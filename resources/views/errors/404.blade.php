@extends('errors::layout')

@section('title', __('Page Not Found'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-red-100 dark:bg-red-900/30 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">404</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Page Not Found') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('Sorry, the page you are looking for does not exist or has been moved.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Go Home') }}
        </a>
        <button onclick="history.back()" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl transition-all duration-200">
            {{ __('Go Back') }}
        </button>
    </div>
@endsection
