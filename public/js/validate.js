function isEmpty(value) {
    return typeof value == 'string' && !value.trim() || typeof value == 'undefined' || value === null;
}

function printErrorMsg (msg) {

    var totalErrors         = msg.length,
        validationModal     = $('#validationErrorsModal'),
        validationModalBody = validationModal.find('.modal-body');

    validationModalBody.html('');

    $.each( msg, function( index, value ) {

        validationModalBody.append(' <div class="alert alert-danger" role="alert">' + value + '</div>');

        if (index === totalErrors - 1) {
            validationModal.modal('show');
        }
    });

}


function jsFormValidate( formSubmit, validateUrl ) {
    formSubmit.click(function(e){

        e.preventDefault();

        var form = $(this).closest('form');

        $.ajax({

            url: validateUrl,

            type:'POST',

            data: form.serialize(),

            success: function(data) {

                if($.isEmptyObject(data.error)){

                    form.submit();

                }else{
                    printErrorMsg(data.error);
                }

            },

            error: function (data) {
                window.location.reload;
            }

        });

    });

}
