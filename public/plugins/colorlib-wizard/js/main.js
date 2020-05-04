(function($) {

    var form = $("#wizard-form");
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        saveState: true,
        transitionEffect: "fade",
        labels: {
            previous : 'Anterior',
            next : 'Siguiente',
            finish : 'Finalizar',
            current : ''
        },
        titleTemplate : '<div class="title"><span class="title-number">#index#</span></div>',
        onStepChanging: function(event, currentIndex){
            event.preventDefault();

            let inputAnswer = form.find('#step-'+currentIndex).find('input');
            let value = inputAnswer.val();
            let url = inputAnswer.data('url');
            let token = form.find('input[name="_token"]').val();
            let feedback = inputAnswer.parent().find('.invalid-feedback');

            inputAnswer.removeClass('is-invalid');
            feedback.addClass('d-none');

            var result = $.ajax({
                url: url,
                method: 'POST',
                data: {answer: value, _token: token},
                success: function (response) {
                    if (!response.check) {
                        inputAnswer.val('');
                        inputAnswer.addClass('is-invalid');
                        feedback.html('La respuesta es incorrecta. Vuelva a intentarlo.');
                        feedback.removeClass('d-none');
                    }
                }
            });

            return result.always(function (response) {
                return response.check;
            });

        },
        onFinished: function (event, currentIndex)
        {
            alert('Sumited');
        }
    });

})(jQuery);
