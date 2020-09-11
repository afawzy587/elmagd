$(document).ready(function () {

    $('#addProductsForm').formValidation({
        excluded: [':disabled'],
        fields: {
            productName: {
                validators: {
                    notEmpty: {
                        message: 'ادخل اسم المنتج '
                    }
                }
            },
            productdescription: {
                validators: {
                    notEmpty: {
                        message: 'ادخل وصف المنتج  '
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        // productName input[name="productName"]
        // productdescription input[name="productdescription"]
    })

    // product search input name =>> productsSearch
})