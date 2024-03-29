// Delete basket product modal
$(document).on("click", ".openDeleteBasketProductModal", function () {
    let productName = $(this).data('product-name');
    $("#deleteBasketProductModalLabel").text(productName);

    let basketId = $(this).data('id-basket');
    $("#destroyBasketId").val(basketId);

    let productId = $(this).data('id-product');
    $("#destroyProductId").val(productId);
});

// Edit basket product modal
// Sending data to modal
$(document).on("click", ".openEditBasketProductModal", function () {
    let productName = $(this).data('product-name');
    $("#editBasketProductModalLabel").text(productName);

    let basketQuantity = $(this).data('basket-quantity');
    $("#quantityValue").val(basketQuantity);

    let warehouseQuantity = $(this).data('warehouse-quantity');
    $("#warehouseQuantity").attr('placeholder', warehouseQuantity);

    let basketId = $(this).data('id-basket');
    $("#basketId").val(basketId);

    let productId = $(this).data('id-product');
    $("#productId").val(productId);
});

// AJAX for editing product quantity in basket
$(document).ready(function() {
    $('.editBasketProductClass').click(function () {
        let basketId = $('#basketId').val();
        let productId = $('#productId').val();
        let newBasketQuantity = $('#quantityValue').val();

        $.ajax({
            url: "/user/editBasketProductQuantity",
            method: 'POST',
            data: {
                productId: productId,
                basketId: basketId,
                newBasketQuantity: newBasketQuantity
            },
            // This header must be added otherwise AJAX fails
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Change total price and quantity
                let totalPrice = $('#totalPriceBasket' + basketId + 'product' + productId);
                totalPrice.html(response.newTotalPrice + ' &euro;');

                let quantity = $('#quantityBasket' + basketId + 'product' + productId);
                quantity.text(newBasketQuantity);

                // Change text color of warehouse quantity
                let quantityWarehouse = $('#warehouseQuantityBasket' + basketId + 'product' + productId);
                quantityWarehouse.toggleClass('text-success', response.enoughInStock);
                quantityWarehouse.toggleClass('text-danger', !response.enoughInStock);

                // To have current data in modal, action button must be updated as well
                let actionButton = $('#actionButtonBasket' + basketId + 'product' + productId);
                actionButton.data('basket-quantity', newBasketQuantity);

                updateTotalPurchasePrice();
                updateContinueButton();

                // Hide modal
                $('#editBasketProductModal').modal('hide');
            },
            error: function() {
                alert('AJAX zlyhal');
            }
        });
    });
});

function updateTotalPurchasePrice()
{
    $.ajax({
        type: "GET",
        url: "/user/getTotalPurchasePrice",
        success: function(response) {
            $('#totalPurchasePrice').html(response.totalPurchasePrice + ' &euro;');
            $('#totalPurchasePriceWithFee').html(response.totalPurchasePrice + ' &euro;');
        },
        error: function() {
            alert('AJAX zlyhal');
        }
    });
}

// If purchase cannot be made, make button disabled
function updateContinueButton()
{
    $.ajax({
        type: "GET",
        url: "/user/isBasketOrderable",
        success: function(response) {
            let continueButton = $('#continueButton');
            continueButton.toggleClass('disabled', !response.isOrderable);
        },
        error: function() {
            alert('AJAX zlyhal');
        }
    });
}
