<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        try {
            
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);


            if (!$request->file('image')->isValid()) {
                return response()->json(['error' => 'Error al subir la imagen'], 400);
            }

            

            $image = $request->file('image');
            $path = $image->store('images', 'public');

            $usuario = auth()->user();
            $usuario->foto_perfil = $path;
            $usuario->save();


            return response()->json(['path' => $path], 200);
        } 
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}