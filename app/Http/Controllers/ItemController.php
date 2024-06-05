<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    //
    public function list(Request $request)
    {
        try {
            $items = Item::where('status', 'active')->get();
            return ItemResource::collection($items);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }
}
