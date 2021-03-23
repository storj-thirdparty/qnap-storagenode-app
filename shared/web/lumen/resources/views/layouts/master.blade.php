<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Storj - Storage Node</title>
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet">
        <link href="{{ url('css/lib/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        @stack('styles')
    </head>
    <body>
        @yield('content')
        <script src="{{ url('js/vue.js') }}"></script>
	<script src="{{ url('js/axios.min.js') }}"></script>
        @stack('scripts')
    </body>
</html>