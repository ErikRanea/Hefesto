<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El campo correo es obligatorio.',
            'email.email' => 'El correo no tiene el formato correcto.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Datos de acceso incorrectos. Por favor, verifica tus credenciales.'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function unauthorized()
    {
        return redirect(route('login'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'min:2'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'email', 'unique:users,email'],
            'id_campus' => ['required', 'integer', 'exists:campus,id'], // Agregamos la validación para id_campus
            'primer_apellido' => ['nullable', 'string'],
            'segundo_apellido' => ['nullable', 'string'],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener un minimo :min caracteres.',
            'id_campus.required' => 'El campo campus es obligatorio.',
            'id_campus.integer' => 'El campo campus debe ser un número entero.',
            'id_campus.exists' => 'El campus seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $exists = User::where('email', htmlspecialchars($request->input('email')))->first();
        if (!$exists) {

            $new = new User();
            $new->name = $request->get('name');
            $request->get('primer_apellido') ? $new->primer_apellido = $request->get('primer_apellido') : $new->primer_apellido = "" ;
            $request->get('segundo_apellido') ? $new->segundo_apellido = $request->get('segundo_apellido') : $new->segundo_apellido = "" ;
            $new->email = $request->get('email');
            $new->password = Hash::make($request->get('password'));
            $new->rol = 'operario';
            $new->id_campus = $request->get('id_campus');
            $new->habilitado = 1;
    
            if($request->file('image') != null){
                ImageController::cargarImagen($request,$new);
            }
            $new->save();

            return response()->json($new, Response::HTTP_CREATED);
        }else{
            return response()->json(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_BAD_REQUEST);
        }
    }


    public function registerTecnico(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'min:2'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'email', 'unique:users,email'],  
            'id_campus' => ['required', 'integer', 'exists:campus,id'], // Agregamos la validación para id_campus
            'primer_apellido' => ['nullable', 'string'],
            'segundo_apellido' => ['nullable', 'string'],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener un minimo :min caracteres.',
            'id_campus.required' => 'El campo campus es obligatorio.',
            'id_campus.integer' => 'El campo campus debe ser un número entero.',
            'id_campus.exists' => 'El campus seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $exists = User::where('email', htmlspecialchars($request->input('email')))->first();
        if (!$exists) {

            $  $new = new User();
            $new->name = $request->get('name');
            $request->get('primer_apellido') ? $new->primer_apellido = $request->get('primer_apellido') : $new->primer_apellido = "" ;
            $request->get('segundo_apellido') ? $new->segundo_apellido = $request->get('segundo_apellido') : $new->segundo_apellido = "" ;
            $new->email = $request->get('email');
            $new->password = Hash::make($request->get('password'));
            $new->rol = 'operario';
            $new->habilitado = 1;
    
            if($request->file('image') != null){
                ImageController::cargarImagen($request,$new);
            }
            $new->save();

            return response()->json($new, Response::HTTP_CREATED);
        }else{
            return response()->json(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Valida si el token es válido
     */
    public function validateToken()
    {
        try {
            // Si el token es válido, auth()->user() no lanzará una excepción
            $user = auth()->user();
            return response()->json(['message' => 'Token válido','data' => auth()->user()], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al validar el token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function meData(){
        try {
            // Si el token es válido, auth()->user() no lanzará una excepción
            $user = auth()->user();
            return response()->json(['message' => 'Token válido','data' => auth()->user()->id], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al validar el token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        try {
            $token = JWTAuth::getToken(); // Obtiene el token actual
            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }
            // Invalida el token actual
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Sesión cerrada correctamente'], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::getToken(); // Obtiene el token actual
            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }
            $nuevo_token = auth()->refresh();
            // Invalida el token actual
            JWTAuth::invalidate($token);
            return $this->respondWithToken($nuevo_token);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,// El token en sí
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }
}