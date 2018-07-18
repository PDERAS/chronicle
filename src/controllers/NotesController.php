<?php

namespace CodyMoorhouse\Chronicle\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/* Models */
use CodyMoorhouse\Chronicle\Models\Media;
use CodyMoorhouse\Chronicle\Models\Note;
use CodyMoorhouse\Chronicle\Models\Section;

/* Requests */
use CodyMoorhouse\Chronicle\Requests\Notes\DestroyRequest;
use CodyMoorhouse\Chronicle\Requests\Notes\MediaRequest;
use CodyMoorhouse\Chronicle\Requests\Notes\StoreRequest;
use CodyMoorhouse\Chronicle\Requests\Notes\UpdateRequest;

class NotesController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(config('chronicle.middlewares.api'));
    }

    /**
     * Destroy a note in the system.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Media\DestroyRequest $request
     * @param int $note_id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, $note_id)
    {
        try {
            return DB::transaction(function() use ($request, $note_id) {
                $note = Note::find($note_id);

                /* Delete note comments */
                foreach ($note->comments as $comment) {
                    $comment->delete();
                }

                /* Delete note media */
                foreach ($note->media as $media) {
                    $media->delete();
                }

                $note->delete();

                return Response::json([
                    'message'   =>  'Note deleted successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'notes'  =>  [$e]
            ]);
        }
    }

    /**
     * Store a new note into a section.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Notes\StoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                $section = Section::where('tag', $request->section_tag)->first();
                $note = Note::create([
                    'description'       =>  $request->description,
                    'section_id'        =>  $section->id,
                    'section_ref_slug'  =>  $request->section_ref,
                    'user_id'           =>  Auth::id()
                ]);

                if ($section->is_attachments_allowed && $request->has('files')) {
                    foreach ($request->file('files') as $file) {
                        $filename = md5($file->getClientOriginalName() . microtime()) . "." . ($file->clientExtension());
                        Media::create([
                            'filename'          =>  $filename,
                            'filename_original' =>  $file->getClientOriginalName(),
                            'file_mime'         =>  $file->getMimeType(),
                            'note_id'           =>  $note->id,
                            'user_id'           =>  Auth::id()
                        ]);

                        /* Save file to disk */
                        Storage::disk(config('chronicle.disk'))->put($filename, File::get($file));
                    }
                }

                return Response::json([
                    'message'   =>  'Note created successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'notes'  =>  [$e]
            ]);
        }
    }

    /**
     * Update a note in the system.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Notes\UpdateRequest $request
     * @param int $note_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $note_id)
    {
        try {
            return DB::transaction(function() use ($request, $note_id) {
                $note = Note::find($note_id);
                $note->update([
                    'description'   =>  $request->description
                ]);

                return Response::json([
                    'message'   =>  'Note updated successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'notes'  =>  [$e]
            ]);
        }
    }

    /**
     * Gets the media for a note.
     *
     * @param int $note_id
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedia($note_id)
    {
        try {
            return DB::transaction(function() use ($note_id) {
                $note = Note::find($note_id);
                $media = $note->media()
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('chronicle.paginate_amount'));

                return Response::json([
                    'files'   =>  $media
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'notes'  =>  [$e]
            ]);
        }
    }
}
