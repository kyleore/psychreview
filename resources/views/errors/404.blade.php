@extends('errors.layout')

@section('title', 'Page not found')
@section('code', 'Error 404')
@section('heading', 'Page not found')
@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-6.187a23.83 23.83 0 0 0-1.122-4.75M9 6.75V4.5a3 3 0 1 1 6 0v2.25"/></svg>
@endsection
@section('message', "The page you are looking for doesn't exist or has moved.")
