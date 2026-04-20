@extends('errors::layout')

@section('title', __('Too Many Requests'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-purple-100 dark:bg-purple-900/30 rounded-full animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-purple-600 dark:text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">429</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Too Many Requests') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('You are making too many requests to our servers. Please wait a moment and try again.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Return Home') }}
        </a>
    </div>
@endsection
