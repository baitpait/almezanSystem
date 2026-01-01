<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rules\Password;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $notes;
    public $photo;
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';
    public $showPasswordForm = false;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->notes = $user->notes ?? '';
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $user = auth()->user();
        $updateData = [
            'name' => $this->name,
            'phone' => $this->phone,
            'notes' => $this->notes,
        ];

        // Handle photo upload
        if ($this->photo) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Store new photo
            $photoPath = $this->photo->store('profile-photos', 'public');
            $updateData['photo'] = $photoPath;
        }

        $user->update($updateData);

        // Reset photo property after update
        $this->photo = null;

        session()->flash('message', 'Profile updated successfully.');
        $this->dispatch('profile-updated');
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => ['required', 'string', 'min:8', Password::defaults()],
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'confirmPassword.same' => 'The new password confirmation does not match.',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');
            return;
        }

        // Laravel automatically hashes password due to 'password' => 'hashed' cast in User model
        $user->update([
            'password' => $this->newPassword,
        ]);

        $this->reset(['currentPassword', 'newPassword', 'confirmPassword', 'showPasswordForm']);
        session()->flash('message', 'Password updated successfully.');
        $this->dispatch('password-updated');
    }

    public function togglePasswordForm()
    {
        $this->showPasswordForm = !$this->showPasswordForm;
        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $user = auth()->user();
        return view('livewire.profile', [
            'user' => $user,
        ])->layout('components.layouts.app', [
            'title' => 'Profile',
        ]);
    }
}
