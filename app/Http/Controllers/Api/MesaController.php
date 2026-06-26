<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MesaRequest;
use App\Models\Mesa;
use Illuminate\Http\JsonResponse;

class MesaController extends Controller
{
    public function index(): JsonResponse
    {
        $mesas = Mesa::with(['latestFactura'])
            ->orderByRaw('LENGTH(nombre) ASC')
            ->orderBy('nombre', 'ASC')
            ->get();
        return response()->json($mesas);
    }

    public function store(MesaRequest $request): JsonResponse
    {
        $mesa = Mesa::create($request->validated());

        return response()->json($mesa, 201);
    }

    public function show(Mesa $mesa): JsonResponse
    {
        return response()->json($mesa);
    }

    public function update(MesaRequest $request, Mesa $mesa): JsonResponse
    {
        $mesa->update($request->validated());

        return response()->json($mesa);
    }

    public function destroy(Mesa $mesa): JsonResponse
    {
        $mesa->delete();

        return response()->json(null, 204);
    }
}
