@extends('layout.layout')

@section('content')

    <link rel="stylesheet" href="{{asset('css/adminStyles.css')}}">

    <x-navbar.navbarAdmin homePath="/admin/purchase"/>

    <div class="container">
        <div class="mt-4 row">
            <div class="mb-5 col-md-12 col-lg-3">
                <x-menu.categoryMenuAdminPurchase :activeCategory="$activeCategory"/>
            </div>
            <div class="col-md-12 col-lg-9">
                <x-table.purchasesTable :purchases="$purchases"/>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <x-footer.footer/>
@endsection