$(document).ready(function () {

    $('#addaccessForm').formValidation({
        excluded: [':disabled'],
        fields: {
            groupName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم المجموغة '
                    }
                }
            },
            groupdescription: {
                validators: {
                    notEmpty: {
                        message: 'ادخل وصف المجموعة  '
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // groupName input[name="groupName"]
        // groupdescription input[name="groupdescription"]
    })

    // access search input name =>> accessSearch
})