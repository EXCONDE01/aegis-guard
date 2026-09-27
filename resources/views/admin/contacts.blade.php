@extends('layouts.admin')
@section('page_title', 'Alert Directory | Aegis-Guard')
@section('header_title', 'Emergency Contacts')
@section('header_subtitle', 'Manage emergency responders, configure escalation tiers, and test API communication links.')

@section('header_actions')
    <!-- This button triggers the Alpine.js modal at the bottom of the file -->
    <button @click="$dispatch('open-add-contact-modal')" class="bg-sky-600 hover:bg-sky-700 text-white text-[13px] font-bold px-6 py-2.5 rounded shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Add Responder
    </button>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($contacts as $contact)
        @php
            $isActive = $contact->is_active ?? true;
            $role = $contact->role ?? 'Responder';
            
            $cardBorder = $isActive ? 'border-gray-200 hover:border-sky-300' : 'border-gray-200 bg-gray-50 opacity-75';
            $topAccent = $isActive ? 'bg-sky-500' : 'bg-gray-400';
            $roleBadge = $role === 'System Admin' ? 'bg-violet-100 text-violet-700 border-violet-200' : 'bg-sky-100 text-sky-700 border-sky-200';
            
            // Extract initials for the avatar circle (e.g., "John Doe" becomes "JD")
            $initials = collect(explode(' ', $contact->name))->map(function($segment) { return strtoupper(substr($segment, 0, 1)); })->take(2)->join('');
        @endphp

        <div class="bg-white border {{ $cardBorder }} rounded-xl shadow-sm relative overflow-hidden transition-all duration-300 group">
            <!-- Top Color Bar -->
            <div class="h-1.5 w-full {{ $topAccent }}"></div>
            
            <div class="p-6">
                <!-- Header: Avatar & Status -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full {{ $isActive ? 'bg-sky-50 text-sky-600 border-sky-100' : 'bg-gray-100 text-gray-500 border-gray-200' }} border flex items-center justify-center font-black text-lg shadow-sm">
                            {{ $initials }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $contact->name }}</h3>
                            <span class="inline-block px-2 py-0.5 mt-1 rounded text-[9px] font-bold uppercase tracking-widest border {{ $roleBadge }}">
                                {{ $role }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Duty Status Indicator -->
                    <span class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest {{ $isActive ? 'text-emerald-600' : 'text-gray-400' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
                        {{ $isActive ? 'On-Duty' : 'On-Leave' }}
                    </span>
                </div>

                <!-- Contact Details -->
                <div class="space-y-3 bg-gray-50/50 rounded-lg p-4 border border-gray-100 mb-6">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg> Phone Number
                        </p>
                        <p class="font-telemetry text-sm text-gray-800 font-bold">{{ $contact->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg> Pushover API Key
                        </p>
                        <p class="font-telemetry text-xs text-sky-600 font-bold truncate" title="{{ $contact->pushover_key ?? $contact->user_key ?? 'N/A' }}">
                            {{ $contact->pushover_key ?? $contact->user_key ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <!-- Toggle Duty Status -->
                    <form method="POST" action="{{ route('admin.contacts.toggle', $contact->id) }}" class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full py-2 px-3 rounded bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-[10px] font-bold uppercase tracking-widest transition-colors shadow-sm">
                            {{ $isActive ? 'Set Off-Duty' : 'Set Active' }}
                        </button>
                    </form>
                    
                    <!-- Dispatch Test Ping -->
                    <form method="POST" action="{{ route('admin.contacts.test', $contact->id) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2 px-3 rounded bg-sky-50 border border-sky-200 hover:bg-sky-100 text-sky-700 text-[10px] font-bold uppercase tracking-widest transition-colors shadow-sm flex justify-center items-center gap-1.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Test Ping
                        </button>
                    </form>
                    
                    <!-- Delete Button -->
                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" onsubmit="return confirm('Permanently remove this responder from the system?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded bg-white border border-gray-200 hover:bg-red-50 hover:border-red-200 hover:text-red-600 text-gray-400 transition-colors shadow-sm" title="Remove Contact">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <!-- Empty State -->
        <div class="col-span-full p-16 flex flex-col items-center justify-center text-center bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900">No Responders Configured</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-md">Add emergency contacts and their Pushover API keys to enable automated SMS dispatch.</p>
        </div>
    @endforelse
</div>
@endsection

@section('modals')
<!-- Add Responder Modal -->
<div x-data="{ showAddModal: false }" @open-add-contact-modal.window="showAddModal = true">
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center" x-transition.opacity>
        <!-- Background Blur -->
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>
        
        <!-- Modal Content -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-2xl p-8 max-w-md w-full relative z-10" 
             x-show="showAddModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <h3 class="text-xl font-bold text-gray-900 mb-1">Register Responder</h3>
            <p class="text-xs text-gray-500 mb-6">Provision a new emergency contact for the automated SMS broadcast list.</p>
            
            <!-- Target the store route you have defined in web.php -->
            <form method="POST" action="{{ route('admin.contacts.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Full Name</label>
                    <input type="text" name="name" required placeholder="e.g., John Doe" 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 p-2.5">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Role / Escalation Tier</label>
                    <select name="role" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 p-2.5">
                        <option value="Primary Responder">Primary Responder (Security)</option>
                        <option value="System Admin">System Admin (IT)</option>
                        <option value="Facility Manager">Facility Manager</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Phone Number</label>
                    <input type="text" name="phone" placeholder="+63 912 345 6789" 
                           class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 p-2.5 font-telemetry">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Pushover User Key</label>
                    <input type="text" name="pushover_key" required placeholder="Paste 30-character key here..." 
                           class="w-full bg-white border border-sky-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 p-2.5 font-telemetry font-bold shadow-sm">
                </div>
                
                <div class="flex justify-end gap-3 mt-8 border-t border-gray-100 pt-5">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded transition-colors">Cancel</button>
                    <button type="submit" class="bg-sky-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold tracking-wider transition-all shadow-sm hover:bg-sky-700 uppercase">Save Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection