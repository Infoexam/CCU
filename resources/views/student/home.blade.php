@extends('layouts.master')

@section('header')
    @include('student.nav')
@endsection

@section('main')
    <div class="student-main">
        <div class="container">
            @parent
        </div>
    </div>
@endsection

@section('footer')
    @include('student.footer')
@endsection

@section('scripts')
    <script src="{{ _asset('js/student.js') }}" defer></script>
@endsection
