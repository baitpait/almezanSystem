<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            // Update last login timestamp
            try {
                $user = Auth::user();
                // Save current last_login_at as previous before updating
                $user->update([
                    'previous_last_login_at' => $user->last_login_at,
                    'last_login_at' => now()
                ]);
            } catch (\Exception $e) {
                // Ignore if columns don't exist
            }
            
            // Clear permission cache to ensure fresh permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            session()->regenerate();
            session()->forget('error'); // Clear any error messages

            // Redirect using Livewire's redirect method
            $this->redirect(route('dashboard'));
        } else {
            $this->addError('email', 'These credentials do not match our records.');
            $this->addError('password', 'The password is incorrect.');
        }
    }
    
    public function updatedEmail()
    {
        $this->resetErrorBag('email');
    }
    
    public function updatedPassword()
    {
        $this->resetErrorBag('password');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.app');
    }
}
