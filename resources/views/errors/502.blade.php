@extends('errors::layout')

@section('title', __('Bad Gateway'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-red-100 dark:bg-red-900/30 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">502</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Bad Gateway') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('We received an invalid response from the upstream server. Please try again later.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Refresh') }}
        </button>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl transition-all duration-200">
            {{ __('Go Home') }}
        </a>
    </div>
@endsection
