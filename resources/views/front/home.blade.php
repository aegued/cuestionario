@extends('front.layouts.app')

@section('content')
    <form method="POST" id="wizard-form">
        @csrf
        @php $count = 0; @endphp
        @foreach($questions as $question)
        <h3>{{ $questionnaire->name }}</h3>

        <fieldset>
            <h2>{{ $question->question }}</h2>

            <div class="form-group" id="step-@php echo $count; @endphp">
                <input
                    type="text"
                    name="answer"
                    id="answer"
                    class="form-control"
                    placeholder="Escribe tu respuesta ... "
                    data-url="{{ route('questions.check',$question->id) }}"
                />
                <span id="exampleInputEmail1-error" class="error invalid-feedback d-none"></span>
            </div>
        </fieldset>
            @php $count++; @endphp
        @endforeach

    </form>
@endsection

@section('css')
    <!-- Font Icon -->
    <link rel="stylesheet" href="{{ asset('plugins/colorlib-wizard/fonts/material-icon/css/material-design-iconic-font.min.css') }}">
    <!-- Wizard css -->
    <link rel="stylesheet" href="{{ asset('plugins/colorlib-wizard/css/style.css') }}">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/additional-methods.min.js"></script>
    <script src="{{ asset('plugins/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{'plugins/colorlib-wizard/js/main.js'}}"></script>
@endsection
