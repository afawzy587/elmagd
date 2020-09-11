$(document).ready(function () {

    $('#departmentSearch').keypress(function (e) {
        var key = e.which;
        if (key == 13) {
            // search input value =>> $(this)[0].value
            console.log($(this)[0].value);
            $('#departmentSearchForm').submit();

            return false;
        }
    });


    $('#companyDetailsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            departmenName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم الادارة'
                    }
                }
            },
            departmentDesc: {
                validators: {
                    notEmpty: {
                        message: 'ادخل وصف الادارة '
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // departmenName input[name="departmenName"]
        // departmentDesc input[name="departmentDesc"]
    })
})