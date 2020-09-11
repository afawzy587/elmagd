$(document).ready(function () {

    $('#supplierPayments').formValidation({
        excluded: [':disabled'],
        fields: {
            amount: {
                validators: {
                    notEmpty: {
                        message: 'أدخل المبلغ'
                    }
                }
            },
            remaninAmountDate: {
                validators: {
                    notEmpty: {
                        message: 'أدخل تاريخ استحقاق المتبقي'
                    }
                }
            },
            recipient: {
                validators: {
                    notEmpty: {
                        message: 'أدخل اسم المستلم'
                    }
                }
            },
            receptionDate: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ الاستلام'
                    }
                }
            }

        }
    }).on('success.form.bv', function (e) {


    })


    $('select[name="paymentType"]').on('change', function (e, data) {
        key = $(this)[0].value;
        switch (key) {
            case '1':
                var checkNumInput = $(this).parents('.customerItem').find('input').eq(7).prop("disabled", true);
                var creditInput = $(this).parents('.customerItem').find('select').eq(1).prop("disabled", true);
                var accountInput = $(this).parents('.customerItem').find('select').eq(2).prop("disabled", true);
                var partInput = $(this).parents('.customerItem').find('select').eq(3).prop("disabled", true);
                var dateInput = $(this).parents('.customerItem').find('input').eq(9).prop("disabled", true);


                // remove check validation from form
                $('#supplierPayments').formValidation('removeField', checkNumInput);
                $('#supplierPayments').formValidation('removeField', creditInput);
                $('#supplierPayments').formValidation('removeField', accountInput);
                $('#supplierPayments').formValidation('removeField', partInput);
                $('#supplierPayments').formValidation('removeField', dateInput);
                checkNumInput.siblings('.help-block').hide();
                creditInput.parent().siblings('.help-block').hide();
                accountInput.parent().siblings('.help-block').hide();
                partInput.parent().siblings('.help-block').hide();
                dateInput.siblings('.help-block').hide();
                break;

            case '2':
                var checkNum = $(this).parents('.customerItem').find('input').eq(7).prop("disabled", false);
                var credit = $(this).parents('.customerItem').find('select').eq(1).prop("disabled", false);
                var account = $(this).parents('.customerItem').find('select').eq(2).prop("disabled", false);
                var part = $(this).parents('.customerItem').find('select').eq(3).prop("disabled", false);
                var date = $(this).parents('.customerItem').find('input').eq(9).prop("disabled", false);

                // add check validation to form 
                $('#supplierPayments').formValidation('addField', checkNum, {
                    validators: {
                        notEmpty: {
                            message: 'أدخل رقم الشيك'
                        },
                        digits: {
                            message: 'يجب أن يحتوي علي أرقام صحيحة فقط'
                        }
                    }
                })
                    .formValidation('addField', credit, {
                        validators: {
                            notEmpty: {
                                message: 'اختر الرصيد'
                            }
                        }
                    })
                    .formValidation('addField', account, {
                        validators: {
                            notEmpty: {
                                message: 'اختر الحساب'
                            }
                        }
                    })
                    .formValidation('addField', part, {
                        validators: {
                            notEmpty: {
                                message: 'اختر الوعاء'
                            }
                        }
                    })
                    .formValidation('addField', date, {
                        validators: {
                            notEmpty: {
                                message: 'اختر تاريخ اسيحقاق الشيك'
                            }
                        }
                    })

                break;

            default:
                break;
        }
    })

    $('input.paidAmount').keyup(function () {
        var totalPaid = 0;
        console.log($(this).parents('.customerItem').find('input').eq(3).val());
        var toPaid_amount = $(this).parents('.customerItem').find('input').eq(3).val();
        $(this).parents('.customerItem').find('input').eq(10).val((toPaid_amount - $(this).val()).toFixed(2));
        calcRemainAmount();
        $.each($('input.paidAmount'), function () {
            if ($(this).val() != '') {
                totalPaid += parseInt($(this).val());
                $('input#TotalPaid').val(totalPaid);
            }
        })
    })



        function calcRemainAmount(){
        var totalremain = 0;
        $.each($('input.remainAmountInput'), function () {
            if ($(this).val() != '') {
                totalremain += parseInt($(this).val());
                $('input#TotalRemainAmount').val(totalremain);
            }
        })
        calcCustomerAccount(totalremain);
    }

    function calcCustomerAccount(remainAmount) {
        var finalState = remainAmount + parseInt($('input#currentSupplierState').val());
        $('input#FinalCurrentSupplierState').val(finalState)
    }
})