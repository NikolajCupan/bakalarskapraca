@extends('layout.layout')

@section('content')

    <link rel="stylesheet" href="{{asset('css/adminStyles.css')}}">

    <x-navbar.navbarAdmin homePath="/admin/product"/>

    <div class="container">
        <div class="mt-4 row">
            <div class="mb-5 col-md-12 col-lg-3">
                <x-menu.categoryMenuAdminProduct :activeCategory='"warehouseInactive"'/>
            </div>

            <div class="col-md-12 col-lg-9">

                <h3 class="title">Neaktivne produkty na sklade</h3>
                <p>Nasledujuca tabulka zobazuje neaktivne produkty na sklade. Za neaktivny produkt sa povazuje taky, ktory sa nepredava
                    a zaroven pocet skladovanych kusov je 0. Detail produktu zobrazuje historiu predavania produktu. Produktu mozno manualne
                    menit kvantitu a ak nebol nikdy predavany mozno ho zmazat.</p>

                <x-table.warehouseProductsTable :warehouseProducts="$warehouseInactiveProducts"/>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <x-footer.footer/>
@endsection
