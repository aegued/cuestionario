$(document).ready(function(){
    /*
    * Questionnaire Steps
    * */
    $('#questions-tab li:first-child a').addClass('active');
    $('#questions-tab li:first-child a').removeClass('disabled');
    $('#questions-tabContent .tab-pane:first-child').addClass('active');
    $('.tab-pane:first-child .prev-step').addClass('d-none');

    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        let $target = $(e.target);
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $('.tab-pane form').on('submit', function () {
        return false;
    });

    $(".next-step").click(function (e) {
        let $active = $('.nav-pills li > a.active');
        $active.parent().next().find('.nav-link').removeClass('disabled');

        let form = $(this).parent().parent();
        let inputAnswer = form.find('#answer');
        let feedback = form.find('.invalid-feedback');

        resetMessage(inputAnswer, feedback);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.check) {
                    successMessage(inputAnswer, feedback);

                    setTimeout(function () {
                        nextTab($active);
                    }, 1000);

                } else {
                    errorMessage(inputAnswer, feedback);
                }
            }
        });
    });

    $(".prev-step").click(function (e) {
        var $active = $('.nav-pills li > a.active');
        prevTab($active);
    });

    $('.help').click(function (e) {
        let $this = $(this);
        let url = $this.data('url');
        let modal = $this.parent().find('.modal');

        $.get(url, function (data) {
            modal.find('.modal-body').html("<p>"+data.help+"</p>");
            modal.modal('show');
        });
    });

    function nextTab(elem) {
        $(elem).parent().next().find('a[data-toggle="pill"]').click();
    }
    function prevTab(elem) {
        $(elem).parent().prev().find('a[data-toggle="pill"]').click();
    }
    function resetMessage(input, feedback) {
        input.removeClass('is-invalid');
        input.removeClass('is-valid');
        feedback.addClass('d-none');
        input.parent().find('.alert').remove();
    }
    function errorMessage(input, feedback) {
        input.val('');
        input.addClass('is-invalid');
        feedback.html('La respuesta es incorrecta. Vuelva a intentarlo.');
        feedback.removeClass('d-none');
    }
    function successMessage(input, feedback) {
        input.addClass('is-valid');
        input.removeClass('is-invalid');
        input.parent().append(
            '<div class="alert alert-success mt-3">Respuesta correcta</div>'
        );
    }
});
