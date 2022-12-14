<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy(Comment $comment)
    {
        // if($comment->user_id != auth()->user()->id && auth()->user()->isNotAdmin()) {;
        if($comment->user_id != auth()->user()->isNotAdmin()) { 
            return redirect()
                ->route('user.comments')
                ->withMessage("Сэтгэгдэл устгах эрх байхгүй байна.");
        }

        $comment->delete();

        return redirect()
            ->route('user.comments')
            ->withMessage('Сэтгэгдэл амжилттай устлаа.');
    }
}