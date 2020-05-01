@extends('layouts.app')

@section('page_title', $questionnaire->name)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Preguntas</h3>
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#question-modal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="questions-table" class="table table-striped nowrap" style="width: 100%;">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>Pregunta</td>
                                <td>Acciones</td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Respuestas</h3>
                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#question-modal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="answers-table" class="table table-striped nowrap" style="width: 100%;">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>Respuesta</td>
                                <td>Acciones</td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('questionnaires.modal_question')
@endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- AlertifyJS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- AlertifyJS -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /*
            * Questions Table
            * */

            //Init DataTables
            var tableQuestions = $('#questions-table').DataTable({
                processing: true,
                serverSide: true,
                info: false,
                searching: false,
                paging: false,
                language: {
                    url: "/datatable_spanish.json"
                },
                ajax: {
                    url: '{!! route('getQuestions') !!}',
                    data: {'questionnaire_id': '{{ $questionnaire->id }}'}
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'question', name: 'question' },
                    { data: 'actions', name: 'actions' },
                ]
            });

            //Modal Form
            let questionModal = $('.question-modal');
            let formQuestion = questionModal.find('form');
            let formQuestionLastAction = formQuestion.attr('action');
            let formQuestionLastMethod = formQuestion.attr('method');

            //Event when the table is draw
            tableQuestions.on('draw', function () {
                //Delete Questionnaire
                $('#questions-table button.delete').on('click', function () {
                    let url = $(this).data('url');

                    alertify.confirm('Confirmacion','¿Estas seguro que desea eliminar la Pregunta?',
                        function () {
                            $.ajax({
                                url: url,
                                method: 'DELETE',
                                success: function () {
                                    //Reset the table with the new data
                                    tableQuestions.ajax.reload();

                                    toastr.success('Pregunta eliminada correctamente.');
                                },
                                error: function (response) {
                                    let error = response.responseJSON.error;
                                    toastr.error(error);
                                }
                            });
                        },
                        function () {
                            toastr.error('Cancelado');
                        }
                    ).set('labels', {ok: 'Si', cancel:"Cancelar"});
                });

                //Edit Questionnaire
                $('#questions-table button.edit').on('click', function () {
                    let url = $(this).data('url');

                    $.get(url, function () {
                        questionModal.modal('show');
                    }).done(function (response) {
                        let question = response.question;

                        formQuestion.attr('action', '/questions/'+question.id);
                        formQuestion.attr('method', 'PUT');
                        formQuestion.find('#question').val(question.question);
                    }).fail(function (response) {
                        let error = response.responseJSON.error;
                        toastr.error(error);
                    });
                });

                //Show Answers from a Question
                $('#questions-table button.show').on('click', function () {
                    drawAnswersTable($(this).data('id'));
                });
            });

            //Submit form to save user data
            formQuestion.submit(function (e) {
                e.preventDefault();

                let formUrl = formQuestion.attr('action');
                let formMethod = formQuestion.attr('method');
                let btnSubmit = formQuestion.find('.btn-primary');
                let btnSubmitValue = btnSubmit.text();

                resetErrorsFeedback(formQuestion);

                $.ajax({
                    method: formMethod,
                    url: formUrl,
                    data: formQuestion.serialize(),
                    beforeSend: function(){
                        btnSubmit.html('<i class="fas fa-spinner fa-spin"></i>');
                    },
                    success: function (response) {
                        //Restore the button value
                        btnSubmit.html(btnSubmitValue);
                        //Hide the modal
                        questionModal.modal('hide');
                        //Reset the table with the new data
                        tableQuestions.ajax.reload();

                        toastr.success('Pregunta creada correctamente.');
                    },
                    error: function (response) {
                        let errors = response.responseJSON.errors;

                        //Restore the button value
                        btnSubmit.html(btnSubmitValue);

                        //Set question errors messages
                        if (errors.question){
                            let inputQuestion = formQuestion.find('#question');
                            let feedback = inputQuestion.parent().find('.invalid-feedback');

                            inputQuestion.addClass('is-invalid');
                            feedback.html(errors.question);
                            feedback.removeClass('d-none');
                        }
                    }
                });
            });

            //Reset errors feedback
            function resetErrorsFeedback(form){
                let feedbacks = form.find('.invalid-feedback');
                let inputsInvalid = form.find('input');
                let textareasInvalid = form.find('textarea');

                feedbacks.addClass('d-none');
                inputsInvalid.removeClass('is-invalid');
                textareasInvalid.removeClass('is-invalid');
            }

            //Modals Events
            questionModal.on('hidden.bs.modal', function (e) {
                formQuestion[0].reset();
                formQuestion.attr('action', formQuestionLastAction);
                formQuestion.attr('method', formQuestionLastMethod);
                resetErrorsFeedback(formQuestion);
            });


            /*
            * Answers Table
            * Separated in a function
            * */

            function drawAnswersTable(questionID) {
                //Init DataTables
                let tableAnswer = $('#answers-table');

                tableAnswer.DataTable().destroy();

                var tableAnswers = tableAnswer.DataTable({
                    processing: true,
                    serverSide: true,
                    info: false,
                    searching: false,
                    paging: false,
                    language: {
                        url: "/datatable_spanish.json"
                    },
                    ajax: {
                        url: '{!! route('getAnswers') !!}',
                        data: {'question_id': questionID}
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'answer', name: 'answer' },
                        { data: 'actions', name: 'actions' },
                    ]
                });


            }

        });
    </script>
@endsection
