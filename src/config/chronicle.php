<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Previous Tables
    |--------------------------------------------------------------------------
    |
    | Chronicle is a note taking library that logs notes based on the logged in
    | user. As such, you can customize the tables and the used column fields here.
    |
    */
    /* Required */
    'users_table'           =>  'users',
    'users_table_id'        =>  'id',
    'users_table_name'      =>  'name',
    'users_model'           =>  'App\User',

    /* Optional */
    'companies_table'       =>  'companies',
    'companies_table_id'    =>  'id',
    'companies_table_name'  =>  'name',

    'roles_table'           =>  'roles',
    'roles_table_id'        =>  'id',

    /*
    |--------------------------------------------------------------------------
    | New Table Names
    |--------------------------------------------------------------------------
    |
    | These are the new tables that will be created through the chronicle
    | migrations.
    |
    */
    'sections_table'        =>  'sections',
    'role_section_table'    =>  'role_section',
    'notes_table'           =>  'notes',
    'comments_table'        =>  'comments',
    'media_table'           =>  'media',

    /*
    |--------------------------------------------------------------------------
    | Database Behaviours
    |--------------------------------------------------------------------------
    |
    | The amount of transaction attempts on the database
    |
    */
    'db_attempts'           =>  env('DB_ATTEMPTS', 5),
    'paginate_amount'       =>  5,

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | The storage disk where files will be saved to.
    |
    */
    'disk'                  =>  'local',
];
