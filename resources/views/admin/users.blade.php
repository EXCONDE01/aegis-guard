@extends('layouts.admin')
@section('page_title', 'User Management')
@section('header_title', 'Identity & Access Management')
@section('header_subtitle', 'Provision new operator credentials and revoke system access.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Provisioning Form (Left Column) -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                <h2 class="text-sm font-bold text-gray-900 tracking-tight">Provision New Access</h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Full Name -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Operator Identity</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <input type="text" name="name" required class="w-full pl-10 text-sm border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all shadow-sm" placeholder="e.g. John Doe">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <input type="email" name="email" required class="w-full pl-10 text-sm border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all shadow-sm" placeholder="user@firenet.local">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Temporary Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input type="password" name="password" required class="w-full pl-10 text-sm border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all shadow-sm" placeholder="Min. 8 characters">
                        </div>
                    </div>

                    <!-- Authorization Role (Visual Cards) -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Authorization Level</label>
                        <div class="space-y-3">
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:bg-gray-50 focus:outline-none has-[:checked]:border-sky-500 has-[:checked]:ring-1 has-[:checked]:ring-sky-500 transition-all">
                                <input type="radio" name="role" value="executive" class="sr-only" checked>
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Campus Director</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Read-only dashboard access.</p>
                                        </div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 bg-white peer-checked:border-sky-500 flex items-center justify-center transition-colors">
                                        <div class="w-2 h-2 rounded-full bg-sky-500 opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:bg-gray-50 focus:outline-none has-[:checked]:border-purple-500 has-[:checked]:ring-1 has-[:checked]:ring-purple-500 transition-all">
                                <input type="radio" name="role" value="admin" class="sr-only">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">System Administrator</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Full hardware & db control.</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white text-[13px] font-bold py-3 px-4 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                            Generate Credentials
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Active Directory Table (Right Column) -->
    <div class="lg:col-span-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden h-full flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <h2 class="text-sm font-bold text-gray-900 tracking-tight">Active Personnel Directory</h2>
                </div>
                <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded border border-emerald-100 uppercase tracking-widest flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ count($users) }} Operators
                </span>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] uppercase tracking-widest text-gray-500 font-bold border-b border-gray-100">
                            <th class="p-4 pl-6 font-semibold">Operator Identity</th>
                            <th class="p-4 font-semibold">Authorization Level</th>
                            <th class="p-4 pr-6 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <!-- Dynamic Avatar -->
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-sm
                                        {{ $user->role === 'admin' ? 'bg-gradient-to-br from-purple-500 to-indigo-600' : 'bg-gradient-to-br from-sky-500 to-blue-600' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-sky-700 transition-colors">{{ $user->name }}</p>
                                        <p class="text-[11px] text-gray-500 font-telemetry mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-purple-50 text-purple-700 text-[10px] font-bold uppercase tracking-widest border border-purple-100 shadow-sm">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 text-[10px] font-bold uppercase tracking-widest border border-gray-200 shadow-sm">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Director
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 pr-6 text-right align-middle">
                                @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('WARNING: This will permanently revoke access for this user. Proceed?');" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-white hover:bg-red-50 text-gray-500 hover:text-red-700 border border-gray-200 hover:border-red-200 text-[10px] font-bold uppercase tracking-widest rounded shadow-sm transition-all flex items-center gap-1.5 ml-auto">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Revoke
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-3 py-1.5 rounded border border-gray-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Session
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS to handle the custom radio card active states dynamically */
label:has(input[type="radio"]:checked) .border-2 {
    border-color: #0ea5e9; /* Sky 500 */
}
label:has(input[value="admin"]:checked) .border-2 {
    border-color: #a855f7; /* Purple 500 */
}
label:has(input[type="radio"]:checked) .w-2 {
    opacity: 1;
}
label:has(input[value="admin"]:checked) .w-2 {
    background-color: #a855f7;
}
</style>
@endsection