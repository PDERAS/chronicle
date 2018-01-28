<?php

namespace CodyMoorhouse\Secretary\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/* Models */
use CodyMoorhouse\Secretary\Models\Comment;

/* Requests */
use CodyMoorhouse\Secretary\Requests\Comments\DestroyRequest;
use CodyMoorhouse\Secretary\Requests\Comments\StoreRequest;
use CodyMoorhouse\Secretary\Requests\Comments\UpdateRequest;

class CommentsController extends Controller
{
    /**
     * Destroy a comment in the system.
     *
     * @param CodyMoorhouse\Secretary\Models\Comment $comment
     * @param CodyMoorhouse\Secretary\Requests\Comments\DestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment, DestroyRequest $request)
    {
        try {
            return DB::transaction(function() use ($comment, $request) {
                $comment->delete();

                return Response::json([
                    'message'   =>  'Comment deleted successfully',
                ]);
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }

    /**
     * Store a new comment into a note.
     *
     * @param CodyMoorhouse\Secretary\Requests\Comments\StoreRequest $request
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
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }

    /**
     * Update a comment in the system.
     *
     * @param CodyMoorhouse\Secretary\Models\Comment $comment
     * @param CodyMoorhouse\Secretary\Requests\Comments\UpdateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Comment $comment, UpdateRequest $request)
    {
        try {
            return DB::transaction(function() use ($comment, $request) {
                $comment->update([
                    'description'   =>  $request->description
                ]);

                return Response::json([
                    'message'   =>  'Comment updated successfully',
                ]);
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }
}
