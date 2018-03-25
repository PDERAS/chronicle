<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $notes_table    = config('secretary.notes_table');

        Schema::create($notes_table, function(Blueprint $table)
        {
            $companies_table    = config('secretary.companies_table');
            $companies_table_id = config('secretary.companies_table_id');
            $users_table        = config('secretary.users_table');
            $users_table_id     = config('secretary.users_table_id');

            $roles_table_id     = config('secretary.roles_table_id');
            $sections_table     = config('secretary.sections_table');

            $table->increments('id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('user_id');
            $table->string('section_ref_slug');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('section_id')
                ->references('id')
                ->on($sections_table);

            if (Schema::hasTable($users_table)) {
                $table->foreign('user_id')
                    ->references($users_table_id)
                    ->on($users_table);
            }

            if (Schema::hasTable($companies_table)) {
                $table->unsignedInteger('company_id')->nullable();
                $table->foreign('company_id')
                    ->references($companies_table_id)
                    ->on($companies_table);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $notes_table    = config('secretary.notes_table');

        Schema::dropIfExists($notes_table);
    }
}
