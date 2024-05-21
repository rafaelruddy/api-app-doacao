<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstitutionResource;
use App\Models\Institution;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function list(Request $request)
    {
        try {
            $institutions = Institution::where('status', 'active')->get();
            return InstitutionResource::collection($institutions);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno do servidor: ' . $e], 500);
        }
    }

    public function info(Request $request, int $id)
    {
        try {
            $institution = Institution::findOrFail($id);

            return new InstitutionResource($institution);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Instituição não encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno do servidor: ' . $e], 500);
        }
    }
}
