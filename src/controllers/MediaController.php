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

/* Requests */
use CodyMoorhouse\Chronicle\Requests\Media\DestroyRequest;
use CodyMoorhouse\Chronicle\Requests\Media\StoreRequest;
use CodyMoorhouse\Chronicle\Requests\Media\UpdateRequest;

class MediaController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(config('chronicle.middlewares.api'), [
           'except' => 'download'
        ]);
        $this->middleware(config('chronicle.middlewares.auth'), [
           'only' => 'download'
        ]);
    }

    /**
     * Destroy media in the system.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Media\DestroyRequest $request
     * @param int $media_id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, $media_id)
    {
        try {
            return DB::transaction(function() use ($request, $media_id) {
                Media::destroy($media_id);

                return Response::json([
                    'message'   =>  'Media deleted successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'media'  =>  [$e]
            ]);
        }
    }

    /**
     * Stores media into a note.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Media\StoreRequest $request
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
                Storage::disk(config('chronicle.disk'))->put($filename, File::get($file));

                return Response::json([
                    'message'   =>  'Media created successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'media'  =>  [$e]
            ]);
        }
    }

    /**
     * Update a media in the system.
     *
     * @param CodyMoorhouse\Chronicle\Requests\Media\UpdateRequest $request
     * @param int $media_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $media_id)
    {
        try {
            return DB::transaction(function() use ($request, $media_id) {
                $media = Media::find($media_id);
                $media->update([
                    'filename_original'   =>  $request->filename
                ]);

                return Response::json([
                    'message'   =>  'Media updated successfully',
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }

    /**
     * Downloads a media from the system.
     *
     * @param int $media_id
     *
     * @return \Illuminate\Http\Response
     */
    public function download($media_id)
    {
        try {
            return DB::transaction(function() use ($media_id) {
                $media = Media::find($media_id);

                $path = Storage::disk(config('chronicle.disk'))
                    ->getDriver()
                    ->getAdapter()
                    ->applyPathPrefix($media->getOriginal('filename'));

                return response()->file($path);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'comments'  =>  [$e]
            ]);
        }
    }
}
