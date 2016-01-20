@extends('layouts.master')

@section('header')
    <nav class="indigo darken-1">
        <div class="nav-wrapper container indigo darken-1">
            <!-- Logo -->
            <a v-link="{name: 'home'}" class="brand-logo">Logo Icon</a>

            <!-- Mobile Menu Icon -->
            <a v-link="{name: 'home'}" data-activates="nav-mobile-menu-icon" class="button-collapse"><i class="material-icons" @click.prevent>menu</i></a>

            <ul class="side-nav" id="nav-mobile-menu-icon">
                <li><a v-link="{name: 'home'}">{{ trans('navigation.announcement') }}</a></li>
                <li><a v-link="{name: 'home'}">{{ trans('navigation.practice') }}</a></li>
            </ul>
        </div>
    </nav>
@endsection

@section('footer')
    @include('student.footer')
@endsection

@section('scripts')
    <script src="{{ _asset('js/student.js') }}" defer></script>
@endsection
