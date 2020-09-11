$(document).ready(function () {

    $('#banksDepositeForm').formValidation({
        excluded: [':disabled'],
        fields: {
            operationDate: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ العملية '
                    }
                }
            },
            amount: {
                validators: {
                    notEmpty: {
                        message: 'ادخل المبلغ  '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            customer: {
                validators: {
                    notEmpty: {
                        message: 'اختر العميل'
                    }
                }
            },
            product: {
                validators: {
                    notEmpty: {
                        message: 'اختر المنتج'
                    }
                }
            },
        }
    }).on('success.form.bv', function (e) {


    })

    $('input[name="paymenttype"]').on('change', function () {
        key = $(this).val();
        switch (key) {
            case 'check':
                var checkDate = $('#check_date').prop("disabled", false);
                var checkNum = $('#check_number').prop("disabled", false);
                var accountType = $('#account_type').prop("disabled", false);
                var bankItem = $('#bank_item').prop("disabled", false);
                var credit = $('#credit').prop("disabled", false);

                $('#banksDepositeForm')
                    .formValidation('addField', checkDate, {
                        validators: {
                            notEmpty: {
                                message: 'ادخل تاريخ استحقاق الشيك  '
                            }
                        }
                    })
                    .formValidation('addField', checkNum, {
                        validators: {
                            notEmpty: {
                                message: 'ادخل رقم الشيك '
                            },
                            digits: {
                                message: 'يجب أن يكون أرقام صحيحة فقط'
                            }
                        }
                    })
                    .formValidation('addField', accountType, {
                        validators: {
                            notEmpty: {
                                message: 'اختر نوع الحساب'
                            }
                        }
                    })
                    .formValidation('addField', bankItem, {
                        validators: {
                            notEmpty: {
                                message: 'اختر الوعاء'
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
                break;

            case 'cash':
                var checkDate = $('#check_date').prop("disabled", true);
                var checkNum = $('#check_number').prop("disabled", true);
                var accountType = $('#account_type').prop("disabled", true);
                var bankItem = $('#bank_item').prop("disabled", true);
                var credit = $('#credit').prop("disabled", true);

                checkDate.siblings('.help-block').hide();
                checkNum.siblings('.help-block').hide();
                accountType.parent().siblings('.help-block').hide();
                bankItem.parent().siblings('.help-block').hide();
                credit.parent().siblings('.help-block').hide();

                $('#banksDepositeForm')
                    .formValidation('removeField', checkDate)
                    .formValidation('removeField', checkNum)
                    .formValidation('removeField', accountType)
                    .formValidation('removeField', bankItem)
                    .formValidation('removeField', credit)
                break;

            default:
                break;
        }

    });


    $('input[name="amount"]').keyup(function(){
        var result = 0;
        result= (parseInt($(this).val())*(parseInt($('#breakPercentageInput').val())/100)).toFixed(2);
        $('#breakAmountInput').val(result);
        console.log(result);
    })
})