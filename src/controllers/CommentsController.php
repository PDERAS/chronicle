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
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('bindings');
        foreach (config('chronicle.middlewares.auth') as $middleware) {
            $this->middleware($middleware);
        }
    }

    /**
     * Destroy a comment in the system.
     *
     * @param CodyMoorhouse\Chronicle\Models\Comment $comment
     * @param CodyMoorhouse\Chronicle\Requests\Comments\DestroyRequest $request
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
     * @param CodyMoorhouse\Chronicle\Models\Comment $comment
     * @param CodyMoorhouse\Chronicle\Requests\Comments\UpdateRequest $request
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
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }
}
