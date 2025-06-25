<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    <br>
{{-- 
                    <form method="POST" target="_blank" action="{{ route('sso.redirect.foodpanda') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Login FoodPanda
                        </button>
                    </form> --}}

                    {{-- <button class="mt-5">
                        <a href="http://127.0.0.1:8001/dashboard" target="_blank">Login FoodPanda Account</a>
                    </button> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
