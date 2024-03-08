<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategories;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ArticleCategoriesController extends Controller
{
    public function getArticleCategories(Request $request): Response
    {
        try {
            $articles_cat = ArticleCategories::all();
            return Response([
                'status' => true,
                'data' => $articles_cat,
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createArticleCategory(Request $request): Response
    {
        try {
            $validateArticleCat = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validateArticleCat->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateArticleCat->errors()
                ], 401);
            }
            $article_cat = ArticleCategories::create([
                'name' => $request->name,
            ]);
            return Response([
                'status' => true,
                'message' => 'Article Categories created successfully',
            ], 201);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function updateArticleCategory(Request $request): Response
    {
        try {
            $validateArticleCat = Validator::make($request->all(), [
                'article_category_id' => 'required|exists:article_categories,article_category_id',
                'name' => 'required',
            ]);
            if ($validateArticleCat->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateArticleCat->errors()
                ], 401);
            }
            $article_cat = ArticleCategories::where('article_category_id', $request->article_category_id)->first();
            $article_cat->update($request->all());
            return Response([
                'status' => true,
                'message' => 'article updated'
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function deleteArticleCategory(Request $request): Response
    {
        try {
            $validateArticleCat = Validator::make($request->all(), [
                'article_category_id' => 'required|exists:article_categories,article_category_id',
            ]);
            if ($validateArticleCat->fails()) {
                return Response([
                    'message' => 'validation error',
                    'errors' => $validateArticleCat->errors()
                ], 401);
            }
            $article_cat = ArticleCategories::where('article_category_id', $request->article_category_id)->first();
            $article_cat->delete();
            return Response([
                'status' => true,
                'message' => 'deleted successfully'
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
