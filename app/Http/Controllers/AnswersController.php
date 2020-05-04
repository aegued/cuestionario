<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'question_id.required'  =>  'La Pregunta es requerida.',
            'answer.required'       =>  'La Respuesta es requerida.',
        ];

        $validator = Validator::make($request->all(), [
            'question_id'   =>  'required',
            'answer'        =>  'required',
        ], $messages);

        if ($validator->fails())
            return response()->json(['success' => false, 'errors' => $validator->errors()], 409);

        $answer = new Answer($request->all());
        $answer->save();

        return response()->json(['success' => true, 'answer' => $answer], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $answer = Answer::find($id);

        if(!$answer)
            return response()->json(['success' => false, 'error' => 'La Respuesta no existe.'], 404);

        return response()->json(['success' => true, 'answer' => $answer], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $answer = Answer::find($id);

        if(!$answer)
            return response()->json(['success' => false, 'error' => 'La Respuesta no existe.'], 404);

        $messages = [
            'question_id.required'  =>  'La Pregunta es requerida.',
            'answer.required'       =>  'La Respuesta es requerida.',
        ];

        $validator = Validator::make($request->all(), [
            'question_id'  =>  'required',
            'answer'       =>  'required',
        ], $messages);

        if ($validator->fails())
            return response()->json(['success' => false, 'errors' => $validator->errors()], 409);

        $answer->question_id = $request->question_id;
        $answer->answer = $request->answer;
        $answer->save();

        return response()->json(['success' => true, 'answer' => $answer], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $answer = Answer::find($id);

        if(!$answer)
            return response()->json(['success' => false, 'error' => 'La Respuesta no existe.'], 404);

        $answer->delete();

        return response()->json(['success' => true, 'message' => 'Respuesta eliminada correctamente.'], 200);
    }

    public function getAnswersDataTable(Request $request)
    {
        $answers = Answer::where('question_id','=', $request->question_id)->get();
        $datatables = Datatables::of($answers)
            ->editColumn('actions', function ($answer){
                $output = "<button class='btn btn-success btn-sm edit' data-id='".$answer->id."' data-url='".route('answers.edit',$answer->id)."'><i class='fas fa-edit'></i></button> ";
                $output .= "<button class='btn btn-danger btn-sm delete' data-id='".$answer->id."' data-url='".route('answers.destroy',$answer->id)."'><i class='fas fa-trash'></i></button>";

                return $output;
            })->rawColumns(['actions']);

        return $datatables->make(true);
    }
}
