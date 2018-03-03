<?php

namespace CodyMoorhouse\Secretary\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

/* Models */
use CodyMoorhouse\Secretary\Models\Section;

/* Requests */
use CodyMoorhouse\Secretary\Requests\Sections\IndexRequest;
use CodyMoorhouse\Secretary\Requests\Sections\NotesRequest;

class SectionsController extends Controller
{

    /**
     * Gets a section
     *
     * @param CodyMoorhouse\Secretary\Models\Sections\Section $section
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
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'sections'  =>  [$e]
            ]);
        }
    }

    /**
     * Get a listing of sections based on the $request input parameters.
     *
     * @param CodyMoorhouse\Secretary\Requests\Sections\IndexRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                $sections_table = config('secretary.sections_table');
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
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'sections'  =>  [$e]
            ]);
        }
    }

    /**
     * Gets the notes for a section
     *
     * @param CodyMoorhouse\Secretary\Requests\Sections\NotesRequest $request
     * @param CodyMoorhouse\Secretary\Models\Sections\Section $section
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotes(NotesRequest $request, Section $section)
    {
        try {
            return DB::transaction(function() use ($request, $section) {
                return Response::json([
                    'notes'   =>  $section->notes
                ]);
            }, config('secretary.db_attempts'));
        } catch (Exception $e) {
            return Response::json([
                'sections'  =>  [$e]
            ]);
        }
    }
}
