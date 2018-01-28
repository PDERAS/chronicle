<?php

namespace CodyMoorhouse\Secretary\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/* Models */
use CodyMoorhouse\Secretary\Models\Media;

/* Requests */
use CodyMoorhouse\Secretary\Requests\Media\DestroyRequest;
use CodyMoorhouse\Secretary\Requests\Media\StoreRequest;
use CodyMoorhouse\Secretary\Requests\Media\UpdateRequest;

class MediaController extends Controller
{
    /**
     * Destroy media in the system.
     *
     * @param CodyMoorhouse\Secretary\Models\Media $media
     * @param CodyMoorhouse\Secretary\Requests\Media\DestroyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media, DestroyRequest $request)
    {
        try {
            return DB::transaction(function() use ($media, $request) {
                $media->delete();

                return Response::json([
                    'message'   =>  'Media deleted successfully',
                ]);
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'media'  =>  [$e]
            ]);
        }
    }

    /**
     * Stores media into a note.
     *
     * @param CodyMoorhouse\Secretary\Requests\Media\StoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                $file = $request->file('file');
                $filename = md5($file->getClientOriginalName() . microtime()) . "." . ($file->clientExtension());
                Media::create([
                    'filename'          =>  $filename,
                    'filename_original' =>  $file->getClientOriginalName(),
                    'file_mime'         =>  $file->getMimeType(),
                    'note_id'           =>  $request->note_id,
                    'user_id'           =>  Auth::id()
                ]);

                /* Save file to disk */
                Storage::disk(config('secretary.disk'))->put($filename, File::get($file));

                return Response::json([
                    'message'   =>  'Media created successfully',
                ]);
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'media'  =>  [$e]
            ]);
        }
    }

    /**
     * Update a media in the system.
     *
     * @param CodyMoorhouse\Secretary\Models\Media $media
     * @param CodyMoorhouse\Secretary\Requests\Media\UpdateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Media $media, UpdateRequest $request)
    {
        try {
            return DB::transaction(function() use ($media, $request) {
                $media->update([
                    'filename_original'   =>  $request->filename
                ]);

                return Response::json([
                    'message'   =>  'Media updated successfully',
                ]);
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }
}
