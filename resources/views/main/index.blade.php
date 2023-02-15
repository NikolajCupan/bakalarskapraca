@extends('layout.layout')

@section('content')

    <x-flashMessage/>
    <x-navbar :imagePath="$imagePath" :user="$user"/>

    <div class="container">
        <div class="mt-4 row">
            <div class="mb-5 col-md-12 col-lg-3">
                <x-categoryMenu :categories="$categories"/>
            </div>
            <div class="col-md-12 col-lg-9">
                Nothing
            </div>
        </div>
    </div>

@endsection
