<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>You are logged in.</p>
    <p><a href="{{ route('home') }}">Back to Home</a></p>
</body>
</html>
