@extends('layouts.master')

@section('header')
    <nav>
        <div class="nav-wrapper blue accent-2">
            <!-- Logo -->
            <a v-link="{name: 'home'}" class="brand-logo">Logo Icon</a>

            <!-- Mobile Menu Icon -->
            <a v-link="{name: 'home'}" data-activates="nav-mobile-menu-icon" class="button-collapse"><i class="material-icons">menu</i></a>

            <!-- Desktop Menu -->
            <ul class="right hide-on-med-and-down">
                @include('_navigation')
            </ul>

            <!-- Mobile Menu -->
            <ul class="side-nav" id="nav-mobile-menu-icon">
                @include('_navigation')
            </ul>
        </div>
    </nav>
@endsection

@section('scripts')
    <script src="{{ asset('js/student.js') }}" defer></script>
@endsection
