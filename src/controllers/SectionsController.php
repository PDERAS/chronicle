<?php

namespace CodyMoorhouse\Chronicle\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

/* Models */
use CodyMoorhouse\Chronicle\Models\Section;

/* Requests */
use CodyMoorhouse\Chronicle\Requests\Sections\IndexRequest;
use CodyMoorhouse\Chronicle\Requests\Sections\NotesRequest;

class SectionsController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('bindings');
        foreach (config('chronicle.middlewares.general') as $middleware) {
            $this->middleware($middleware);
        }
    }

    /**
     * Gets a section
     *
     * @param CodyMoorhouse\Chronicle\Models\Sections\Section $section
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Section $section)
    {
        try {
            return DB::transaction(function() use ($section) {
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
     * @param CodyMoorhouse\Chronicle\Requests\Sections\IndexRequest $request
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
     * @param CodyMoorhouse\Chronicle\Requests\Sections\NotesRequest $request
     * @param CodyMoorhouse\Chronicle\Models\Sections\Section $section
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotes(NotesRequest $request, Section $section)
    {
        try {
            return DB::transaction(function() use ($request, $section) {
                $notes = $section->notes()
                    ->where('section_ref_slug', $request->slug)
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
