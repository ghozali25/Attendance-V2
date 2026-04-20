@extends('errors::layout')

@section('title', __('Payload Too Large'))

@section('content')
    <div class="mb-6 flex justify-center">
        <div class="p-4 bg-orange-100 dark:bg-orange-900/30 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-orange-600 dark:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-2">413</h1>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('File Too Large') }}</h2>

    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ __('The file you are trying to upload exceeds the server limit. Please try a smaller file.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button onclick="history.back()" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200">
            {{ __('Try Again') }}
        </button>
    </div>
@endsection
