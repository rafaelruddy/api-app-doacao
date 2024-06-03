<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function list(Request $request)
    {
        try {
            $news = News::get();
            return NewsResource::collection($news);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }

    public function info(Request $request, int $id)
    {
        try {
            $institution = News::findOrFail($id);
            return new NewsResource($institution);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notícia não encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }
}
