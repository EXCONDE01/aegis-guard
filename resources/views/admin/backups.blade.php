@extends('layouts.admin')
@section('page_title', 'System Backups')
@section('header_title', 'Disaster Recovery Center')
@section('header_subtitle', 'Manage automated MySQL snapshots, monitor local storage analytics, and configure remote replication.')

@section('header_actions')
    <form method="POST" action="{{ route('admin.backups.generate') }}">
        @csrf
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[13px] font-bold px-6 py-2.5 rounded shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
            Generate Snapshot
        </button>
    </form>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Storage Analytics -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 relative overflow-hidden">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Vault Capacity</p>
                <h3 class="text-2xl font-bold text-gray-900 font-telemetry">{{ $totalSizeFormatted ?? '0.00' }} MB</h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
            </div>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2">
            <div class="bg-sky-500 h-1.5 rounded-full" style="width: 15%"></div>
        </div>
        <p class="text-[10px] text-gray-500 font-medium">Automated {{ $retentionDays }}-day retention pruning is active.</p>
    </div>

    <!-- AWS Replication Status -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 lg:col-span-2">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 relative">
                    <div class="absolute inset-0 rounded-full border border-emerald-200 animate-ping opacity-50"></div>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" /></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">AWS EC2 Off-Site Replication</h3>
                    <p class="text-[11px] text-gray-500 mt-0.5">Secure tunnel established via Nginx reverse proxy.</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-emerald-50 text-emerald-700 text-[9px] font-bold uppercase tracking-widest border border-emerald-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> SYNC SECURE
                </span>
                <p class="text-[10px] font-telemetry text-gray-400 mt-2">Target: us-east-1 instance</p>
            </div>
        </div>
    </div>
</div>

<!-- Snapshot Vault Table -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
        <h2 class="text-sm font-bold text-gray-900 tracking-tight">Available Snapshots</h2>
    </div>
    
    @if(count($backups) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-500 font-bold border-b border-gray-200">
                        <th class="p-4 pl-6">Snapshot Identity</th>
                        <th class="p-4">Timestamp</th>
                        <th class="p-4">File Size</th>
                        <th class="p-4 pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($backups as $backup)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4 pl-6 font-telemetry font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            {{ $backup['name'] }}
                        </td>
                        <td class="p-4 text-gray-600 font-telemetry text-xs">{{ $backup['date'] }}</td>
                        <td class="p-4 text-gray-600 font-telemetry text-xs">{{ $backup['size'] }}</td>
                        <td class="p-4 pr-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.backups.download', $backup['name']) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[10px] font-bold uppercase tracking-widest rounded transition-colors">
                                    Download
                                </a>
                                <form method="POST" action="{{ route('admin.backups.restore', $backup['name']) }}" onsubmit="return confirm('DANGER: This will overwrite the live database with this snapshot. Proceed?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-[10px] font-bold uppercase tracking-widest rounded transition-colors">
                                        Rollback
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-16 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900">Vault Empty</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-md">No system snapshots have been generated yet. Create one now to establish a baseline.</p>
        </div>
    @endif
</div>
@endsection