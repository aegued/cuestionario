<?php

namespace App\Http\Controllers;

use App\Questionnaire;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class QuestionnairesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role','!=', 'admin')->get();

        return view('questionnaires.index',[
            'users'    =>  $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return response()->json($request->start, 409);
        $messages = [
            'name.required'             =>  'El nombre del Cuestionario es requerido.',
            'name.unique'               =>  'El nombre del Cuestionario ya esta en uso.',
            'user_id.required'          =>  'La Usuario es requerido.',
        ];

        $validator = Validator::make($request->all(), [
            'name'          =>  'required|unique:questionnaires',
            'user_id'       =>  'required',
        ], $messages);

        if ($validator->fails())
            return response()->json(['success' => false, 'errors' => $validator->errors()], 409);

        $questionnaire = new Questionnaire($request->all());
        $questionnaire->save();

        return response()->json(['success' => true, 'questionnaire' => $questionnaire], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $questionnaire = Questionnaire::find($id);

        return view('questionnaires.show', [
            'questionnaire'     =>  $questionnaire
        ]);
    }

    /**
     * Return the specified resource.
     */
    public function edit($id)
    {
        $questionnaire = Questionnaire::find($id);
        $questionnaire->user = $questionnaire->user->name;

        if(!$questionnaire)
            return response()->json(['success' => false, 'error' => 'El Cuestionario no existe.'], 404);

        return response()->json(['success' => true, 'questionnaire' => $questionnaire], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $questionnaire = Questionnaire::find($id);

        if(!$questionnaire)
            return response()->json(['success' => false, 'error' => 'El Cuestionario no existe.'], 404);

        $messages = [
            'name.required'             =>  'El nombre del Cuestionario es requerido.',
            'user_id.required'          =>  'La Usuario es requerido.',
        ];

        $validator = Validator::make($request->all(), [
            'name'          =>  'required',
            'user_id'       =>  'required',
        ], $messages);

        if ($validator->fails())
            return response()->json(['success' => false, 'errors' => $validator->errors()], 409);

        $questionnaire->user_id = $request->user_id;
        $questionnaire->name = $request->name;
        $questionnaire->start = $request->start;
        $questionnaire->save();

        return response()->json(['success' => true, 'questionnaire' => $questionnaire], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $questionnaire = Questionnaire::find($id);

        if(!$questionnaire)
            return response()->json(['success' => false, 'error' => 'El Cuestionario no existe.'], 404);

        $questionnaire->delete();

        return response()->json(['success' => true, 'message' => 'Cuestionario eliminado correctamente.'], 200);
    }

    public function getQuestionnairesDataTable()
    {
        $questionnaires = Questionnaire::query();
        $datatables = Datatables::of($questionnaires)
            ->editColumn('created_at', function ($questionnaire){
                return Carbon::createFromTimeString($questionnaire->start)->format('m/d/Y H:i:s');
            })
            ->editColumn('user', function ($questionnaire){
                $user = $questionnaire->user;

                return $user->name;
            })
            ->editColumn('actions', function ($questionnaire){
                $output = "<a href='".route('questionnaires.show',$questionnaire->id)."' class='btn btn-info btn-sm'><i class='fas fa-eye'></i></a> ";
                $output .= "<button class='btn btn-success btn-sm edit' data-id='".$questionnaire->id."' data-url='".route('questionnaires.edit',$questionnaire->id)."'><i class='fas fa-edit'></i></button> ";
                $output .= "<button class='btn btn-danger btn-sm delete' data-id='".$questionnaire->id."' data-url='".route('questionnaires.destroy',$questionnaire->id)."'><i class='fas fa-trash'></i></button>";

                return $output;
            })->rawColumns(['actions']);

        return $datatables->make(true);
    }

    public function setFinish($id)
    {
        $questionnaire = Questionnaire::find($id);
        $questionnaire->completed = true;
        $questionnaire->save();

        return response()->json(['completed' => true], 200);
    }
}
