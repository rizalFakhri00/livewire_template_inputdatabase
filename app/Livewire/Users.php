<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $avatar;

    protected function rules()
    {
        return [
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'avatar'   => 'nullable|image|max:1024', // 1MB
        ];
    }

    public function save()
    {
        $this->validate();

        $avatarPath = null;
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars', 'public');
        }

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'avatar'   => $avatarPath,
        ]);

        // reset form
        $this->reset();

        session()->flash('success', 'User berhasil dibuat');
    }


    public function render()
    {
        return view('livewire.users',[
            "users" => User::all(),
        ]);
    }
}