<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <!-- Loading CSS Sources -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body id="infoexam">
        <header class="container">
            @yield('header')
        </header>

        <main class="container">
            <!-- Begin of Router View -->
            <router-view></router-view>
            <!-- End of Router View -->
        </main>

        <footer class="container page-footer blue accent-2">
            <div class="footer-copyright">
                <div class="container">
                    <span>{{ trans('navigation.copyright') }} © 2015</span>
                    <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
                </div>
            </div>
        </footer>

        <!-- Loading JavaScript Sources -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.8/vue.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.17/vue-resource.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/0.7.6/vue-router.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/1.11.1/i18next.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.0.4/js.cookie.min.js" defer></script>
        <script src="{{ asset('js/arrive.min.js') }}" defer></script>
        @yield('scripts')
    </body>
</html>
