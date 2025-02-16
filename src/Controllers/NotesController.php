<?php

namespace Pderas\Chronicle\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/* Models */
use Pderas\Chronicle\Models\Media;
use Pderas\Chronicle\Models\Note;
use Pderas\Chronicle\Models\Section;

/* Requests */
use Pderas\Chronicle\Requests\Notes\DestroyRequest;
use Pderas\Chronicle\Requests\Notes\MediaRequest;
use Pderas\Chronicle\Requests\Notes\StoreRequest;
use Pderas\Chronicle\Requests\Notes\UpdateRequest;
use Pderas\Chronicle\Requests\Notes\GetMediaRequest;

class NotesController extends Controller
{
    /**
     * Destroy a note in the system.
     *
     * @param Pderas\Chronicle\Requests\Media\DestroyRequest $request
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
     * @param Pderas\Chronicle\Requests\Notes\StoreRequest $request
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
     * @param Pderas\Chronicle\Requests\Notes\UpdateRequest $request
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
     * @param Pderas\Chronicle\Requests\Notes\GetMediaRequest $request
     * @param int $note_id
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedia(GetMediaRequest $request, $note_id)
    {
        try {
            return DB::transaction(function() use ($note_id, $request) {
                $note = Note::find($note_id);

                $media = $note->media()
                    ->orderBy('created_at', 'desc')
                    ->paginate($request->input('per_page', config('chronicle.paginate_amount')));

                if ($request->input('encoded', false)) {
                    collect($media->items())
                        ->each
                        ->setAppends(['encoded']);
                }

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
