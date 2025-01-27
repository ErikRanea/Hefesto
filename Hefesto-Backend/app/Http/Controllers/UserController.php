<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
       $perPage = $request->input('per_page', 20); // Numero de usuarios por pagina, default a 5
        if ($user->rol == 'administrador') {
           $users = User::orderBy('name', 'asc')->paginate($perPage);
        } else {
            $users = User::where('id', $user->id)->paginate($perPage);
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
                $userToDelete->delete();
                return response()->json(['message' => 'Usuario eliminado'], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'No puedes eliminar tu propia cuenta'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(['error' => 'No tienes permisos para eliminar usuarios'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function all(Request $request){
        try {
            $perPage = $request->input('per_page', 5); // Numero de usuarios por pagina, default a 5
            $users = User::paginate($perPage);
            return response()->json(['data' => $users], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los usuarios.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'new_password' => 'required|string|min:8',
                'current_password' => 'required|string'
            ],
            [
                'new_password.required' => 'El campo new_password es obligatorio',
                'new_password.min' => 'La new_password debe de tener un mínimo de 8 caracteres',
                'current_password.required' => 'El campo current_password es obligatorio',
            ]
        );
    
        if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

             $user = auth()->user();
            if (!$user) {
                 return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_UNAUTHORIZED);
              }

              // Check current password
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return response()->json(['error' => 'La contraseña actual no es válida.'], Response::HTTP_UNAUTHORIZED);
            }


           
            // Hash the new password
            $hashedPassword = Hash::make($request->input('new_password'));

           // Update the user's password in the database
           $user->password = $hashedPassword;
           $user->save();

           return response()->json(['message' => 'Contraseña reseteada con éxito.'], Response::HTTP_OK);
          } catch (Exception $e) {
            return response()->json(['error' => 'Error al resetear la contraseña.', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    /**
     * Metodo estáticos
     */


    public static function createDummyUsers()
    {
        $users = [];
        $roles = ['operario', 'tecnico', 'administrador'];

        // Create 3 users per role
        for ($i = 0; $i < 3; $i++) {
            foreach ($roles as $role) {
                $users[] = [
                    'name' => "{$role}User" . ($i+1),
                    'primer_apellido' => 'Test',
                    'segundo_apellido' => 'User',
                    'email' => Str::random(10) . '@example.com',
                    'password' => Hash::make('password123'),
                    'rol' => $role,
                    'foto_perfil' => null,
                    'habilitado' => true,
                    'id_campus' => 2
                ];
            }
        }

        // Add a extra administrator user
        $users[] = [
                'name' => "adminUser" . 4,
                'primer_apellido' => 'Test',
                'segundo_apellido' => 'User',
                'email' => Str::random(10) . '@example.com',
                'password' => Hash::make('password123'),
                'rol' => 'administrador',
                'foto_perfil' => null,
                'habilitado' => true,
                'id_campus' => 2
            ];
        
        // Create the users in the database
        try {
              foreach ($users as $userData) {
                 User::create($userData);
              }
          }
         catch(\Exception $e) {
            return response()->json(['error' => 'Error al generar usuarios de prueba: ' . $e->getMessage()], 500);
        }
        return response()->json(['message' => '11 usuarios de prueba creados con éxito'], 201);

    }


}