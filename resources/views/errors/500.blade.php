@extends('errors::layout')

@section('title', __('Server Error'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-red-100 dark:bg-red-900/30 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">500</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Initial Server Error') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('Something went wrong on our servers. We are already looking into it.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Try Again') }}
        </button>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl transition-all duration-200">
            {{ __('Back to Safe Zone') }}
        </a>
    </div>
@endsection
