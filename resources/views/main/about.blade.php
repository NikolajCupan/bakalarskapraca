@extends('layout.layout')

@section('content')

    <x-navbar.navbar :imagePath="$imagePath" :user="$user" :basket="$basket"/>

    ABOUT US

@endsection

@section('footer')
    <x-footer.footer/>
@endsection
