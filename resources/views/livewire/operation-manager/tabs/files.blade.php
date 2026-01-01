<div class="space-y-6">
    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session()->has('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm">Upload Assessment files (images or PDFs). Add description and select eye for each file.</span>
    </div>

    @if(!$editingId)
        <div class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="text-sm">Please save the operation first before uploading files.</span>
        </div>
    @endif

    {{-- Upload New File --}}
    <div class="card bg-base-200 p-4">
        <h3 class="font-semibold mb-4">Upload New File</h3>
        <div class="space-y-4">
            <div class="form-group">
                <label class="form-label">Eye</label>
                <select wire:model="newFileEye" class="form-select form-select-sm">
                    <option value="OU">OU (Both)</option>
                    <option value="OD">OD (Right)</option>
                    <option value="OS">OS (Left)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">File (Image or PDF)</label>
                <input type="file" wire:model="newFile" class="file-input file-input-bordered w-full file-input-sm" accept="image/*,.pdf">
                @error('newFile') 
                    <div class="text-error text-xs mt-1">{{ $message }}</div> 
                @enderror
                @if($newFile)
                    <div class="text-xs text-gray-500 mt-1">Selected: {{ $newFile->getClientOriginalName() }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea wire:model="newFileDescription" class="textarea textarea-bordered w-full textarea-sm" rows="2" placeholder="Enter file description..."></textarea>
            </div>
            <div class="form-group flex justify-end">
            <button type="button" 
                    wire:click="uploadFile" 
                        class="btn-add btn-action flex items-center gap-2" 
                    wire:loading.attr="disabled"
                    wire:target="uploadFile">
                    <span wire:loading.remove wire:target="uploadFile" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload File
                </span>
                <span wire:loading wire:target="uploadFile" class="flex items-center gap-2">
                    <span class="loading loading-spinner loading-sm"></span>
                    Uploading...
                </span>
            </button>
            </div>
        </div>
    </div>

    {{-- Uploaded Files List --}}
    @if($operationFiles && $operationFiles->count() > 0)
        <div class="card bg-base-200 p-4">
            <h3 class="font-semibold mb-4">Uploaded Files</h3>
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px; min-width: 80px; color: #2563eb !important; font-weight: 700 !important; font-size: 0.875rem !important;">Eye</th>
                            <th style="min-width: 400px;">Description</th>
                            <th class="text-right" style="width: 100px; min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($operationFiles as $file)
                            <tr>
                                <td style="width: 80px; min-width: 80px;">
                                    <span class="badge badge-outline" style="color: #2563eb !important; font-weight: 600 !important; font-size: 0.875rem !important; border-color: #2563eb !important;">{{ $file->eye }}</span>
                                </td>
                                <td style="min-width: 400px;">
                                    <span class="text-sm text-gray-600 break-words" title="{{ $file->description ?? '-' }}">{{ $file->description ?? '-' }}</span>
                                </td>
                                <td class="sticky right-0 z-10 bg-white text-right" style="position: relative; width: 100px; min-width: 100px;">
                                    <div class="relative inline-block" data-dropdown-container="{{ $file->id }}" style="position: relative;">
                                        <button type="button" 
                                                class="btn btn-sm btn-ghost" 
                                                onclick="toggleSimpleDropdown({{ $file->id }}, event)"
                                                data-dropdown-trigger="{{ $file->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                        </button>
                                        <div class="simple-dropdown-menu" 
                                             id="dropdown-menu-{{ $file->id }}"
                                             data-dropdown-menu="{{ $file->id }}"
                                             data-original-parent="{{ $file->id }}"
                                             style="display: none;">
                                            <ul class="dropdown-menu-list">
                                @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                                                    <li>
                                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($file->file_path) }}" 
                                                           target="_blank" 
                                                           rel="noopener noreferrer" 
                                                           class="dropdown-menu-item dropdown-menu-item-view" 
                                                           onclick="setTimeout(() => closeSimpleDropdown({{ $file->id }}), 100); return true;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                                            <span>View</span>
                                    </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($file->file_path) }}" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer" 
                                                       download
                                                       class="dropdown-menu-item dropdown-menu-item-view" 
                                                       onclick="setTimeout(() => closeSimpleDropdown({{ $file->id }}), 100); return true;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                                        <span>Download</span>
                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" 
                                                            class="dropdown-menu-item dropdown-menu-item-delete" 
                                                            wire:click="deleteFile({{ $file->id }})" 
                                                            wire:confirm="Are you sure you want to delete this file?" 
                                                            onclick="closeSimpleDropdown({{ $file->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                                        <span>Delete</span>
                                </button>
                                                </li>
                                            </ul>
                            </div>
                        </div>
                                </td>
                            </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card bg-base-200 p-4">
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>No files uploaded yet.</p>
            </div>
        </div>
    @endif
</div>
