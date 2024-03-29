<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Storj - Storage Node</title>
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet">
        <link href="{{ url('css/lib/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/style.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        @stack('styles')
    </head>
    <body class="@yield('class')">
        @yield('content')
        <script src="{{ url('js/jquery.js') }}"></script>
        <script src="{{ url('js/popper.min.js') }}"></script>
        <script src="{{ url('js/bootstrap.min.js') }}"></script>
        
        
<!--        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        -->
        <script src="{{ url('js/vue.js') }}"></script>
        <script src="{{ url('js/axios.min.js') }}"></script>
        <!-- The core Firebase JS SDK is always required and must be listed first -->
<!--        <script src="{{ url('js/firebase-app.js') }}"></script>-->

        <!-- TODO: Add SDKs for Firebase products that you want to use
             https://firebase.google.com/docs/web/setup#available-libraries -->
<!--        <script src="{{ url('js/firebase-analytics.js') }}"></script>-->

<!--        <script>
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        var firebaseConfig = {
            apiKey: "AIzaSyAMsefmecw1Yg1JV9sgFsSbLuuomVDBGXc",
            authDomain: "storj-utropic-services.firebaseapp.com",
            databaseURL: "https://storj-utropic-services.firebaseio.com",
            projectId: "storj-utropic-services",
            storageBucket: "storj-utropic-services.appspot.com",
            messagingSenderId: "811814508714",
            appId: "1:811814508714:web:7cccf09e8a7a5f44c998d3",
            measurementId: "G-Q35B3MHX24"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();
        </script>-->
        @stack('scripts')
    </body>
</html>
