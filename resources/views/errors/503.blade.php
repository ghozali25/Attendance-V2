@extends('errors::layout')

@section('title', __('Service Unavailable'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-blue-100 dark:bg-blue-900/30 rounded-full animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">503</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Under Maintenance') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('We are currently updating the system to give you a better experience. We will be back shortly.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Check Again') }}
        </button>
    </div>
@endsection
