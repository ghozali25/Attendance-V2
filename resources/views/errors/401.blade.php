@extends('errors::layout')

@section('title', __('Unauthorized'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">401</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('Unauthorized') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('You are not authorized to access this page. Please log in to continue.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Log In') }}
        </a>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl transition-all duration-200">
            {{ __('Go Home') }}
        </a>
    </div>
@endsection
