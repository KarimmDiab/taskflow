<?php

namespace App\Livewire\Forms;

use App\Models\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Livewire\WithFileUploads;   // 🟢 add this for slug

class CollectionForm extends Form
{
    use WithFileUploads;

    public ?Collection $collection = null;

    public string $collection_name = '';

    public string $collection_desc = '';

    public bool $is_active = true;

    public $main_image = null;

    public ?string $existing_image = null;

    public function rules(): array
    {
        return [
            'collection_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('collections', 'collection_name')
                    ->ignore($this->collection?->id)
                    ->whereNull('deleted_at'),
            ],
            'collection_desc' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'collection_name.required' => 'اسم المجموعة مطلوب',
            'collection_name.unique' => 'اسم المجموعة مستخدم بالفعل',
            'collection_name.regex' => 'اسم المجموعة يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
            'collection_desc.max' => 'وصف المجموعة يجب ألا يزيد عن 1000 حرف',
            'main_image.image' => 'الملف يجب أن يكون صورة',
            'main_image.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif, webp',
            'main_image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميغابايت',
        ];
    }

    public function setCollection(Collection $collection): void
    {
        $this->collection = $collection;
        $this->collection_name = $collection->collection_name;
        $this->collection_desc = $collection->collection_desc ?? '';
        $this->is_active = (bool) $collection->is_active;
        $this->existing_image = $collection->main_image;
        $this->main_image = null;
    }

    protected function getImageDirectory(): string
    {
        $folder = preg_replace('/[^A-Za-z0-9\-_]/', '_', $this->collection_name);

        return "collection/{$folder}";
    }

    protected function handleImageUpload(): ?string
    {
        if (! $this->main_image) {
            return $this->existing_image;
        }

        // Delete old image if exists
        if ($this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
            Storage::disk('public')->delete($this->existing_image);
        }

        // Store new image
        return $this->main_image->store($this->getImageDirectory(), 'public');
    }

    /**
     * Generate a unique slug from the given name.
     */
    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        $query = Collection::withTrashed()->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $original.'-'.$counter++;
            $query = Collection::withTrashed()->where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    public function store(): void
    {
        $validated = $this->validate();
        $imagePath = $this->handleImageUpload();

        // Generate unique slug
        $slug = $this->generateUniqueSlug($validated['collection_name']);

        $this->collection = Collection::create([
            'collection_name' => $validated['collection_name'],
            'slug' => $slug,
            'collection_desc' => $validated['collection_desc'],
            'is_active' => $validated['is_active'],
            'main_image' => $imagePath,
        ]);

        $this->resetForm();
    }

    public function update(): void
    {
        $validated = $this->validate();
        $imagePath = $this->handleImageUpload();

        $updateData = [
            'collection_name' => $validated['collection_name'],
            'collection_desc' => $validated['collection_desc'],
            'is_active' => $validated['is_active'],
            'main_image' => $imagePath,
        ];

        // Only update slug if the name has changed
        if ($this->collection->collection_name !== $validated['collection_name']) {
            $updateData['slug'] = $this->generateUniqueSlug(
                $validated['collection_name'],
                $this->collection->id
            );
        }

        $this->collection->update($updateData);

        // Refresh existing_image with the final image path
        $this->existing_image = $this->collection->main_image;
        $this->main_image = null;
    }

    public function resetForm(): void
    {
        $this->reset([
            'collection_name',
            'collection_desc',
            'is_active',
            'main_image',
            'existing_image',
        ]);
        $this->is_active = true;
        $this->collection = null;
    }
}
