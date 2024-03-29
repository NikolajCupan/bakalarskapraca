@aware(['product'])

<div class="row p-2 mb-2 bg-white border rounded">
    <div class="col-md-3 mt-1">
        @if (!is_null($product->getImagePathIfExists()))
            <img class="img-fluid img-responsive rounded product-image" src="{{asset('/storage/images/products/' . $product->getImagePathIfExists())}}" alt="">
        @else
            <img class="img-fluid img-responsive rounded product-image" src="{{asset('/images/imageMissing.jpg')}}" alt="">
        @endif
    </div>

    <div class="col-md-6 mt-1">
        <h5>{{$product->getWarehouseProduct()->product}}</h5>
        <div class="d-flex flex-row">
            <div class="d-flex ratings mr-2">
                <x-shop.elements.productStars :stars="$product->getStarsCount()"/>
            </div>

            <x-shop.elements.reviewsCount :product="$product"/>
        </div>

        <p class="cropText3Lines pt-3 text-justify para mb-0">{{$product->description}}</p>
    </div>
    <div class="align-items-center align-content-center col-md-3 border-left mt-1">
        <div class="d-flex flex-row align-items-center">
            <h4 class="mr-1">{{$product->getNewestPrice()->price}} &euro;</h4>
        </div>

        @if ($product->isAvailable())
            <h6 class="text-success">Na sklade</h6>
        @else
            <h6 class="text-danger">Vypredane</h6>
        @endif

        <div class="d-flex flex-column mt-4">
            <a type="button" class="btn btn-primary btn-sm" href="/shop/product/{{$product->id_product}}">Detail</a>
            @if ($product->isAvailable())
            <form method="POST" action="/user/addToBasket">
                @csrf
                <input type="hidden" name="productId" id="productId" value="{{$product->id_product}}">
                <input type="hidden" name="quantityValue" id="quantityValue" value="1">
                <button style="width: 100%" class="btn btn-outline-primary btn-sm mt-2" type="submit">Do kosika</button>
            </form>
            @else
            <button style="width: 100%" class="btn btn-outline-primary btn-sm mt-2" disabled>Do kosika</button>
            @endif
        </div>
    </div>
</div>
