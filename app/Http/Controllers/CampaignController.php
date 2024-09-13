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
            $campaigns = Campaign::query();

            if ($request->has('name')) {
                $campaigns->where('name', 'like', '%' . $request->input('name') . '%');
            }

            if($request->has('item')) {
                $campaigns->withWhereHas('necessary_items', function ($query) use ($request) {
                    $query->where('item_id', $request->input('item'));
                });
            }

            $campaigns = $campaigns->get();
            return CampaignResource::collection($campaigns);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }

    public function info(int $id)
    {
        try {
            $institution = Campaign::with(['addressess', 'necessary_items', 'institution'])->findOrFail($id);
            return new CampaignResource($institution);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Campanha não encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }
}
