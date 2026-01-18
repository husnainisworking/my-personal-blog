@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>

    <!-- Stats Cards -->
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Views Today -->
         <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Views Today</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($viewsToday) }}</p>
</div>
</div>
</div>

            <!-- Views This Week -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
</svg>
</div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Views This Week</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($viewsThisWeek) }}</p>
</div>
</div>
</div>

            <!-- Views This Month -->
             <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
</svg>
</div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Views This Month</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($viewsThisMonth) }}</p>
</div>
</div>
</div>
</div>
    <!-- Engagement Stats -->
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Average Time on Page</h3>
            <p class="text-3xl font-bold text-indigo-600">
                {{ $avgEngagement && $avgEngagement->avg_time !== null ? round($avgEngagement->avg_time) . 's' : 'N/A'}}
</p>
<p class="text-sm text-gray-500 mt-1">Last 7 days</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Average Scroll Depth</h3>
            <p class="text-3xl font-bold text-green-600">
                {{ $avgEngagement && $avgEngagement->avg_scroll !== null ? round($avgEngagement->avg_scroll) . '%' : 'N/A'}}
</p>
<p class="text-sm text-gray-500 mt-1">Last 7 days</p>
</div>
</div>

<!-- Views by Hour Chart -->
 <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Views Today by Hour</h3>
    <div class="flex items-end space-x-1 h-40">
        @foreach($viewsByHoursComplete as $hour => $count)
        @php
            $maxCount = max($viewsByHoursComplete) ?: 1;
            $height = ($count / $maxCount) * 100;
        @endphp
        <div class="flex-1 flex flex-col items-center">
            <div
                class="w-full bg-indigo-500 rounded-t transition-all duration-300 hover:bg-indigo-600"
                style="height: {{ $height }}%"
                title="{{ $hour }}:00 - {{ $count }} views"
                ></div>
                @if($hour % 4 === 0)
                <span class="text-xs text-gray-500 mt-1">{{ $hour }}</span>
                @endif
</div>
@endforeach
</div>
<div class="flex justify-between text-xs text-gray-500 mt-2">
    <span>12am</span>
    <span>12pm</span>
    <span>11pm</span>
</div>
</div>

<!-- Views by Day Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Views Last 7 Days</h3>
        <div class="flex items-end space-x-2 h-40">
            @forelse($viewsByDay as $date => $count)
            @php
                $maxCount = max($viewsByDay) ?: 1;
                $height = ($count / $maxCount) * 100;
            @endphp
            <div class="flex-1 flex flex-col items-center">
                <div
                    class="w-full bg-green-500 rounded-t transition-all duration-300 hover:bg-green-600"
                    style="height: {{ $height }}%"
                    title="{{ $date }} - {{ $count }} views"
                    ></div>
                    <span class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($date)->format('D')}}</span>
</div>
@empty
<p class="text-gray-500 text-sm">No data available</p>
@endforelse
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Posts -->
     <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Posts (Last 7 Days)</h3>
        @if($topPosts->count() > 0)
        <div class="space-y-3">
            @foreach($topPosts as $post)
            <div class="flex items-center justify-between">
                <a href="{{ route('posts.public.show', $post->slug) }}" class="text-sm text-gray-700 hover:text-indigo-600 truncate flex-1 mr-4">
                    {{ $post->title }}
</a>
<span class="text-sm font-medium text-gray-900 whitespace-nowrap">
    {{ number_format($post->views_count) }} views
</span>
</div>
@endforeach
</div>
@else 
<p class="text-gray-500 text-sm">No views recorded yet</p>
@endif
</div>

<!-- Top Referrers -->
 <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
    @if($recentActivity->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
</tr>
</thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recentActivity as $event)
                <tr>
                    <td class="px-4 py-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $event->event_type === 'viewed' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                         {{ ucfirst($event->event_type) }}
</span>
</td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        {{ Str::limit($event->post->title ?? 'Deleted', 30) }}
</td>
                        <td class="px-4 py-2 text-sm text-gray-500">
                            {{ $event->user->name ?? 'Guest'}}
</td>
                        <td class="px-4 py-2 text-sm text-gray-500">
                            {{ $event->created_at->diffForHumans() }}
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@else
<p class="text-gray-500 text-sm">No activity recorded yet</p>
@endif
</div>
</div>
@endsection

                        
 