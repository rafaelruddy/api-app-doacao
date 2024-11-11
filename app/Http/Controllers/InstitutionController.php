<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstitutionResource;
use App\Models\Institution;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function list()
    {
        try {
            $institutions = Institution::with('address')->where('status', 'active')->get();
            return InstitutionResource::collection($institutions);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }

    public function info(int $id)
    {
        try {
            $institution = Institution::with([
                'campaigns' => function ($query) {
                    $query->withSum([
                        'donated_items as total_donated_items_quantity' => function ($query) {
                            $query->where('donations.status', 'concluded');
                        }
                    ], 'donated_items.quantity')
                        ->withSum([
                            'necessary_items as total_necessary_items_quantity'
                        ], 'quantity_objective');
                },
                'address'
            ])->findOrFail($id);
            return new InstitutionResource($institution);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Instituição não encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }
}
