$(document).ready(function () {

    $('#banksOperationsSearchForm').formValidation({
        excluded: [':disabled'],
        fields: {
            startDate: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ البداية '
                    },
                    callback: {
                      message: 'تاريخ البداية يجب أن يكون قبل تاريخ النهاية',
                      callback: function (startDate, validator, $field) {
                          return checkDateRange($('input[name="startDate"]').val(), $('input[name="endDate"]').val());
                      }
                  }
                }
            },
            endDate: {
                validators: {
                    notEmpty: {
                        message: 'اختر تاريخ النهاية '
                    },
                    callback: {
                      message: 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
                      callback: function (startDate, validator, $field) {
                          return checkDateRange($('input[name="startDate"]').val(), $('input[name="endDate"]').val());
                      }
                  }
                }
            },
            customerName: {
                validators: {
                    notEmpty: {
                        message: 'اختر العميل أو المخزن '
                    }
                }
            },
            products:{
                validators: {
                    notEmpty: {
                        message: 'اختر المنتج  '
                    }
                }
            },
            suppliers:{
                validators: {
                    notEmpty: {
                        message: 'اختر المورد  '
                    }
                }
            },
            types:{
                validators: {
                    notEmpty: {
                        message: 'اختر النوع  '
                    }
                }
            },
  
            startValue: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم العملية'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    },
                    callback: {
                      message: 'رقم البداية يجب أن يكون أصغر من رقم النهاية',
                      callback: function () {
                          return checkNumbersRange(parseFloat($('input[name="startValue"]').val()), parseFloat($('input[name="endValue"]').val()));
                      }
                  }
                }
            },
            endValue:{
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم العملية'
                    },
                    regexp: {
                        regexp: /^[0-9]{1,30}(?:\.[0-9]{1,2})?$/,
                        message: ' يجب أن يحتوي علي أرقام فقط (بحد أقصي رقمين عشريين)'
                    },
                    callback: {
                      message: 'رقم النهاية يجب أن يكون أكبر من رقم البداية',
                      callback: function () {
                          return checkNumbersRange(parseFloat($('input[name="startValue"]').val()), parseFloat($('input[name="endValue"]').val()));
                      }
                  }
                }
            },

        }
    }).on('success.form.bv', function (e) {


    })

  // dates validation
  var checkDateRange = function (startDate, endDate) {
    startDate = startDate.split("/").reverse().join("/");
    endDate = endDate.split("/").reverse().join("/");
    var isValid = (startDate != "" && endDate != "") ? startDate < endDate : true;
    return isValid;
  }

  var checkNumbersRange = function (startNum, endNum) {
    var isValid = (startNum != "" && endNum != "") ? startNum < endNum : true;
    return isValid;
  }

  $('input[name="startDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="endDate'));
  });

  $('input[name="endDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="startDate"]'));
  });

  $('input[name="startValue"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="endValue'));
  });

  $('input[name="endValue"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="startValue"]'));
  });

})