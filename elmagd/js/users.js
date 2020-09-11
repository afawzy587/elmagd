$(document).ready(function () {

    $('#addusersForm').formValidation({
        excluded: [':disabled'],
        fields: {
            userName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم المستخدم '
                    }
                }
            },
            userBirthdate:{
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ ميلاد المستخدم  '
                    }
                }
            },
            userDepartment:{
                validators: {
                    notEmpty: {
                        message: 'اختر الادارة  '
                    }
                }
            },
            userRole:{
                validators: {
                    notEmpty: {
                        message: 'اختر الوظيفة  '
                    }
                }
            },
            userEducation: {
                validators: {
                    notEmpty: {
                        message: 'ادخل المؤهل الدراسي '
                    }
                }
            },
            gradYear: {
                validators: {
                    notEmpty: {
                        message: 'ادخل سنة التخرج '
                    },
                    digits:{
                        message: 'يجب  أن يكون أرقام صحيحة'
                    },
                    stringLength: {
                      min: 4,
                      max: 4,
                      message: ' بحد أقصي 4 أرقام'
                    }
                }
            },
            userPhonenum: {
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
            userImg:{
                validators: {
                    notEmpty: {
                        message: 'أضف صورة المستخدم'
                    }
                }
            },
            userAddress: {
                validators: {
                    notEmpty: {
                        message: 'ادخل عنوان المستخدم'
                    }
                }
            },
            userEmail: {
                validators: {
                    notEmpty: {
                        message: 'أدخل البريد الالكتروني'
                    },
                    emailAddress: {
                        message: 'ادخل بريد الكتروني صحيح'
                    }
                }
            },
            userPassword: {
                validators: {
                    notEmpty: {
                        message: 'ادخل كلمة المرور '
                    },
                    stringLength: {
                        min: 8,
                        message: 'كلمة المرور يجب أن لا تقل عن 8 حروف'
                    }
                }
            },
            confirmUserPassword: {
                validators: {
                    notEmpty: {
                        message: 'ادخل كلمة المرور مرة اخري  '
                    },
                    identical: {
                        field: 'userPassword',
                        message: 'غير مطابقة لكلمة المرور'
                    }
                }
            },
            userAccess: {
                validators: {
                    notEmpty: {
                        message: 'اختر المجموعة'
                    }
                }
            },
            userSalary: {
                validators: {
                    notEmpty: {
                        message: 'ادخل الراتب الشهري'
                    },
                    regexp: {
                        regexp: /^[+-]?[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // userName input[name="userName"]
        // userdescription input[name="userdescription"]
    })



})