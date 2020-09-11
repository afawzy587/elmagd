$(document).ready(function(){

    $('#companyDetailsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            companyname: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم الشركة'
                    }
                }
            },
            companyAddress: {
                validators: {
                    notEmpty: {
                        message: 'ادخل العنوان '
                    }
                }
            },
            companyphone: {
                validators: {
                    notEmpty: {
                        message: 'ادخل التليفون'
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: 'ادخل رقم تليفون صحيح',
                    }
                }
            },
            logoImg: {
                validators: {
                    notEmpty: {
                        message: 'اختر صورة الشعار'
                    }
                }
            },
            credit: {
                validators: {
                    notEmpty: {
                        message: ' ادخل الرصيدالافتتاحي للخزنة'
                    },
                    regexp: {
                      regexp: /^[+-]?[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                      message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            checkCredit: {
                validators: {
                    notEmpty: {
                        message: 'ادخل الرصيدالافتتاحي الشيكات'
                    },
                    regexp: {
                      regexp: /^[+-]?[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                      message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            },
            docImg: {
                validators: {
                    notEmpty: {
                        message: 'اختر الوثائق'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {

        // companyname input[name="companyname"]
        // address input[name="companyAddress"]
        // phone input[name="companyphone"]
        // logo input[name="logoImg"]
        // credit input[name="credit"]
        // checkcredit input[name="checkCredit"]
        // docs input[name="docImg"]
    })


})
