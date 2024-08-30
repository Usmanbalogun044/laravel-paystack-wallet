
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="https://unpkg.com/tailwindcss@^3.3/dist/tailwind.min.css" rel="stylesheet"> -->

    <script src="{{asset('tailwind.js')}}"></script>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="w-full max-w-xl mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-lg mt-10">
    <h5 class="mb-4 text-xl font-medium text-gray-500">Account Balance</h5>
    <div class="flex items-baseline text-gray-900">
        <span class="text-3xl font-semibold">₦</span>
        <span class="text-5xl font-extrabold tracking-tight">{{ $wallet->balance }}</span>
    </div>
    <a href="{{route('fundwallet')}}">
    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center mt-4">
        Add Fund
    </button>
    </a>
</div>
@endsection
</body>
</html>

