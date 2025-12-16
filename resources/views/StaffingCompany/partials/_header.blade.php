<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst(request()->path()) }} | {{ config('app.name', 'DCS') }}</title>
    {{-- <title>@yield('title', '') | {{ config('app.name', 'DCS') }}</title> --}}
    @include('StaffingCompany.partials._styles')
    <link rel="icon" type="image/x-icon" href="{{asset('image/Favicon1.svg')}}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    @stack('head')
</head>
<script>
      var APP_URL = {!! json_encode(url('/').'/') !!}
</script>
<script>
    window.auth = {!! auth()->user() !!}
</script>
