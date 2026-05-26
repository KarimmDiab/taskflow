<?php

namespace App\Livewire\Forms;

use App\Models\Color;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ColorForm extends Form
{
    public ?Color $color = null;

    public string $color_name = '';

    public string $color_hex_code = '';

    public bool $is_active = true;  // default active

    public function rules(): array
    {
        return [
            'color_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('colors', 'color_name')->ignore($this->color?->id)->whereNull('deleted_at'),
            ],
            'color_hex_code' => [
                'required',
                'string',
                'max:7',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'color_name.required' => 'اسم اللون مطلوب',
            'color_name.unique' => 'اسم اللون مستخدم بالفعل',
            'color_name.regex' => 'اسم اللون يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',

            'color_hex_code.required' => 'كود اللون مطلوب',
            'color_hex_code.regex' => 'كود اللون يجب أن يكون بصيغة Hex صحيحة مثل #FF0000 أو #F00',
        ];
    }

    public function setColor(Color $color): void
    {
        $this->color = $color;
        $this->color_name = $color->color_name;
        $this->color_hex_code = $color->color_hex_code ?? '';
        $this->is_active = (bool) $color->is_active;
    }

    public function store(): void
    {
        $data = $this->validate();
        Color::create($data);
        $this->reset();
    }

    public function update(): void
    {
        $data = $this->validate();
        $this->color->update($data);
    }
}
