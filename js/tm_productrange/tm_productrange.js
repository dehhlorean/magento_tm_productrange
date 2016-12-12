function populateProductrange(prods) {
    (function($) {
        $(".productrange-products").html("");

        for (var i = 0; i < prods.length; i++) {
            var prodName = prods[i].name;
            var prodPrice = prods[i].price;
            var prodUrl = prods[i].url;
            var prodThumbnail = "/media/catalog/product/" + prods[i].thumbnail;
            var prodSku = prods[i].sku;
            var prodQty = Math.round(prods[i].qty);

            $('.productrange-products').append(
                $('<div class="productrange-product">').append(
                    $('<img>').attr('src', prodThumbnail).attr('width', '100'),
                    prodPrice,
                    $('<span class="sku">').append('SKU: ' + prodSku),
                    $('<span class="qty">').append('QTY: ' + prodQty),
                    $('<a>').attr('href', prodUrl).attr('target', '_blank').append(
                        $('<span>').append(prodName)
            )));
        }
    })(jQuery);
}

(function($) {
    $(document).ready(function() {
        $('#productrange-form').submit(function() {
            var url = '/productrange/index/getproducts';
            var loVal = Number($('#productrange-form .lo').val());
            var hiVal = Number($('#productrange-form .hi').val());
            var sortByVal = $('#productrange-form .sortBy').val();

            if ((loVal == null) || (hiVal == null)) {
              alert('You must enter values for High range and Low range');
              return false;
            }
            if ((hiVal < 0) || (loVal < 0)) {
              alert('High range and Low range cannot be negative values');
              return false;
            }
            if (hiVal > (5 * loVal)) {
                alert('High range cannot be more than 5 times greater than Low range');
                return false;
            }
            if (hiVal <= loVal) {
                alert('High range must be greater than Low range');
                return false;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    hi: hiVal,
                    lo: loVal,
                    sortBy: sortByVal
                },
                success: function(data) {
                    if (data !== '') {
                        var productrangeResults = $.parseJSON(data);
                        populateProductrange(productrangeResults);
                    } else {
                      alert('Invalid form input');
                    }
                }
            });
            return false;
        });
    });
})(jQuery);
