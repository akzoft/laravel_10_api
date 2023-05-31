<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\EditPostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //liste des articles
    public function index(Request $request)
    {

        $query = Post::query();

        //si search alors rechercher le caractere search dans le titre de tous les lignes
        $search = $request->input('search');
        if ($search) {
            $query->whereRaw("title LIKE '%" . $search . "%'");
        }

        //pagination
        $per_page = 2;
        $current_page = $request->input("page", 1); //page par defaut: (page:pour page entrée dynamiquement, 1:si la page n'est pas renseigner elle prendra la valeur 1)
        $total_post = $query->count(); //le total post changera de valeur en fonction de la recherche si elle existe

        $resultat = $query->offset(($current_page - 1) * $per_page)->limit($per_page)->get();

        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => "Liste des articles.",
            "current_page" => $current_page,
            "last_page" => ceil($total_post / $per_page),
            "data" =>  $resultat
        ]);
    }

    public function store(CreatePostRequest $request)
    {
        try {
            $post = new Post();

            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = auth()->user()->id;
            $post->save();

            return response()->json([
                'status' => 200,
                'success' => true,
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function update(EditPostRequest $request, Post $post)
    {
        try {
            $post->title = $request->title;
            $post->description = $request->description;

            if ($post->user_id === auth()->user()->id) {
                $post->save();
                return response()->json([
                    "status" => 201,
                    "success" => true,
                    "message" => "Article mis à jour.",
                    "data" => $post
                ]);
            } else {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Vous n'etes pas autorisé à mettre à jour cet article.",
                ]);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function delete(Post $post)
    {
        try {
            if ($post->user_id === auth()->user()->id) {
                $post->delete();
                return response()->json([
                    "status" => 201,
                    "success" => true,
                    "message" => "Article supprimé.",
                    "data" => $post
                ]);
            } else {
                return response()->json([
                    "status" => 401,
                    "success" => false,
                    "message" => "Vous n'etes pas autorisé à supprimer cet article.",
                ]);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
