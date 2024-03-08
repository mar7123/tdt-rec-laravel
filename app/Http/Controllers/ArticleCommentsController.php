<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategories;
use App\Models\ArticleComments;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ArticleCommentsController extends Controller
{
    public function getArticleComments(Request $request, string $art_id): Response
    {
        try {
            $art_comments = Article::where("article_id", $art_id)->first()->comments()->get();
            return Response([
                'status' => true,
                'data' => $art_comments,
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createArticleComment(Request $request): Response
    {
        try {
            $validateArticleComms = Validator::make($request->all(), [
                'article_id' => 'required|exists:articles,article_id',
                'comment_desc' => 'required',
            ]);
            if ($validateArticleComms->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateArticleComms->errors()
                ], 401);
            }
            $article = Article::where('article_id', $request->article_id)->first();
            $user = $request->user();
            $time = new DateTime();
            $timenow = $time->format('Y-m-d H:i:s');
            $article = ArticleComments::create([
                'comment_desc' => $request->comment_desc,
                'published_at' => $timenow,
                'article_id' => $article->article_id,
                'author_id' => $user->user_id,
            ]);
            return Response([
                'status' => true,
                'message' => 'Article comment created successfully',
            ], 201);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function updateArticleComment(Request $request): Response
    {
        try {
            $validateArticleComms = Validator::make($request->all(), [
                'article_comment_id' => 'required|exists:article_comments,article_comment_id',
                'comment_desc' => 'required',
            ]);
            if ($validateArticleComms->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateArticleComms->errors()
                ], 401);
            }
            $comms = ArticleComments::where('article_comment_id', $request->article_comment_id)->first();
            $comms->update($request->all());
            return Response([
                'status' => true,
                'message' => 'article comment updated'
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function deleteArticleComment(Request $request): Response
    {
        try {
            $validateArticleComms = Validator::make($request->all(), [
                'article_comment_id' => 'required|exists:article_comments,article_comment_id',
            ]);
            if ($validateArticleComms->fails()) {
                return Response([
                    'message' => 'validation error',
                    'errors' => $validateArticleComms->errors()
                ], 401);
            }
            $articlecomms = ArticleComments::where('article_comment_id', $request->article_comment_id)->first();
            $articlecomms->delete();
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
