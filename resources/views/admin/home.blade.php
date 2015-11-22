@extends('layouts.master')

@section('header')
    <nav>
        <div class="nav-wrapper blue accent-2">
            <!-- Logo -->
            <a v-link="{name: 'home'}" class="brand-logo">Logo Icon</a>

            <!-- Mobile Menu Icon -->
            <a href="#!/" data-activates="nav-mobile-menu-icon" class="button-collapse"><i class="material-icons">menu</i></a>

            <!-- Desktop Menu -->
            <ul class="right hide-on-med-and-down">
                @include('admin._navigation')
            </ul>

            <!-- Mobile Menu -->
            <ul class="side-nav" id="nav-mobile-menu-icon">
                @include('admin._navigation')
            </ul>
        </div>
    </nav>

    <ul id="nav-dropdown-account" class="dropdown-content">
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.info') }}</a></li>
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.sync') }}</a></li>
    </ul>

    <ul id="nav-dropdown-testing" class="dropdown-content">
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.testing.list') }}</a></li>
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.testing.grade') }}</a></li>
    </ul>

    <ul id="nav-dropdown-exam" class="dropdown-content">
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.exam.set') }}</a></li>
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.exam.paper') }}</a></li>
    </ul>

    <ul id="nav-dropdown-website" class="dropdown-content">
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.announcement') }}</a></li>
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.maintenance') }}</a></li>
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.ip') }}</a></li>
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.faq') }}</a></li>
        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.log') }}</a></li>
    </ul>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin.js') }}" defer></script>
@endsection
