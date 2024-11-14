<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\PostCommentResource;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function index($post) {
        try {
            $comments = Post::whereId($post)->with('comments')->first();
            return response()->json(['message' => 'Data Fetched' , 'data' => PostCommentResource::make($comments)], 200);
        }catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function store(CommentRequest $request, Post $post)
    {
        $content = htmlspecialchars(strip_tags($request->content) ,  ENT_QUOTES, 'UTF-8');
        $comment = new Comment([
            'content' => $content,
        ]);

        $comment->user()->associate($request->user());
        $comment->post()->associate($post);
        $comment->save();
        $comments = Post::whereId($comment->post_id)->with('comments')->first();
        return response()->json(['message' => 'Comment Added Successfully' , 'data' => PostCommentResource::make(($comments))], 200);
    }
}
