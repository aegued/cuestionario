<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('users.index',[
            'users' =>  $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required'             =>  'El nombre del usuario es requerido.',
            'username.required'         =>  'El nombre de usuario es requerido.',
            'username.unique'           =>  'El nombre de usuario ya esta en uso.',
            'email.required'            =>  'El email es requerido.',
            'email.unique'              =>  'Este email ya esta en uso.',
            'email.email'               =>  'El email no es un correo electronico valido.',
            'password.required'         =>  'La Contraseña es requerido.',
        ];

        $validator = Validator::make($request->all(), [
            'name'          =>  'required',
            'username'      =>  'required|unique:users',
            'email'         =>  'required|email|unique:users',
            'password'      =>  'required',
        ], $messages);

        if ($validator->fails())
            return response()->json(['success' => false, 'errors' => $validator->errors()], 409);

        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['success' => true, 'user' => $user], 200);
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
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user)
            return response()->json(['success' => false, 'error' => 'El Usuario no existe.'], 404);

        $user->delete();

        return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente.'], 200);
    }

    /**
     * Return DataTables query of Users
     * */
    public function getUsersDataTable(Request $request)
    {
        $users = User::query();
        $datatables = Datatables::of($users)
            ->editColumn('created_at', function ($user){
                return Carbon::createFromTimeString($user->created_at)->format('m/d/Y H:i:s');
            })
            ->editColumn('actions', function ($user){
                $output = "<button class='btn btn-success btn-sm edit' data-id='".$user->id."' data-url='".route('users.show',$user->id)."'><i class='fas fa-edit'></i></button> ";
                $output .= "<button class='btn btn-danger btn-sm delete' data-id='".$user->id."' data-url='".route('users.destroy',$user->id)."'><i class='fas fa-trash'></i></button>";

                return $output;
            })->rawColumns(['actions']);

        return $datatables->make(true);
    }
}
