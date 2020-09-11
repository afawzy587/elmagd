$(document).ready(function () {

    $('#customersPricingSearchForm').formValidation({
        excluded: [':disabled'],
        fields: {
            customerName: {
                validators: {
                    notEmpty: {
                        message: 'اختر العميل '
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
            supplier:{
                validators: {
                    notEmpty: {
                        message: 'اختر المورد  '
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


  $('input[name="startDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="endDate'));
  });

  $('input[name="endDate"]').on('change', function (evt) {
    $('#customersAccountsSearchForm').formValidation('revalidateField', $(this));
    $('#customersAccountsSearchForm').formValidation('revalidateField', $('input[name="startDate"]'));
  });

})