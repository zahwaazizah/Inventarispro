@extends('layout')

@section('title', 'Welcome to InventarisPro')

@section('content')
<div class="bg-linear-to-br from-blue-600 to-blue-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl font-bold mb-6">Welcome to InventarisPro</h1>
            <p class="text-xl text-blue-100 mb-8">
                Manage your inventory efficiently and effortlessly
            </p>
            @guest
                <div class="flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Get Started
                    </a>
                </div>
            @else
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                    Go to Dashboard
                </a>
            @endguest
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="text-3xl mb-4">📊</div>
            <h3 class="text-xl font-semibold mb-2">Real-time Analytics</h3>
            <p class="text-gray-600">
                Track your inventory in real-time with detailed analytics and insights.
            </p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="text-3xl mb-4">🔒</div>
            <h3 class="text-xl font-semibold mb-2">Secure</h3>
            <p class="text-gray-600">
                Your data is protected with industry-standard security measures.
            </p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="text-3xl mb-4">⚡</div>
            <h3 class="text-xl font-semibold mb-2">Fast & Reliable</h3>
            <p class="text-gray-600">
                Lightning-fast performance with 99.9% uptime guarantee.
            </p>
        </div>
    </div>
</div>
@endsection
