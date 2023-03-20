@extends('layout.layout')

@section('content')

    <link rel="stylesheet" href="{{asset('css/categoryProducts.css')}}">

    <x-other.flashMessage/>
    <x-navbar.navbar :imagePath="$imagePath" :user="$user" :basket="$basket"/>

    <div class="container">
        <div class="mt-4 row">
            <div class="mb-5 col-md-12 col-lg-3">
                <x-menu.categoryMenu :categories="$categories"/>
            </div>
            <div class="col-md-12 col-lg-9">
                <div class="container mb-5">
                    <div class="d-flex justify-content-center row">
                        <div class="col-md-10">
                            <h2 class="title mb-3">Najpredavanejsie produkty</h2>

                            @foreach ($mostSellingProducts as $product)
                                <x-shop.productCard :product="$product"/>
                            @endforeach

                            <h2 class="title mt-5 mb-3">Najnovsie produkty</h2>

                            @foreach ($newestProducts as $product)
                                <x-shop.productCard :product="$product"/>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <x-footer.footer/>
@endsection
