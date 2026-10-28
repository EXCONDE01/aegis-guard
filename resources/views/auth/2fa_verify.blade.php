@extends('layouts.admin')
@section('content')
<div class="max-w-md mx-auto bg-white p-8 border rounded-lg shadow-sm mt-10">
    <form method="POST" action="{{ route('2fa.check') }}">
        @csrf
        <h3 class="text-xl font-bold mb-2">Two-Factor Authentication</h3>
        <p class="text-sm text-gray-600 mb-6">Open your Authenticator app and enter the 6-digit code.</p>
        
        <input type="text" name="otp" maxlength="6" placeholder="000000" class="w-full text-center tracking-widest text-3xl p-4 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 mb-2" required autocomplete="one-time-code">
        
        @error('otp')
            <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
        @enderror
        
        <button type="submit" class="mt-6 bg-slate-900 text-white px-4 py-3 rounded w-full font-bold uppercase tracking-wider">Verify Login</button>
    </form>
</div>
@endsection