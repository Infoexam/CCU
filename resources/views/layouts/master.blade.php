<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <meta property="og:title" content="資訊能力測驗系統">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://infoexam.ccu.edu.tw">
        <meta property="og:locale" content="zh_TW">
        <title>資訊能力測驗系統</title>
        <!-- Loading CSS Sources -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.4/css/materialize.min.css">
        <link rel="stylesheet" href="{{ _asset('css/app.css') }}">
    </head>
    <body id="infoexam">
        <header>
            @yield('header')
        </header>

        <main class="container">
            @section('main')
                <!-- Begin of Router View -->
                <router-view></router-view>
                <!-- End of Router View -->
            @show
        </main>

        <footer class="indigo darken-1">
            <div class="container">
                <span><a href="http://www.ccu.edu.tw/" target="_blank">{{ trans('navigation.copyright') }}</a> © 2015</span>
                <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
            </div>
        </footer>

        <!-- Loading JavaScript Sources -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.4/js/materialize.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.12/vue.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.5.1/vue-resource.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/0.7.7/vue-router.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/1.11.1/i18next.min.js" defer></script>
        <script src="https://cdn-infoexam.ccu.edu.tw/ajax/libs/arrive/2.3.0/arrive.min.js" defer></script>
        @yield('scripts')
    </body>
</html>
