<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;    
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Producto::latest('created_at')->get());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['mensaje' => 'Producto no encontrado'], 404);
        }
        return response()->json($producto);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|unique:productos,nombre|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]); // por defecto mostrara errores 422 con el validate
        
        $producto = Producto::create($datos);
        return response()->json([
            'mensaje' => 'Producto creado exitosamente',
            'producto' => $producto
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['mensaje' => 'Producto no encontrado'], 404);
        }

        $datos = $request->validate([
            'nombre' => ['sometimes', 'string', Rule::unique('productos', 'nombre')->ignore($producto->id), 'max:255'],
            // esta de aqui arriba asegura que el nombre sea único, pero ignorando el producto actual
            // por si el usuario lo incluye, pero es opcional incluirlo, como se ve abajo
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        
        if (!$request->has('nombre')) {
            $datos['nombre'] = $producto->nombre;
        }
        $producto->update($datos);
        return response()->json([
            'mensaje' => 'Producto actualizado exitosamente',
            'producto' => $producto
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['mensaje' => 'Producto no encontrado'], 404);
        }
        $producto->delete();
        return response()->json(null, 204);
    }
}
