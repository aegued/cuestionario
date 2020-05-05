<div class="modal fade question-modal" id="question-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Formulario de la Pregunta</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" action="{{ route('questions.store') }}" method="post">
                @csrf
                <input type="hidden" name="questionnaire_id" value="{{ $questionnaire->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="question">Pregunta</label>
                        <textarea name="question" id="question" class="form-control" placeholder="Escriba la pregunta ..."></textarea>
                        <span id="exampleInputEmail1-error" class="error invalid-feedback d-none"></span>
                    </div>
                    <div class="form-group">
                        <label for="answer">Respuesta</label>
                        <textarea name="answer" id="answer" class="form-control" placeholder="Escriba la respuesta ..."></textarea>
                        <span id="exampleInputEmail1-error" class="error invalid-feedback d-none"></span>
                    </div>
                    <div class="form-group">
                        <label for="help">Pista</label>
                        <textarea name="help" id="help" class="form-control" placeholder="Escriba una pista ..."></textarea>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="reset" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
