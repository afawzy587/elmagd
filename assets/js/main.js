$(document).ready(function(){
    $(".uploadimage").change(function () {
        if ( this.files &&  this.files[0]) {
            previewDiv= $(this).parent().parent().next().children('.imagePreviewURL');
            var reader = new FileReader();
            reader.onload = function (e) {
                // $(this).attr('value', e.target.result);
                previewDiv.attr('src', e.target.result);
                // previewDiv.hide();
                // previewDiv.fadein();
                // console.log( $(this));
            }
           
            reader.readAsDataURL( this.files[0]);
        }
    });



    // main div mt
    function setMainDivTM(){
        $('.mainPageContainer').css("margin-top",  $('.navbar.mainNavbar').height() + 50 + 'px');
    };
    setMainDivTM();
    $(window).resize(function() {
//        console.log( $('.navbar.mainNavbar').height());
        setMainDivTM();
    });
})
