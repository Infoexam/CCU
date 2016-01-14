@extends('layouts.master')

@section('header')
    <!-- Navigation -->
    @include('admin.nav.mobile')
@endsection

@section('main')
    @include('layouts.breadcrumbs')

    @parent
@endsection

@section('scripts')
    <script src="{{ _asset('js/admin.js') }}" defer></script>
@endsection
