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

            if ($request->has('item')) {
                $campaigns->withWhereHas('necessary_items', function ($query) use ($request) {
                    $query->where('item_id', $request->input('item'));
                });
            }

            $campaigns = $campaigns
                            ->withSum([
                                'donated_items as total_donated_items_quantity' => function ($query) {
                                    $query->where('donations.status', 'concluded');
                                }
                            ], 'donated_items.quantity')
                            ->withSum([
                                'necessary_items as total_necessary_items_quantity'
                            ], 'quantity_objective')
                            ->get();
            return CampaignResource::collection($campaigns);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }

    public function info(int $id)
    {
        try {
            $campaign = Campaign::with([
                'addressess',
                'necessary_items' => function ($query) {
                    $query->with(
                        [
                            'item' => function ($query) {
                                $query->withSum([
                                    'donations as donated_total' => function ($query) {
                                        $query->where('donations.status', 'concluded');
                                    }
                                ], 'donated_items.quantity');
                            }
                        ]
                    );
                },
                'necessary_items.campaign',
                'institution'
            ])
                ->withSum([
                    'donated_items as total_donated_items_quantity' => function ($query) {
                        $query->where('donations.status', 'concluded');
                    }
                ], 'donated_items.quantity')
                ->withSum([
                    'necessary_items as total_necessary_items_quantity'
                ], 'quantity_objective')
                ->findOrFail($id);
            return new CampaignResource($campaign);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Campanha não encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }
}
