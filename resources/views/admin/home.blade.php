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
                <li>
                    <a class="dropdown-button" href="#!/" @click.prevent data-beloworigin="true" data-activates="nav-dropdown-account">
                        <span>{{ trans('navigation.account./') }}</span><i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
                <li>
                    <a class="dropdown-button" href="#!/" @click.prevent data-beloworigin="true" data-activates="nav-dropdown-testing">
                        <span>{{ trans('navigation.testing./') }}</span><i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
                <li>
                    <a class="dropdown-button" href="#!/" @click.prevent data-beloworigin="true" data-activates="nav-dropdown-exam">
                        <span>{{ trans('navigation.exam./') }}</span><i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
                <li>
                    <a class="dropdown-button" href="#!/" @click.prevent data-beloworigin="true" data-activates="nav-dropdown-website">
                        <span>{{ trans('navigation.website./') }}</span><i class="material-icons right">arrow_drop_down</i>
                    </a>
                </li>
            </ul>

            <!-- Mobile Menu -->
            <ul class="side-nav" id="nav-mobile-menu-icon">
                <li><a href="badges.html">Components</a></li>
                <li><a href="collapsible.html">Javascript</a></li>
                <li><a href="mobile.html">Mobile</a></li>
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
