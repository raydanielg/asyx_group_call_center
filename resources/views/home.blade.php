@extends('layouts.dashboard')

@section('title', 'Dashboard - AYS Call Center')
@section('page_title', 'Dashboard')

@section('content')

{{-- Welcome --}}
<div class="mb-6 flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap">
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Hello {{ Auth::user()->first_name ?? Auth::user()->name ?? 'Agent' }} 👋</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Here's what's happening at the call center today.</p>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <button class="px-2 sm:px-3 py-1.5 text-[11px] sm:text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span class="hidden sm:inline">Export</span>
        </button>
        <a href="#" class="px-2 sm:px-3 py-1.5 text-[11px] sm:text-xs font-medium bg-navy-600 text-white rounded-lg hover:bg-navy-700 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">New Call</span>
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4 mb-6">
    {{-- Total Agents --}}
    <div class="card-sm bg-gradient-to-br from-navy-600 to-navy-700 rounded-xl border border-navy-500 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-navy-100">Total Agents</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-navy-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($totalAgents) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-navy-200 font-medium relative z-10">+{{ $newAgentsThisWeek }} this week</div>
    </div>

    {{-- Active Today --}}
    <div class="card-sm bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl border border-sky-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-sky-100">Active Today</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($activeAgents) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-sky-100 font-medium relative z-10">Agents online now</div>
    </div>

    {{-- Calls Today --}}
    <div class="card-sm bg-gradient-to-br from-copper-400 to-copper-500 rounded-xl border border-copper-300 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-copper-50">Calls Today</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-copper-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format(array_sum($callVolume)) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-copper-50 font-medium relative z-10">Last 7 days total</div>
    </div>

    {{-- Success Rate --}}
    <div class="card-sm bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl border border-purple-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-purple-100">Success Rate</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">94.2%</div>
        <div class="mt-1 text-[10px] sm:text-xs text-purple-100 font-medium relative z-10">+2.1% vs last week</div>
    </div>
</div>

@php
    $volMax = max($callVolume) ?: 1;
    $volSvgPoints = [];
    foreach($callVolume as $i => $vol) {
        $x = round($i * (100 / 6), 2);
        $y = round(40 - (($vol / $volMax) * 35), 2);
        $volSvgPoints[] = "{$x},{$y}";
    }
    $areaPath = "M" . $volSvgPoints[0] . " L" . implode(" L", array_slice($volSvgPoints, 1)) . " L100,40 L0,40 Z";
    $linePoints = implode(" ", $volSvgPoints);
@endphp

{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    {{-- Call Volume Area Chart --}}
    <div class="bg-white rounded-xl border p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Call Volume</h3>
                <p class="text-xs text-gray-400">Last 7 days</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">{{ number_format(array_sum($callVolume)) }}</div>
                <div class="text-xs text-navy-600 font-medium">+12.5%</div>
            </div>
        </div>
        <svg viewBox="0 0 100 40" class="w-full h-56" preserveAspectRatio="none">
            <defs>
                <linearGradient id="volGrad" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0%" stop-color="#0D3E63" stop-opacity="0.4"/>
                    <stop offset="100%" stop-color="#0D3E63" stop-opacity="0"/>
                </linearGradient>
            </defs>
            <line x1="0" y1="10" x2="100" y2="10" stroke="#f3f4f6" stroke-dasharray="2"/>
            <line x1="0" y1="20" x2="100" y2="20" stroke="#f3f4f6" stroke-dasharray="2"/>
            <line x1="0" y1="30" x2="100" y2="30" stroke="#f3f4f6" stroke-dasharray="2"/>
            <path d="{{ $areaPath }}" fill="url(#volGrad)"/>
            <polyline points="{{ $linePoints }}" fill="none" stroke="#0D3E63" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
            @foreach($volSvgPoints as $pt)
                @php list($px, $py) = explode(',', $pt); @endphp
                <circle cx="{{ $px }}" cy="{{ $py }}" r="0.8" fill="#0D3E63"/>
            @endforeach
        </svg>
        <div class="flex justify-between mt-2">
            @foreach($dayLabels as $label)
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            @endforeach
        </div>
    </div>

    {{-- Daily Calls Bar Chart --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-900">Daily Breakdown</h3>
            <p class="text-xs text-gray-400">Last 7 days</p>
        </div>
        <div class="flex items-end gap-2 h-56">
            @foreach($callVolume as $i => $vol)
                @php
                    $pct = ($vol / $volMax) * 100;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-48 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-navy-500" style="height: {{ max($pct, 4) }}%"></div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $dayLabels[$i] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Agents Table --}}
<div class="bg-white rounded-xl border overflow-hidden mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b px-5 py-4 gap-3">
        <div>
            <h3 class="text-sm font-semibold text-gray-900">Recent Agents</h3>
            <p class="text-xs text-gray-400">Latest team members who joined</p>
        </div>
        <a href="#" class="text-xs font-medium text-navy-600 hover:text-navy-700">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    <th class="px-5 py-3 font-medium">Phone</th>
                    <th class="px-5 py-3 font-medium">Joined</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAgents as $agent)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-copper-400 to-copper-600 flex items-center justify-center text-white font-bold text-[10px]">
                                {{ strtoupper(substr($agent->first_name ?? $agent->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $agent->first_name ? $agent->first_name . ' ' . $agent->last_name : ($agent->name ?? 'Unknown') }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $agent->email }}</td>
                    <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $agent->phone ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $agent->created_at?->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        @if($agent->email_verified_at)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-navy-50 text-navy-700 border border-navy-100">Verified</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                        <p class="text-sm font-medium">No agents yet</p>
                        <p class="text-xs mt-1">Agents will appear here once they register.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Bottom Nav --}}
<div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-gray-200/80 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
    <div class="flex items-center justify-around py-2 px-2 max-w-lg mx-auto">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-navy-50 group">
            <div class="w-9 h-9 rounded-full bg-navy-100 flex items-center justify-center group-hover:bg-navy-500 transition-colors">
                <svg class="w-4 h-4 text-navy-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-navy-700 transition-colors">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-navy-50 group">
            <div class="w-9 h-9 rounded-full bg-navy-100 flex items-center justify-center group-hover:bg-navy-500 transition-colors">
                <svg class="w-4 h-4 text-navy-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-navy-700 transition-colors">Agents</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-navy-50 group">
            <div class="w-9 h-9 rounded-full bg-navy-100 flex items-center justify-center group-hover:bg-navy-500 transition-colors">
                <svg class="w-4 h-4 text-navy-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-navy-700 transition-colors">Calls</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-navy-50 group">
            <div class="w-9 h-9 rounded-full bg-navy-100 flex items-center justify-center group-hover:bg-navy-500 transition-colors">
                <svg class="w-4 h-4 text-navy-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-navy-700 transition-colors">Reports</span>
        </a>
    </div>
</div>

<div class="h-16 lg:hidden"></div>

@endsection
