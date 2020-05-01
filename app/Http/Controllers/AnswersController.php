<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Http\Request;
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAnswersDataTable(Request $request)
    {
        $answers = Answer::where('question_id','=', $request->question_id)->get();
        $datatables = Datatables::of($answers)
            ->editColumn('actions', function ($answer){
                $output = "<button class='btn btn-info btn-sm show' data-id='".$answer->id."'><i class='fas fa-eye'></i></button> ";
                $output .= "<button class='btn btn-success btn-sm edit' data-id='".$answer->id."' data-url='".route('answers.edit',$answer->id)."'><i class='fas fa-edit'></i></button> ";
                $output .= "<button class='btn btn-danger btn-sm delete' data-id='".$answer->id."' data-url='".route('answers.destroy',$answer->id)."'><i class='fas fa-trash'></i></button>";

                return $output;
            })->rawColumns(['actions']);

        return $datatables->make(true);
    }
}
