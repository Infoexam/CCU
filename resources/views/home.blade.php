@extends('layouts.master')

@section('header')
    <nav class="indigo darken-3">
        <div class="nav-wrapper container indigo darken-3">
            <!-- Logo -->
            <a v-link="{name: 'home'}" class="brand-logo">Logo Icon</a>

            <!-- Mobile Menu Icon -->
            <a v-link="{name: 'home'}" data-activates="nav-mobile-menu-icon" class="button-collapse"><i class="material-icons" @click.prevent>menu</i></a>

            <!-- Desktop Menu -->
            <ul class="right hide-on-med-and-down">
                @include('_nav', ['device' => 'desktop'])
            </ul>

            <!-- Mobile Menu -->
            <ul class="side-nav" id="nav-mobile-menu-icon">
                @include('_nav', ['device' => 'mobile'])
            </ul>
        </div>
    </nav>
@endsection

@section('scripts')
    <script src="{{ asset('js/student.js') }}" defer></script>
@endsection
