$(document).ready(function () {

    $('#addwarehousesForm').formValidation({
        excluded: [':disabled'],
        fields: {
            warehouseName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم المخزن '
                    }
                }
            },
            warehouseAddress: {
                validators: {
                    notEmpty: {
                        message: 'ادخل عنوان المخزن   '
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
            warehouseSupervisor: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم المسئول '
                    }
                }
            },
            whSupervisorPhonenum: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم تليفون المسئول'
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
            }
        }
    }).on('success.form.bv', function (e) {

        // warehouseName input[name="warehouseName"]
        // warehousedescription input[name="warehousedescription"]
    })

    // warehouse search input name =>> warehousesSearch
})