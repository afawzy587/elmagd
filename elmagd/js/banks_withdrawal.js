$(document).ready(function () {

    $('#banksWithdrawalForm').formValidation({
        excluded: [':disabled'],
        fields: {
            operationDate: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ العملية '
                    }
                }
            },
            credit: {
                validators: {
                    notEmpty: {
                        message: 'اختر الرصيد  '
                    }
                }
            },
            accountType: {
                validators: {
                    notEmpty: {
                        message: 'اختر نوع الحساب  '
                    }
                }
            },
            bankItem: {
                validators: {
                    notEmpty: {
                        message: 'اختر الوعاء  '
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
            amount: {
                validators: {
                    notEmpty: {
                        message: 'ادخل المبلغ  '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    },
                    callback: {
                        message: 'هذا المبلغ يجب أن يكون متاح للسحب',
                        callback: function () {
                            return parseInt($('input[name="amount"]').val()) <= parseInt($('input[name="availableAmount"]').val());
                        }
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {


    })




    // $('input[name="amount"]').keyup(function(){
    //     var result = 0;
    //     result= (parseInt($(this).val())*(parseInt($('#breakPercentageInput').val())/100)).toFixed(2);
    //     $('#breakAmountInput').val(result);
    //     console.log(result);
    // })
})