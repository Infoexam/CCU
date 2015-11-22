@extends('layouts.master')

@section('header')
    <nav>
        <div class="nav-wrapper blue accent-2">
            <!-- Logo -->
            <a v-link="{name: 'home'}" class="brand-logo">Logo Icon</a>

            <!-- Mobile Menu Icon -->
            <a v-link="{name: 'home'}" data-activates="nav-mobile-menu-icon" class="button-collapse"><i class="material-icons" @click.prevent>menu</i></a>

            <!-- Desktop Menu -->
            <ul class="right hide-on-med-and-down">
                @include('admin._nav', ['device' => 'desktop'])
            </ul>

            <!-- Mobile Menu -->
            <ul class="side-nav" id="nav-mobile-menu-icon">
                @include('admin._nav', ['device' => 'mobile'])
            </ul>
        </div>
    </nav>

    @include('admin._nav_dropdown', ['device' => 'desktop'])
    @include('admin._nav_dropdown', ['device' => 'mobile'])
@endsection

@section('scripts')
    <script src="{{ asset('js/admin.js') }}" defer></script>
@endsection
