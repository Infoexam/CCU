@extends('layouts.master')

@section('header')
    <!-- Navigation -->
    @include('admin.nav.mobile')
@endsection

@section('main')
    @parent
@endsection

@section('scripts')
    <script src="{{ _asset('js/admin.js') }}" defer></script>
@endsection
