<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">User Management</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage system users and their permissions</p>
        </div>
        <button class="btn btn-primary btn-sm gap-2" wire:click="create">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add User
        </button>
    </div>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Search --}}
    <div class="card bg-base-100 shadow-lg mb-4">
        <div class="card-body p-4">
            <input type="text" 
                   class="input input-bordered w-full" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Search by name or email...">
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card bg-base-100 shadow-lg">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="hover">
                            <td class="font-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-outline badge-info">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td>{{ $user->branch->name ?? '-' }}</td>
                            <td>
                                @if($user->is_active)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-error">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex gap-2 justify-end">
                                    <button class="btn btn-xs btn-info" wire:click="edit({{ $user->id }})">Edit</button>
                                    <button class="btn btn-xs btn-error" wire:click="delete({{ $user->id }})" onclick="return confirm('Are you sure?')">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-base-content/50 py-8">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body pt-0">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="modal modal-open">
        <div class="modal-box max-w-2xl">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-base-300">
                <div>
                    <h2 class="text-xl font-bold">{{ $editingId ? 'Edit User' : 'Add User' }}</h2>
                    <p class="text-xs text-base-content/70 mt-0.5">{{ $editingId ? 'Update user information' : 'Create a new user account' }}</p>
                </div>
                <button class="btn btn-xs btn-circle btn-ghost" wire:click="resetForm">✕</button>
            </div>

            <form wire:submit.prevent="save" autocomplete="off">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Full Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" wire:model.defer="form.name" required>
                        @error('form.name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Email <span class="text-error">*</span></span>
                        </label>
                        <input type="email" class="input input-bordered w-full" wire:model.defer="form.email" required>
                        @error('form.email') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Phone Number</span>
                        </label>
                        <input type="tel" class="input input-bordered w-full" wire:model.defer="form.phone" placeholder="Enter phone number">
                        @error('form.phone') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Password @if(!$editingId)<span class="text-error">*</span>@endif</span>
                        </label>
                        <input type="password" class="input input-bordered w-full" wire:model.defer="form.password" @if(!$editingId) required @endif>
                        @error('form.password') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        @if($editingId)
                        <label class="label">
                            <span class="label-text-alt">Leave blank to keep current password</span>
                        </label>
                        @endif
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Role <span class="text-error">*</span></span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="form.role" required>
                            <option value="secretary">Secretary</option>
                            <option value="doctor">Doctor</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('form.role') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Branch</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="form.branch_id">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('form.branch_id') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select class="select select-bordered w-full" wire:model.defer="form.is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Notes</span>
                        </label>
                        <textarea class="textarea textarea-bordered w-full" wire:model.defer="form.notes" rows="3" placeholder="Enter any general notes about this user..."></textarea>
                        @error('form.notes') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">These notes are private and only visible to administrators</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 pt-3 border-t border-base-300 flex gap-2 justify-end">
                    <button type="button" class="btn btn-ghost btn-sm" wire:click="resetForm">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
        <div class="modal-backdrop" wire:click="resetForm"></div>
    </div>
    @endif
</div>
