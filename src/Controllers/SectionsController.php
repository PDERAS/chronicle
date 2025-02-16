<?php

namespace Pderas\Chronicle\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

/* Models */
use Pderas\Chronicle\Models\Section;

/* Requests */
use Pderas\Chronicle\Requests\Sections\IndexRequest;
use Pderas\Chronicle\Requests\Sections\NotesRequest;

class SectionsController extends Controller
{
    /**
     * Gets a section
     *
     * @param string $section_tag
     *
     * @return \Illuminate\Http\Response
     */
    public function get($section_tag)
    {
        try {
            return DB::transaction(function() use ($section_tag) {
                $section = Section::where('tag', $section_tag)->first();
                return Response::json([
                    'section'   =>  $section
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'sections'  =>  [$e]
            ]);
        }
    }

    /**
     * Get a listing of sections based on the $request input parameters.
     *
     * @param Pderas\Chronicle\Requests\Sections\IndexRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                $sections_table = config('chronicle.sections_table');
                $sections_query = Section::select('*');

                foreach ($request->all() as $key => $value) {
                    switch ($key) {
                        case 'tag':
                        case 'title':
                            $sections_query->where($key, 'LIKE', '%' . $value . '%');
                            break;
                        default:
                            if (Schema::hasColumn($sections_table, $key)) {
                                $sections_query->where($key, $value);
                            }
                            break;
                    }
                }

                $sections = $request->has('paginate') ? $sections_query->paginate($request->paginate) : $sections_query->get();

                return Response::json($sections);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'sections'  =>  [$e]
            ]);
        }
    }

    /**
     * Gets the notes for a section
     *
     * @param Pderas\Chronicle\Requests\Sections\NotesRequest $request
     * @param string $section_tag
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotes(NotesRequest $request, $section_tag)
    {
        try {
            return DB::transaction(function() use ($request, $section_tag) {
                $section = Section::where('tag', $section_tag)->first();
                $notes = $section->notes()
                    ->whereRaw("BINARY section_ref_slug = '$request->slug'")
                    ->orderBy('created_at', 'desc')
                    ->paginate(config('chronicle.paginate_amount'));

                foreach ($notes as $note) {
                    $note->user;
                }

                return Response::json([
                    'notes'   =>  $notes
                ]);
            }, config('chronicle.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'sections'  =>  [$e]
            ]);
        }
    }
}
