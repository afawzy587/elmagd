$(document).ready(function () {

    $('#warehousesSearchForm').formValidation({
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
            supplierName: {
                validators: {
                    notEmpty: {
                        message: 'اختر المورد '
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
            quality:{
                validators: {
                    notEmpty: {
                        message: 'اختر الدرجة  '
                    }
                }
            },
            quality2:{
                validators: {
                    notEmpty: {
                        message: 'اختر التصنيف  '
                    }
                }
            },
            startSerial: {
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم العملية'
                    },
                    digits: {
                        message: 'يجب أن يحتوي علي أرقام صحيحة فقط'
                    },
                    callback: {
                      message: 'رقم البداية يجب أن يكون أصغر من رقم النهاية',
                      callback: function () {
                          return checkNumbersRange($('input[name="startSerial"]').val(), $('input[name="endSerial"]').val());
                      }
                  }
                }
            },
            endSerial:{
                validators: {
                    notEmpty: {
                        message: 'ادخل رقم العملية'
                    },
                    digits: {
                        message: 'يجب أن يحتوي علي أرقام صحيحة فقط'
                    },
                    callback: {
                      message: 'رقم النهاية يجب أن يكون أكبر من رقم البداية',
                      callback: function () {
                          return checkNumbersRange($('input[name="startSerial"]').val(), $('input[name="endSerial"]').val());
                      }
                  }
                }
            }
      
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

  $('input[name="startSerial"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="endSerial'));
  });

  $('input[name="endSerial"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="startSerial"]'));
  });

})