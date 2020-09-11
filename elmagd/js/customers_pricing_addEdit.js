$(document).ready(function () {

    $('#customersPricingAddEditForm').formValidation({
        excluded: [':disabled'],
        fields: {
            customerName: {
                validators: {
                    notEmpty: {
                        message: 'اختر العميل '
                    }
                }
            },
            products: {
                validators: {
                    notEmpty: {
                        message: 'اختر المنتج  '
                    }
                }
            },


            // startDate: {
            //     validators: {
            //         notEmpty: {
            //             message: 'اختر تاريخ البداية '
            //         },
            //         callback: {
            //           message: 'تاريخ البداية يجب أن يكون قبل تاريخ النهاية',
            //           callback: function (startDate, validator, $field) {
            //               return checkDateRange($('input[name="startDate"]').val(), $('input[name="endDate"]').val());
            //           }
            //       }
            //     }
            // },
            // endDate: {
            //     validators: {
            //         notEmpty: {
            //             message: 'اختر تاريخ النهاية '
            //         },
            //         callback: {
            //           message: 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            //           callback: function (startDate, validator, $field) {
            //               return checkDateRange($('input[name="startDate"]').val(), $('input[name="endDate"]').val());
            //           }
            //       }
            //     }
            // }
        }
    }).on('success.form.bv', function (e) {


    })
        .on('added.field.fv', function (e, data) {
            if (data.field.includes('startDate')) {
                var inputnameIndex = Object.values(data.element)[0].name.slice(9);
                var inputnameObj = Object.values(data.element)[0];
                data.element.on('change', function () {
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $(this));
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $('input[name="endDate' + inputnameIndex + '"'));
                })

            } else if (data.field.includes('endDate')) {
                data.element.on('change', function () {
                    var inputnameIndex2 = Object.values(data.element)[0].name.slice(7);
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $(this));
                    $('#customersPricingAddEditForm').formValidation('revalidateField', $('input[name="startDate' + inputnameIndex2 + '"]'));
                })
            }
        });

    // dates validation
    var checkDateRange = function (startDate, endDate) {
        var isValid = (startDate != "" && endDate != "") ? startDate < endDate : true;
        return isValid;
    }


    $('select[name="products"]').on('change', function (evt) {
        var qualitiesNum = $(this).val();
        $('#productsToEdit').empty();
        var qualitiesCounter = 0;
        for (let i = 0; i < qualitiesNum; i++) {
            qualitiesCounter++;
            // clone product row and change row id and inputs names
            var productRowITem = $('#productToEditRow').clone().prop('id', 'productToEditRow' + qualitiesCounter).removeClass('hideRow');
            var startDateInput = productRowITem.find('input').eq(0).attr('name', 'startDate' + qualitiesCounter);
            var endDateInput = productRowITem.find('input').eq(1).attr('name', 'endDate' + qualitiesCounter);
            var currentSellingPriceInput = productRowITem.find('input').eq(2).attr('name', 'currentSellingPrice' + qualitiesCounter);
            var currentBuyingPriceInput = productRowITem.find('input').eq(3).attr('name', 'currentBuyingPrice' + qualitiesCounter);
            var sellingPriceInput = productRowITem.find('input').eq(4).attr('name', 'sellingPrice' + qualitiesCounter);
            var buyingPriceInput = productRowITem.find('input').eq(5).attr('name', 'buyingPrice' + qualitiesCounter);
            var currentBuyingPercentageInput = productRowITem.find('input').eq(6).attr('name', 'currentBuyingPercentage' + qualitiesCounter);
            var BuyingPercentageInput = productRowITem.find('input').eq(7).attr('name', 'BuyingPercentage' + qualitiesCounter);
            var priceInput = productRowITem.find('input').eq(8).attr('name', 'price' + qualitiesCounter);
            var percentageInput = productRowITem.find('input').eq(9).attr('name', 'percentage' + qualitiesCounter);
            var activatePriceInput = productRowITem.find('input').eq(10).attr({'name': 'activatePrice' + qualitiesCounter, 'id' : 'activatePrice1' + qualitiesCounter});
            productRowITem.find('label').eq(10).attr('for' , 'activatePrice1' + qualitiesCounter);
            productRowITem.find('input').eq(11).attr({'name': 'activatePrice' + qualitiesCounter, 'id' : 'activatePrice2' + qualitiesCounter});
            productRowITem.find('label').eq(11).attr('for' , 'activatePrice2' + qualitiesCounter);
            var qualityPercentageInput = productRowITem.find('input').eq(12).attr('name', 'qualityPercentage' + qualitiesCounter);
            var qualityDetailInput = productRowITem.find('input').eq(13).attr({'name': 'qualityDetail' + qualitiesCounter, 'id' : 'qualityDetail1' + qualitiesCounter});
            productRowITem.find('label').eq(13).attr('for' , 'qualityDetail1' + qualitiesCounter);
            productRowITem.find('input').eq(14).attr({'name': 'qualityDetail' + qualitiesCounter, 'id' : 'qualityDetail2' + qualitiesCounter});
            productRowITem.find('label').eq(14).attr('for' , 'qualityDetail2' + qualitiesCounter);
            productRowITem.find('input').eq(15).attr({'name': 'qualityDetail' + qualitiesCounter, 'id' : 'qualityDetail3' + qualitiesCounter});
            productRowITem.find('label').eq(15).attr('for' , 'qualityDetail3' + qualitiesCounter);
            var customerBounsInput = productRowITem.find('input').eq(16).attr({'name': 'customerBouns' + qualitiesCounter, 'id' : 'customerBouns1' + qualitiesCounter});
            productRowITem.find('label').eq(16).attr('for' , 'customerBouns1' + qualitiesCounter);
            productRowITem.find('input').eq(17).attr({'name': 'customerBouns' + qualitiesCounter, 'id' : 'customerBouns2' + qualitiesCounter});
            productRowITem.find('label').eq(17).attr('for' , 'customerBouns2' + qualitiesCounter);
            var customerBounspercentageInput = productRowITem.find('input').eq(18).attr('name', 'customerBounspercentage' + qualitiesCounter);
            var customerBounsKgmInput = productRowITem.find('input').eq(19).attr('name', 'customerBounsKgm' + qualitiesCounter);
            var supplierBounsInput = productRowITem.find('input').eq(20).attr({'name': 'supplierBouns' + qualitiesCounter, 'id' : 'supplierBouns1' + qualitiesCounter});
            productRowITem.find('label').eq(20).attr('for' , 'supplierBouns1' + qualitiesCounter);
            productRowITem.find('input').eq(21).attr({'name': 'supplierBouns' + qualitiesCounter, 'id' : 'supplierBouns2' + qualitiesCounter});
            productRowITem.find('label').eq(21).attr('for' , 'supplierBouns2' + qualitiesCounter);
            var supplierBounsPercentageInput = productRowITem.find('input').eq(22).attr('name', 'supplierBounsPercentage' + qualitiesCounter);
            var supplierBounsKgmInput = productRowITem.find('input').eq(23).attr('name', 'supplierBounsKgm' + qualitiesCounter);


            // add validation to form
            $('#customersPricingAddEditForm')
                .formValidation('addField', startDateInput, {
                    validators: {
                        notEmpty: {
                            message: 'اختر تاريخ البداية '
                        }
                    }
                })
                .formValidation('addField', endDateInput, {
                    validators: {
                        callback: {
                            message: 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
                            callback: function (startDate, validator, $field) {
                                return checkDateRange($('input[name="startDate1"]').val(), $('input[name="endDate1"]').val());
                            }
                        }
                    }
                })

            // console.log(startDate);
            $('#productsToEdit').append(productRowITem);
        }
    });

    $('input[name="supplyState"]').on('change', function(){
        console.log($(this).parents('.supplyStateCol').find('input.supplyStateInput')[0]);
        var inputs = $('input[name="supplyState"]');
        $.each(inputs, function(){
            console.log('checked');
            console.log($(this).is(':checked'));
            if($(this).is(':checked')){
                var input = $(this).parents('.supplyStateCol').find('input.supplyStateInput').prop("disabled", false);
            } else {
                 $(this).parents('.supplyStateCol').find('input.supplyStateInput').prop("disabled", true);
                }
        })
        // console.log($(this));
    })
})