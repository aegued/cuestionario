@extends('front.layouts.app')

@section('content')
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
            <div class="card px-5 py-3 mt-3 mb-3">
                <div class="row">
                    <div class="col-md-12">

                        <ul class="nav nav-pills justify-content-center mb-3" id="questions-tab" role="tablist">
                            @php $count = 0; @endphp
                            @foreach($questions as $question)
                                @php $count++; @endphp
                                <li class="nav-item">
                                    <a class="nav-link" id="questions-tab-1" data-toggle="pill" href="#questions-content-{{ $question->id }}" role="tab">{{ $count }}</a>
                                </li>
                            @endforeach
                                <li class="nav-item">
                                    <a class="nav-link" id="questions-tab-1" data-toggle="pill" href="#finish-content" role="tab">{{ $count+1 }}</a>
                                </li>
                        </ul>

                        <div class="tab-content" id="questions-tabContent">
                            @foreach($questions as $question)
                                <div class="tab-pane fade show" id="questions-content-{{ $question->id }}" role="tabpanel">
                                    <h4 class="mb-5">{{ $question->question }}</h4>

                                    <form action="{{ route('questions.check', $question->id) }}" method="POST" class="form-question">
                                        @csrf
                                        <div class="form-group answer-fields">
                                            <input type="text" class="form-control" name="answer" id="answer" placeholder="Escribe la respuesta ...">
                                            <span id="exampleInputEmail1-error" class="error invalid-feedback d-none"></span>
                                        </div>

                                        <div class="form-group text-center">
                                            <button type="button" class="btn btn-primary btn-sm prev-step">Anterior</button>
                                            <button type="button" class="btn btn-primary btn-sm next-step">Siguiente</button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                                <div class="tab-pane fade" id="finish-content" role="tabpanel">
                                    <div class="alert alert-success text-center">
                                        Enhorabuena has finalizado el test
                                    </div>
                                    <form>
                                        <div class="form-group text-center">
                                            <button type="button" class="btn btn-primary btn-sm prev-step">Anterior</button>
                                        </div>

                                    </form>
                                </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('js')
    <script src="{{ asset('main.js') }}"></script>
@endsection
