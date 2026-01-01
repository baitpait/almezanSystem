<div class="container mx-auto p-4 lg:p-6">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1>Roles & Permissions</h1>
                <p>Configure access across modules</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="btn-add btn-action" wire:click="save">Save Permissions</button>
                <button class="btn-visit btn-action" wire:click="setPreset('full')">Full Access</button>
                <button class="btn-cancel btn-action" wire:click="setPreset('view')">View Only</button>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-6 shadow-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm">{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Roles List --}}
        <div class="card-modern lg:col-span-1">
            <div class="card-header">
                <h3 class="text-base font-semibold text-gray-800">Roles</h3>
            </div>
            <div class="card-body flex flex-col gap-2">
                @foreach($roleLabels as $key => $label)
                    <button
                        class="btn-action w-full justify-start {{ $activeRole === $key ? 'btn-add' : 'bg-white border border-gray-200 hover:bg-gray-50' }}"
                        wire:click="setRole('{{ $key }}')">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Permissions Matrix --}}
        <div class="lg:col-span-3 space-y-4">
            <div class="card-modern">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-800">Permissions for: {{ $roleLabels[$activeRole] ?? $activeRole }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="data-table-container overflow-x-auto">
                        <table class="data-table min-w-full">
                            <thead>
                                <tr>
                                    <th class="sticky left-0 z-10 bg-gray-50">Module</th>
                                    @foreach($actions as $actionKey => $actionLabel)
                                        <th class="text-center">{{ $actionLabel }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                    <tr>
                                        <td class="sticky left-0 z-10 bg-white font-semibold text-gray-900">{{ $module }}</td>
                                        @foreach($actions as $actionKey => $actionLabel)
                                            @php
                                                $allowed = $permissions[$module][$activeRole][$actionKey] ?? false;
                                            @endphp
                                            <td class="text-center">
                                                <button type="button"
                                                        class="badge-status {{ $allowed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}"
                                                        wire:click="toggle('{{ $module }}','{{ $actionKey }}')">
                                                    {{ $allowed ? 'Yes' : 'No' }}
                                                </button>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

