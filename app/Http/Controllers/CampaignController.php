<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    //
    public function list(Request $request)
    {
        try {
            $institutions = Campaign::get();
            return CampaignResource::collection($institutions);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno do servidor: ' . $e], 500);
        }
    }

    public function info(Request $request, int $id)
    {
        try {
            $institution = Campaign::with(['addressess', 'necessary_items'])->findOrFail($id);
            return new CampaignResource($institution);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Campanha não encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno do servidor: ' . $e], 500);
        }
    }
}
