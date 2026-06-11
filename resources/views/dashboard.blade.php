@extends('layout')

@section('title', 'Dashboard - InventarisPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-4xl font-bold mb-2">Welcome, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-600 mb-8">
            You are logged in. Start managing your inventory now.
        </p>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                <h2 class="text-xl font-semibold text-blue-900 mb-2">📦 Inventory</h2>
                <p class="text-blue-700">
                    Manage your products and stock levels efficiently.
                </p>
                <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Go to Inventory
                </button>
            </div>

            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                <h2 class="text-xl font-semibold text-green-900 mb-2">📊 Reports</h2>
                <p class="text-green-700">
                    View detailed reports and analytics about your inventory.
                </p>
                <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    View Reports
                </button>
            </div>

            <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                <h2 class="text-xl font-semibold text-purple-900 mb-2">⚙️ Settings</h2>
                <p class="text-purple-700">
                    Configure your account and preferences.
                </p>
                <button class="mt-4 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    Go to Settings
                </button>
            </div>

            <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                <h2 class="text-xl font-semibold text-orange-900 mb-2">📞 Support</h2>
                <p class="text-orange-700">
                    Need help? Contact our support team.
                </p>
                <button class="mt-4 bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                    Contact Support
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
