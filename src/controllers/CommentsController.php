<?php

namespace CodyMoorhouse\Chronicle\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/* Models */
use CodyMoorhouse\Chronicle\Models\Comment;

/* Requests */
use CodyMoorhouse\Chronicle\Requests\Comments\DestroyRequest;
use CodyMoorhouse\Chronicle\Requests\Comments\StoreRequest;
use CodyMoorhouse\Chronicle\Requests\Comments\UpdateRequest;

class CommentsController extends Controller
{
    /**
     * Destroy a comment in the system.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Comments\DestroyRequest $request
     * @param int $comment_id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $reques, Comment $comment_id)
    {
        try {
            return DB::transaction(function() use ($request, $comment_id) {
                Comment::destroy($comment_id);

                return Response::json([
                    'message'   =>  'Comment deleted successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }

    /**
     * Store a new comment into a note.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Comments\StoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                Comment::create([
                    'description'   =>  $request->description,
                    'note_id'       =>  $request->note_id,
                    'user_id'       =>  Auth::id()
                ]);

                return Response::json([
                    'message'   =>  'Comment created successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }

    /**
     * Update a comment in the system.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Comments\UpdateRequest $request
     * @param int $comment_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Comment $comment_id)
    {
        try {
            return DB::transaction(function() use ($request, $comment_id) {
                $comment = Comment::find($comment_id);
                $comment->update([
                    'description'   =>  $request->description
                ]);

                return Response::json([
                    'message'   =>  'Comment updated successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }
}
