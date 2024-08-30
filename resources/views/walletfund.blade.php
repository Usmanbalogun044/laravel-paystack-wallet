

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="{{asset('tailwind.js')}}"></script>
</head>
<body>
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold">Fund Your Wallet</h2>
        <form action="{{route('wallet')}}"  method="POST">
            @csrf
            <div class="mt-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount (NGN)</label>
                <input type="number" name="amount" id="amount" class="mt-1 block w-full border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Fund Wallet</button>
        </form>
    </div>
    @endsection
</body>
</html>

