$(document).ready(function () {

    $('#newTransactionForm').formValidation({
        excluded: [':disabled'],
        fields: {
            transactionDate: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ العملية '
                    }
                }
            },
            supplier: {
                validators: {
                    notEmpty: {
                        message: 'اختر المورد  '
                    }
                }
            },
            customer: {
                validators: {
                    notEmpty: {
                        message: 'اختر العميل  '
                    }
                }
            },
            product: {
                validators: {
                    notEmpty: {
                        message: 'اختر المنتج  '
                    }
                }
            },
            cardNum: {
                validators: {
                    notEmpty: {
                        message: ' أدخل رقم الكارت'
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            quantityKgm: {
                validators: {
                    notEmpty: {
                        message: 'أدخل الكمية'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            generalDiscount: {
                validators: {
                    notEmpty: {
                        message: 'أدخل الخصم العام'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            cardImg1: {
                validators: {
                    notEmpty: {
                        message: 'أرفع صورة وجه الكارت'
                    }
                }
            },
            cardImg2: {
                validators: {
                    notEmpty: {
                        message: 'أرفع صورة ظهر الكارت'
                    }
                }
            }

        }
    }).on('success.form.bv', function (e) {


    })
        .on('added.field.fv', function (e, data) {
            if (data.field.includes('qualityName')) {
            }
        });

    var QualitesSelectedbefore = false;
    $('.transactiondetailsInput').on('change', function () {
        var qualitiesNum;
        var qualitiesCounter = 0;
        if ($('.transactiondetailsInput').filter(function () {
            return $.trim($(this).val()).length == 0
        }).length == 0) {

            var transactionDetailsRow = $('#transactionDetailsRow').removeClass('hideRow');
            qualitiesNum = $('select[name="product"]').val(); // set here the number of qualities to repeate quality row

            // remove validation if added before and empty the container
            if (QualitesSelectedbefore == true) {
                var inputstoremovelength = $('#qualityItemsContainer').find('input').length;
                for (let i = 0; i < inputstoremovelength; i++) {
                    var inputItem = $('#qualityItemsContainer').find('input').eq(i);
                    $('#newTransactionForm').formValidation('removeField', inputItem);
                }
                $('#qualityItemsContainer').empty();
            }

            // Adding qualites and its validation
            for (let i = 0; i < qualitiesNum; i++) {
                qualitiesCounter++;
                var qualityItemRow = $('#qualityItem').clone().prop('id', 'qualityItem' + qualitiesCounter).removeClass('hideRow');
                qualityItemRow.find('label').eq(0).text('الدرجة' + qualitiesCounter);
                var qualityName_input = qualityItemRow.find('input').eq(0).attr('name', 'qualityName' + qualitiesCounter);
                var discountPercentage_input = qualityItemRow.find('input').eq(1).attr('name', 'discountPercentage' + qualitiesCounter);
                var discountAmount_input = qualityItemRow.find('input').eq(2).attr('name', 'discountAmount' + qualitiesCounter);
                var qualityPercentage_input = qualityItemRow.find('input').eq(3).attr('name', 'qualityPercentage' + qualitiesCounter);
                var qualityDiscountPercentage_input = qualityItemRow.find('input').eq(4).attr('name', 'qualityDiscountPercentage' + qualitiesCounter);
                var percentage_input = qualityItemRow.find('input').eq(5).attr('name', 'percentage' + qualitiesCounter);
                var qualityKgm_input = qualityItemRow.find('input').eq(6).attr('name', 'qualityKgm' + qualitiesCounter);
                var amount_input = qualityItemRow.find('input').eq(7).attr('name', 'amount' + qualitiesCounter);


                // Add validation to form
                $('#newTransactionForm')
                    .formValidation('addField', qualityName_input)
                    .formValidation('addField', discountPercentage_input, {
                        validators: {
                            notEmpty: {
                                message: 'أدخل نسبة الخصم'
                            },
                            regexp: {
                                regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                                message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                            }
                        }
                    })
                    .formValidation('addField', discountAmount_input, {
                        validators: {
                            notEmpty: {
                                message: 'أدخل قيمة الخصم'
                            },
                            regexp: {
                                regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                                message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                            }
                        }
                    })
                    .formValidation('addField', qualityPercentage_input, {
                        validators: {
                            notEmpty: {
                                message: 'أدخل نسبة الدرجة'
                            },
                            regexp: {
                                regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                                message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                            }
                        }
                    })
                    .formValidation('addField', qualityDiscountPercentage_input, {
                        validators: {
                            notEmpty: {
                                message: 'أدخل نسبة خصم الدرجة'
                            },
                            regexp: {
                                regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                                message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                            }
                        }
                    })
                    .formValidation('addField', percentage_input, {
                        validators: {
                            notEmpty: {
                                message: 'أدخل نسبة السماح'
                            },
                            regexp: {
                                regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                                message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                            }
                        }
                    })
                    .formValidation('addField', qualityKgm_input)
                    .formValidation('addField', amount_input);


                //  on change event
                $(qualityItemRow).keyup('input:text', function () {
                    var qualityItemRowElement = $(this).parents('.qualityItemRow');
                    calculateQualityvalues(qualityItemRowElement);
                })


                $('#qualityItemsContainer').append(qualityItemRow);
                QualitesSelectedbefore = true;
            }
        }
    })

    // calculate Quality values
    function calculateQualityvalues(nodeElement) {
        var qualityRow = $(nodeElement.prevObject[0])[0];
        var totalKgms = $('input[name="finalQuantityKgm"]').val();
        var qualityPercentage = $(qualityRow).find('input').eq(3).val();
        var qualityDiscountPercentage = $(qualityRow).find('input').eq(4).val();
        var percentage = $(qualityRow).find('input').eq(5).val();
        var qualityKgms;
        var quantityKgms;
        if (totalKgms != '' && qualityPercentage != '' && qualityDiscountPercentage != '' && percentage != '') {
            var qualityPercentageKgms = totalKgms * (qualityPercentage / 100);
            var QPKgms__discount =  qualityPercentageKgms - (qualityPercentageKgms*(qualityDiscountPercentage/100));
            qualityKgms =  (QPKgms__discount - (QPKgms__discount*(percentage/100))).toFixed(2);
            quantityKgms = (qualityKgms * (percentage / 100)).toFixed(2);
        }
        $(qualityRow).find('input').eq(6).val(qualityKgms);
        $(qualityRow).find('input').eq(7).val(quantityKgms);

    }

    $('.mainInputs').keyup(function () {
        var quantityKgm = $('input[name="quantityKgm"]').val();
        var generalDiscount = $('input[name="generalDiscount"]').val();
        $('input[name="finalQuantityKgm"]').val(quantityKgm - (quantityKgm * (generalDiscount / 100)));
    });
})