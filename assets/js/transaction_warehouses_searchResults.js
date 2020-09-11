$(document).ready(function () {
    $('.transactionTable').DataTable({
        "searching": false,
        "ordering": false,
        "lengthChange": false,
        "info": false,
        "paginate": false
    });

    $('#warehousesForm').formValidation({
        excluded: [':disabled'],
        fields: {
            cost: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ العملية '
                    },
                    digits: {
                        message: 'أرقام صحيحة فقط'
                    }
                }
            }
        }
    });


    $('input.costInput').keyup(function () {
        var itemPrice;
        var itemCost;
        var itemTotalPrice;
        var totalPrices = 0;
        var total_cost = 0;
        var inputs = $(this).parents('tbody').find('input.costInput');
        var prices = $(this).parents('tbody').find('.totalPrice');

        // Item Values
        itemPrice = parseInt($(this).parents('tr').find('.price')[0].innerHTML);
        itemCost = parseInt($(this).val());
        itemTotalPrice = itemPrice + itemCost;
        if (isNaN(itemTotalPrice)) {
            $(this).parents('tr').find('.totalPrice')[0].innerHTML = '';
        } else { $(this).parents('tr').find('.totalPrice')[0].innerHTML = itemTotalPrice }


        // Calc totalCost
        $.each(inputs, function () {
            if ($(this).val() != '') {
                total_cost += parseInt($(this).val());
            }
        })
        $(this).parents('tbody').find('td.totalCost')[0].innerHTML = total_cost;

        // Calc total Price
        $.each(prices, function () {
            totalPrices += parseInt($(this)[0].innerHTML);
        })
        $(this).parents('tbody').find('td.totalPrices')[0].innerHTML = totalPrices;

    })

    $('input.costInput').keyup();

});
