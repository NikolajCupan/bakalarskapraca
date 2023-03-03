@extends('layout.layout')

@section('content')

    <script type="text/javascript" src="{{asset('js/editBasketProduct.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/numberSelector.js')}}"></script>

    <style>
        body {
            /* fallback for old browsers */
            background: #30cfd0;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to bottom right, rgba(48,207,208,0.5), rgba(51,8,103,0.5));

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to bottom right, rgba(48,207,208,0.5), rgba(51,8,103,0.5));
        }

        .editBasketProduct:hover {
            cursor: pointer;
        }
    </style>

    <div class="px-4 px-lg-0">
        <div class="container text-white py-5 text-center">
            <h1 class="display-4">Vas Kosik</h1>
        </div>

        <div class="pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 p-5 bg-white rounded shadow-sm mb-5">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="p-2 px-3 text-uppercase">Produkt</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">Cena</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">Kvantita</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">Spolu</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="py-2 text-uppercase">Akcia</div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($basket->getBasketProducts() as $basketProduct)
                                        <x-shop.basket.basketProduct :basketProduct="$basketProduct"/>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row py-5 p-4 bg-white rounded shadow-sm">
                    <div class="col-lg-6">
                        <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Informacie</div>
                        <div class="p-4">
                            <p class="font-italic mb-4">
                                Nullam sed ipsum congue, iaculis odio sed, tempus turpis. Duis erat augue, tristique vel nulla ac, cursus aliquet erat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque ut rutrum turpis, at vulputate massa. Nulla laoreet lobortis enim id ultricies. Pellentesque feugiat congue turpis non laoreet. Nunc turpis lacus, varius id bibendum vulputate, condimentum ut purus. Cras ut finibus augue. Donec scelerisque odio eget sollicitudin venenatis. Donec tincidunt tellus lobortis justo finibus, sed ullamcorper ipsum mattis. Proin finibus lectus et mauris facilisis, vitae lobortis odio pharetra.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Suhrn objednavky</div>
                        <div class="p-4">
                            <p class="font-italic mb-4">Vivamus posuere vel nunc lobortis tempus. Suspendisse dapibus lorem vel dui blandit, non iaculis lectus pretium.</p>
                            <ul class="list-unstyled mb-4">
                                <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Tovar </strong><strong id="totalOrderPrice">{{$basket->getTotalPrice()}} &euro;</strong></li>
                                <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Dovoz </strong><strong>{{\App\Helpers\Constants::getFormattedDeliveryFee()}} &euro;</strong></li>
                                <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Celkovo </strong>
                                    <h5 id="totalOrderPriceWithFee" class="font-weight-bold">{{$basket->getTotalPriceWithFee()}} &euro;</h5>
                                </li>
                            </ul>
                            <a href="#" class="btn btn-dark rounded-pill py-2 btn-block">Objednat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit basket product -->
    <div class="modal fade" id="editBasketProductModal" tabindex="-1" aria-labelledby="editBasketProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editBasketProductModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-1">Pocet kusov v kosiku</p>
                    <x-shop.elements.numberSelector/>

                    <p class="mt-4 mb-1">Pocet kusov na sklade</p>
                    <input id="warehouseQuantity" class="form-control" type="text" placeholder="" aria-label="Disabled input example" disabled readonly>

                    <input id="basketId" type="hidden">
                    <input id="productId" type="hidden">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrusit</button>
                    <a class="editBasketProductClass btn btn-dark" type="button">Ulozit</a>
                </div>
            </div>
        </div>
    </div>

@endsection
