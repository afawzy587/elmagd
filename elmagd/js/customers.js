$(document).ready(function () {

    var checkTotalPercentage = function () {

        return false;
    }

    $('#addcustomersForm').formValidation({
        excluded: [':disabled'],
        fields: {
            customerName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم العميل '
                    }
                }
            },
            customerAddress: {
                validators: {
                    notEmpty: {
                        message: 'ادخل عنوان العميل   '
                    }
                }
            },
            phoneNum1: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم التليفون'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: 'ادخل رقم تليفون صحيح'
                    }
                }
            },
            phoneNum2: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم التليفون'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: 'ادخل رقم تليفون صحيح'
                    }
                }
            },
            customerEmail: {
                validators: {
                    notEmpty: {
                        message: 'أدخل البريد الالكتروني'
                    },
                    emailAddress: {
                        message: 'ادخل بريد الكتروني صحيح'
                    }
                }
            },
            customerAgent: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم ممثل العميل '
                    }
                }
            },
            customerAgentPhonenum: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم تليفون ممثل العميل'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: 'ادخل رقم تليفون صحيح'
                    }
                }
            },
            customerAgentEmail: {
                validators: {
                    notEmpty: {
                        message: 'أدخل البريد الالكتروني'
                    },
                    emailAddress: {
                        message: 'ادخل بريد الكتروني صحيح'
                    }
                }
            },
            docImg: {
                validators: {
                    notEmpty: {
                        message: 'أضف صورة البطاقة الضريبية'
                    }
                }
            },
            docImg2: {
                validators: {
                    notEmpty: {
                        message: 'أضف صورة السجل التجاري '
                    }
                }
            },
            // percInput: {
            //     validators: {
            //     regexp: {
            //         regexp: /^[+-]?[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
            //         message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
            //     },
            //     callback: {
            //         message: 'مجموعهم 100%',
            //         callback: function (startDate, validator, $field) {
            //             return checkTotalPercentage();
            //         }
            //     }
            // }
            // }
        }
    }).on('success.form.bv', function (e) {

        // customerName input[name="customerName"]
        // customerdescription input[name="customerdescription"]
    })

    // // customer search input name =>> customersSearch
    $('.percentageInput').keyup(function () {
        var sum = 0;
        $('.percentageInput').each(function () {
            if ($(this).val() != '') { sum += parseInt($(this).val()); }
        })
        if (sum != 100) { $('#percentageErrorMsg').css({ 'display': 'block' }); } else {
            $('#percentageErrorMsg').css({ 'display': 'none' }) }
    })


})