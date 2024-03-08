<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategories;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;


class ArticleController extends Controller
{
    public function getArticles(Request $request): Response
    {
        try {
            $articles = Article::all();
            return Response([
                'status' => true,
                'data' => $articles,
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function getArticleById(Request $request, string $art_id): Response
    {
        try {
            $article = Article::where('article_id', $art_id)->first();
            return Response([
                'status' => true,
                'data' => $article,
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createArticle(Request $request): Response
    {
        try {
            $validateArticle = Validator::make($request->all(), [
                'article_title' => 'required',
                'article_desc' => 'required',
                'article_img' => 'required',
                'category' => 'required|exists:article_categories,name'
            ]);
            if ($validateArticle->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateArticle->errors()
                ], 401);
            }
            $author = $request->user();
            $category = ArticleCategories::where('name', $request->category)->first();
            $time = new DateTime();
            $timenow = $time->format('Y-m-d H:i:s');
            $article = Article::create([
                'article_title' => $request->article_title,
                'article_desc' => $request->article_desc,
                'article_img' => $request->article_img,
                'published_at' => $timenow,
                'author_id' => $author->user_id,
                'category_id' => $category->article_category_id,
            ]);
            return Response([
                'status' => true,
                'message' => 'Article created successfully',
            ], 201);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function updateArticle(Request $request): Response
    {
        try {
            $validateArticle = Validator::make($request->all(), [
                'article_id' => 'required|exists:articles,article_id',
                'article_title' => 'required',
                'article_desc' => 'required',
                'article_img' => 'required',
            ]);
            if ($validateArticle->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateArticle->errors()
                ], 401);
            }
            $article = Article::where('article_id', $request->article_id)->first();
            $article->update($request->all());
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
    public function deleteArticle(Request $request): Response
    {
        try {
            $validateArticle = Validator::make($request->all(), [
                'article_id' => 'required|exists:articles,article_id',
            ]);
            if ($validateArticle->fails()) {
                return Response([
                    'message' => 'validation error',
                    'errors' => $validateArticle->errors()
                ], 401);
            }
            $article = Article::where('article_id', $request->article_id)->first();
            $article->delete();
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
