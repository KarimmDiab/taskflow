<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryForm extends Form
{
    use WithFileUploads;

    public ?Category $category = null;

    public string $category_name = '';
    public string $category_description = '';
    public bool $is_active = true;
    public bool $is_featured = false;

    public $image_path = null;               // temporary uploaded file
    public ?string $existing_image = null; // current image path

    public function rules(): array
    {
        return [
            'category_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('categories', 'category_name')
                    ->ignore($this->category?->id)
                    ->whereNull('deleted_at'),
            ],
            'category_description' => 'nullable|string|max:500|regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'اسم التصنيف مطلوب',
            'category_name.unique' => 'اسم التصنيف مستخدم بالفعل',
            'category_name.regex' => 'اسم التصنيف يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
            'category_name.max' => 'اسم التصنيف لا يجب أن يتجاوز 255 حرف',

            'category_description.max' => 'الوصف لا يجب أن يتجاوز 500 حرف',
            'category_description.regex' => 'الوصف يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',

            'image_path.image' => 'الملف يجب أن يكون صورة',
            'image_path.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif, webp',
            'image_path.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميغابايت',
        ];
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
        $this->category_name = $category->category_name;
        $this->category_description = $category->category_description ?? '';
        $this->is_active = (bool) $category->is_active;
        $this->is_featured = (bool) $category->is_featured;
        $this->existing_image = $category->image_path;
        $this->image_path = null;
    }

    protected function getImageDirectory(): string
    {
        // Sanitize folder name (replace spaces/special chars)
        $folder = preg_replace('/[^A-Za-z0-9\-_]/', '_', $this->category_name);
        return "categories/{$folder}";
    }

    protected function handleImageUpload(): ?string
    {
        if (!$this->image_path) {
            return $this->existing_image;
        }

        // Delete old image if exists
        if ($this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
            Storage::disk('public')->delete($this->existing_image);
        }

        // Store new image
        return $this->image_path->store($this->getImageDirectory(), 'public');
    }

    public function store(): void
    {
        $validated = $this->validate();
        $imagePath = $this->handleImageUpload();

        $this->category = Category::create([
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'],
            'is_active' => $validated['is_active'],
            'is_featured' => $validated['is_featured'],
            'image_path' => $imagePath,
        ]);

        $this->resetForm();
    }

    public function update(): void
    {
        $validated = $this->validate();
        $imagePath = $this->handleImageUpload();

        $this->category->update([
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'],
            'is_active' => $validated['is_active'],
            'is_featured' => $validated['is_featured'],
            'image_path' => $imagePath,
        ]);

        $this->existing_image = $this->category->image_path;
        $this->image_path = null;
    }

    public function resetForm(): void
    {
        $this->reset([
            'category_name',
            'category_description',
            'is_active',
            'is_featured',
            'image_path',
            'existing_image',
        ]);
        $this->is_active = true;
        $this->is_featured = false;
        $this->category = null;
    }
}
