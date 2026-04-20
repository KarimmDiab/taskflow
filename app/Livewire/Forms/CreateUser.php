<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateUser extends Form
{
    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public ?string $email_verified_at = null;

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user?->id),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
            ],
            'email_verified_at' => [
                'nullable',
                'date',
            ],

        ];
    }

    public function messages(): array
    {
        return [
            // name
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نص.',
            'name.max' => 'الاسم لا يجب أن يزيد عن 255 حرف.',
            'name.regex' => 'الاسم يسمح بالحروف العربية والإنجليزية والأرقام والمسافات والشرطة فقط.',

            // email
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.max' => 'البريد الإلكتروني لا يجب أن يزيد عن 255 حرف.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',

            // password
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string' => 'كلمة المرور يجب أن تكون نص.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير، وحرف صغير، ورقم، ورمز خاص مثل: @ $ ! % * ? & #',
        ];
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';

    }

    public function store()
    {
        $data = $this->validate();
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now(); // مش مفعل لسه

        User::create($data);
        $this->reset();
    }

    public function update()
    {
        // Validate only name and email, skip password validation
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user?->id),
            ],
        ]);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // لو تم تغيير الإيميل → نلغي التفعيل
        if ($this->email !== $this->user->email) {
            $updateData['email_verified_at'] = null;
        }

        if (! empty($this->password)) {

            $this->validate(
                [
                    'password' => [
                        'string',
                        'min:8',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
                    ],
                ],
                [
                    'password.string' => 'كلمة المرور يجب أن تكون نص.',
                    'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
                    'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير، وحرف صغير، ورقم، ورمز خاص مثل: @ $ ! % * ? & #',
                ]
            );

            $updateData['password'] = Hash::make($this->password);
        }
        $this->user->update($updateData);
    }
}
