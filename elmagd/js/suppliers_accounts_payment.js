$(document).ready(function () {

    $('#suppliersAccountsPaymentForm').formValidation({
        excluded: [':disabled'],
        fields: {
            paymentDate: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ الدفع '
                    }
                }
            },
            paymenttype: {
                validators: {
                    notEmpty: {
                        message: 'اختر نوع المعاملة  '
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
            checkDate: {
                validators: {
                    notEmpty: {
                        message: 'ادخل تاريخ استحقاق الشيك  '
                    }
                }
            },
            checkNum: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم الشيك '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
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
                        message: 'اختر نوع الحساب'
                    }
                }
            },
            bankItem: {
                validators: {
                    notEmpty: {
                        message: 'اختر الوعاء'
                    }
                }
            },
            payment_case: {
                validators: {
                    notEmpty: {
                        message: 'اختر سبب الدفع'
                    }
                }
            },
            recipient: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم المستلم'
                    }
                }
            },
        }
    }).on('success.form.bv', function (e) {


    })

    $('input[name="paymenttype"]').on('change', function () {
        key = $(this).val();
        switch (key) {
            case 'cash':
                var checkDate = $('#check_date').prop("disabled", false);
                var checkNum = $('#check_number').prop("disabled", false);
                var accountType = $('#account_type').prop("disabled", false);
                var bankItem = $('#bank_item').prop("disabled", false);

                $('#suppliersAccountsPaymentForm')
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
                break;

            case 'check':
                var checkDate = $('#check_date').prop("disabled", true);
                var checkNum = $('#check_number').prop("disabled", true);
                var accountType = $('#account_type').prop("disabled", true);
                var bankItem = $('#bank_item').prop("disabled", true);

                checkDate.siblings('.help-block').hide();
                checkNum.siblings('.help-block').hide();
                accountType.parent().siblings('.help-block').hide();
                bankItem.parent().siblings('.help-block').hide();

                $('#suppliersAccountsPaymentForm')
                    .formValidation('removeField', checkDate)
                    .formValidation('removeField', checkNum)
                    .formValidation('removeField', accountType)
                    .formValidation('removeField', bankItem)
                break;

            default:
                break;
        }

    });
})