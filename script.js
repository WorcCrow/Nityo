$(document).ready(function () {

    loadProducts();


    $('#productForm').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'api.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response.message);
                loadProducts();
                $('#productForm')[0].reset();
            }
        });
    });


    $(document).on('click', '.deleteBtn', function () {
        var productId = $(this).data('id');

        $.ajax({
            url: 'api.php',
            type: 'POST',
            data: { action: 'delete', id: productId },
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                loadProducts();
            }
        });
    });

    $(document).on('click', '#openUpdateModalBtn', function () {

        $('#updateName').val('');
        $('#updateUnit').val('');
        $('#updatePrice').val('');
        $('#updateExpiryDate').val('');
        $('#updateInventory').val('');
        $('#updateImage').val('');


        var productId = 123;
        var productName = 'Coke';
        var productUnit = 'bottle';
        var productPrice = 60.00;
        var productExpiryDate = '2022-01-16';
        var productInventory = 20;


        $('#updateName').val(productName);
        $('#updateUnit').val(productUnit);
        $('#updatePrice').val(productPrice);
        $('#updateExpiryDate').val(productExpiryDate);
        $('#updateInventory').val(productInventory);

        $('#updateProductModal').modal('show');
    });

    $(document).on('click', '.updateBtn', function () {
        var productId = $(this).closest('tr').data('id');
        var name = $(this).closest('tr').find('.productName').text();
        var unit = $(this).closest('tr').find('.productUnit').text();
        var price = $(this).closest('tr').find('.productPrice').text();
        var expiryDate = $(this).closest('tr').find('.productExpiryDate').text();
        var inventory = $(this).closest('tr').find('.productInventory').text();

        $('#productId').val(productId);
        $('#updateName').val(name);
        $('#updateUnit').val(unit);
        $('#updatePrice').val(price);
        $('#updateExpiryDate').val(expiryDate);
        $('#updateInventory').val(inventory);

        $('#updateProductModal').modal('show');
    });

    $(document).on('click', '.updateBtnModal', function () {
        var productId = $('#productId').val();
        var name = $('#updateName').val();
        var unit = $('#updateUnit').val();
        var price = $('#updatePrice').val();
        var expiryDate = $('#updateExpiryDate').val();
        var inventory = $('#updateInventory').val();

        $.ajax({
            url: 'api.php',
            method: 'POST',
            data: {
                productId: productId,
                name: name,
                unit: unit,
                price: price,
                expiryDate: expiryDate,
                inventory: inventory,
                action: "update"
            },
            success: function (response) {
                if (response.success) {
                    var $row = $('tr[data-product-id="' + productId + '"]');
                    $row.find('.productName').text(name);
                    $row.find('.productUnit').text(unit);
                    $row.find('.productPrice').text(price);
                    $row.find('.productExpiryDate').text(expiryDate);
                    $row.find('.productInventory').text(inventory);

                    $('#updateProductModal').modal('hide');
                } else {
                }
            },
            error: function () {
            }
        });
    });


    function loadProducts() {
        $.ajax({
            url: 'api.php',
            type: 'POST',
            data: { action: 'read' },
            dataType: 'json',
            success: function (response) {
                var productList = '';
                $.each(response.products, function (key, product) {
                    productList += '<tr data-id="' + product.id + '">';
                    productList += '<td class="productName">' + product.name + '</td>';
                    productList += '<td class="productUnit">' + product.unit + '</td>';
                    productList += '<td class="productPrice">' + product.price + '</td>';
                    productList += '<td class="productExpiryDate">' + product.expiry_date + '</td>';
                    productList += '<td class="productInventory">' + product.inventory + '</td>';
                    productList += '<td>' + (product.inventory * product.price).toFixed(2) + '</td>';
                    productList += '<td><img src="images/' + product.image + '" width="50" height="50"></td>';
                    productList += '<td><button class="btn btn-danger deleteBtn" data-id="' + product.id + '">Delete</button></td>';
                    productList += '</tr>';
                });
                $('#productList').html(productList);
            }
        });
    }
});
