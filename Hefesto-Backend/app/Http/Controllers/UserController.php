<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->rol == 'administrador') {
            $users = User::orderBy('name', 'asc')->get();
        } else {
            // For other roles, potentially show only themselves or a specific subset.
            // Modify this logic as needed based on your application's requirements.
            $users = User::where('id', $user->id)->get();
        }
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'primer_apellido' => ['required', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'rol' => ['required', 'in:operario,tecnico,administrador'],
            'foto_perfil' => ['nullable', 'string'], // You might want to add validation for file types etc.
            'habilitado' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'primer_apellido.required' => 'El campo primer apellido es obligatorio.',
            'segundo_apellido.max' => 'El segundo apellido no puede tener más de :max caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener un mínimo de :min caracteres.',
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'foto_perfil.string' => 'La foto de perfil debe ser una cadena de texto.',
            'habilitado.boolean' => 'El campo habilitado debe ser booleano.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $validatedData = [
            'name' => strip_tags($request->input('name')),
            'primer_apellido' => strip_tags($request->input('primer_apellido')),
            'segundo_apellido' => strip_tags($request->input('segundo_apellido')),
            'email' => strip_tags($request->input('email')),
            'password' => Hash::make($request->input('password')),
            'rol' => $request->input('rol'),
            'foto_perfil' => $request->input('foto_perfil'),
            'habilitado' => $request->input('habilitado', true), // Default to true if not provided
        ];

        $newUser = User::create($validatedData);

        if (!$newUser) {
            return response()->json(['error' => 'No se logró crear el usuario'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($newUser, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255', 'min:2'],
            'primer_apellido' => ['sometimes', 'required', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
            'rol' => ['sometimes', 'required', 'in:operario,tecnico,administrador'],
            'habilitado' => ['sometimes', 'boolean'],
            'id_campus' => ['sometimes','required','integer'],
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'primer_apellido.required' => 'El campo primer apellido es obligatorio.',
            'segundo_apellido.max' => 'El segundo apellido no puede tener más de :max caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener un mínimo de :min caracteres.',
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'habilitado.boolean' => 'El campo habilitado debe ser booleano.',
            'id_campus.required' => 'El campo id_campues es obligatorio'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $validatedData = [];
        if ($request->filled('name')) $validatedData['name'] = strip_tags($request->input('name'));
        if ($request->filled('primer_apellido')) $validatedData['primer_apellido'] = strip_tags($request->input('primer_apellido'));
        if ($request->filled('segundo_apellido')) $validatedData['segundo_apellido'] = strip_tags($request->input('segundo_apellido'));
        if ($request->filled('email')) $validatedData['email'] = strip_tags($request->input('email'));
        if ($request->filled('password')) $validatedData['password'] = Hash::make($request->input('password'));
        if ($request->filled('rol')) $validatedData['rol'] = $request->input('rol');
        if ($request->has('habilitado')) $validatedData['habilitado'] = $request->boolean('habilitado');
        if ($request->filled('id_campus')) $validatedData['id_campus'] = $request->input('id_campus');


        $user->update($validatedData);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(string $id)
    {
        $authUser = auth()->user();
        $userToDelete = User::find($id);

        if (!$userToDelete) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        if ($authUser->rol == 'administrador') {
            if ($authUser->id != $userToDelete->id) {
              
               $userToDelete->update(['habilitado' => 0]);
                return response()->json(['message' => 'Usuario deshabilitado'], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'No puedes eliminar tu propia cuenta'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(['error' => 'No tienes permisos para eliminar usuarios'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function all(){
        try {
            $users = User::all();
            return response()->json(['data' => $users], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los usuarios.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
  
      public function enable(string $id) {
        $authUser = auth()->user();
        if($authUser->rol !== 'administrador') {
          return response()->json(['error' => 'No tienes permisos para habilitar usuarios'], Response::HTTP_UNAUTHORIZED);
        }
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $user->update(['habilitado' => 1]);
        return response()->json(['message' => 'Usuario habilitado'], Response::HTTP_OK);
      }

        public function disable(string $id) {
            $authUser = auth()->user();
              if($authUser->rol !== 'administrador') {
                  return response()->json(['error' => 'No tienes permisos para deshabilitar usuarios'], Response::HTTP_UNAUTHORIZED);
              }
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
            }

          $user->update(['habilitado' => 0]);
            return response()->json(['message' => 'Usuario deshabilitado'], Response::HTTP_OK);
        }
}