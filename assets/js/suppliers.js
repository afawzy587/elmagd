$(document).ready(function () {

    $('#addsuppliersForm').formValidation({
        excluded: [':disabled'],
        fields: {
            supplierName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم المورد '
                    }
                }
            },
            supplierAddress: {
                validators: {
                    notEmpty: {
                        message: 'ادخل عنوان المورد  '
                    }
                }
            },
            supplierPhoneNum1: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم التليفون   '
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: 'ادخل رقم تليفون صحيح',
                    }
                }
            },
            supplierPhoneNum2: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم التليفون   '
                    },
                    regexp: {
                        regexp: /^01[0-2]{1}[0-9]{8}/,
                        message: 'ادخل رقم تليفون صحيح',
                    }
                }
            },
            supplierImg: {
                validators: {
                    notEmpty: {
                        message: 'ارفع صورة المورد '
                    }
                }
            },
            docImg: {
                validators: {
                    notEmpty: {
                        message: 'ارفع صورة الوثيقة '
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'ادخل كلمة المرور  '
                    },
                    stringLength: {
                        min: 8,
                        message: 'كلمة المرور يجب أن لا تقل عن 8 حروف'
                    }
                }
            },
            confirmPassword: {
                validators: {
                    notEmpty: {
                        message: 'ادخل كلمة المرور مرة اخري  '
                    },
                    identical: {
                        field: 'password',
                        message: 'غير مطابقة لكلمة المرور'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // supplierName input[name="supplierName"]
        // supplierAddress input[name="supplierAddress"]
        // supplierPhoneNum1 input[name="supplierPhoneNum1"]
        // supplierPhoneNum2 input[name="supplierPhoneNum2"]
        // supplierImg input[name="supplierImg"]
        // docImg input[name="docImg"]
        // password input[name="password"]
        // confirmPassword input[name="confirmPassword"]
    })

    // product search input name =>> suppliersSearch
})