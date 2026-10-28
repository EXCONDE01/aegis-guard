@extends('layouts.admin')
@section('content')
<div class="max-w-md mx-auto bg-white p-8 border rounded-lg shadow-sm mt-10 text-center">
    <h3 class="text-xl font-bold mb-4">Set Up Two-Factor Authentication</h3>
    <p class="text-sm text-gray-600 mb-6">Scan this QR code with the Google Authenticator app on your mobile device.</p>
    
    <div class="flex justify-center mb-6">
        {!! $qrCodeSvg !!}
    </div>
    
    <p class="text-xs text-gray-400 mb-6">Manual Code: <span class="font-mono text-gray-800">{{ $secret }}</span></p>
    
    <a href="{{ route('2fa.verify') }}" class="bg-slate-900 text-white px-6 py-3 rounded w-full font-bold inline-block mt-4">Proceed to Verification</a>
    
    <a href="{{ route('2fa.verify') }}" class="bg-indigo-600 text-white px-6 py-3 rounded w-full font-bold inline-block">Proceed to Verification</a>
</div>
@endsection