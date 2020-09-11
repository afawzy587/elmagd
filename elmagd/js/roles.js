$(document).ready(function () {

    $('#addRoleForm').formValidation({
        excluded: [':disabled'],
        fields: {
            roleName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل المسمي الوظيفي'
                    }
                }
            },
            department: {
                validators: {
                    notEmpty: {
                        message: 'اختر الإدارة '
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // roleName input[name="roleName"]
        // department input[name="department"]
    })
})