@extends('layouts.master')

@section('header')
    <!-- Navigation -->
    @include('admin.nav.nav')
@endsection

@section('main')
    <div class="admin-main">
        <div class="container">
            @parent
        </div>
    </div>
@endsection

@section('footer')
    @include('admin.nav.footer')
@endsection

@section('scripts')
    <script src="{{ _asset('js/admin.js') }}" defer></script>
@endsection
