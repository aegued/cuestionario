<div class="modal fade answer-modal" id="answer-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Formulario de la Respuesta</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" action="{{ route('answers.store') }}" method="post">
                @csrf
                <input type="hidden" name="question_id" id="question_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="answer">Respuesta</label>
                        <input type="text" class="form-control" name="answer" id="answer" placeholder="Escriba la Respuesta ...">
                        <span id="exampleInputEmail1-error" class="error invalid-feedback d-none"></span>
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
