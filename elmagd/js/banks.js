$(document).ready(function () {

    $('#addbankForm').formValidation({
        excluded: [':disabled'],
        fields: {
            bankName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم البنك '
                    }
                }
            },
            accountNum: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم الحساب  '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            bankItemName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم الوعاء  '
                    }
                }
            },
            BankItemCode: {
                validators: {
                    notEmpty: {
                        message: 'ادخل كود الوعاء   '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            openingCredit: {
                validators: {
                    notEmpty: {
                        message: 'ادخل الرصيد الافتتاحي   '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            paymentDuration: {
                validators: {
                    notEmpty: {
                        message: 'ادخل مدة السداد    '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            interestRate: {
                validators: {
                    notEmpty: {
                        message: 'ادخل نسبة الفائدة  '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            interestDuration: {
                validators: {
                    notEmpty: {
                        message: 'ادخل مدة السداد    '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            value: {
                validators: {
                    notEmpty: {
                        message: 'ادخل قيمة الحد   '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            percentage: {
                validators: {
                    notEmpty: {
                        message: 'ادخل نسبة القطع    '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            relatedCustomer: {
                validators: {
                    notEmpty: {
                        message: 'اختر العميل'
                    }
                }
            },
            relatedProduct: {
                validators: {
                    notEmpty: {
                        message: 'اختر المنتج'
                    }
                }
            },
            accountCode: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم الحساب  '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            opening_credit: {
                validators: {
                    notEmpty: {
                        message: 'ادخل الرصيد الافتتاحي   '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            interest_rate: {
                validators: {
                    notEmpty: {
                        message: 'ادخل نسبة الفائدة  '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            interest_duration: {
                validators: {
                    notEmpty: {
                        message: 'ادخل مدة الفائدة    '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            account_num: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم الحساب  '
                    },
                    digits: {
                        message: 'يجب أن يكون أرقام صحيحة فقط'
                    }
                }
            },
            opening_Credit: {
                validators: {
                    notEmpty: {
                        message: 'ادخل الرصيد الافتتاحي   '
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
        }
    }).on('success.form.bv', function (e) {


    })
    // calc credit line
    $('input[name="value"]').keyup(function () {
        var inputs = $('input.creditLineInput');
        var totalCreditLine = 0;
        $.each(inputs, function () {
            console.log($(this).val());
            if ($(this).val() != '') {
                totalCreditLine += parseFloat($(this).val());
            }

        })
        $('input#totalCreditLineAmount').val(totalCreditLine)
        console.log(totalCreditLine);

    })


    // calc credit
    $('input[name="openingCredit"]').keyup(function () {
        var inputsEle = $('input.openingCreditInput');
        var totalCredit = 0;
        $.each(inputsEle, function () {
            console.log($(this).val());
            if ($(this).val() != '') {
                totalCredit += parseFloat($(this).val());
            }

        })
        $('input#totalCredit').val(totalCredit)
        console.log(totalCredit);

    })

})