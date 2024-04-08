@extends('mainscreen::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('mainscreen.name') !!}</p>
@endsection
