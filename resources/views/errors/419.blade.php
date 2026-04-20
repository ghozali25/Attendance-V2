@extends('errors::layout')

@section('title', __('Page Expired'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-yellow-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">419</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Session Expired') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('Your session has expired due to inactivity. Please refresh the page to continue.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Refresh Page') }}
        </button>
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl transition-all duration-200">
            {{ __('Login Again') }}
        </a>
    </div>
@endsection
