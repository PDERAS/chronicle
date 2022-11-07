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
        $notes_table    = config('chronicle.notes_table');

        Schema::create($notes_table, function(Blueprint $table)
        {
            $users_table        = config('chronicle.users_table');
            $users_table_id     = config('chronicle.users_table_id');

            $sections_table     = config('chronicle.sections_table');

            $table->increments('id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('user_id');
            $table->string('section_ref_slug');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('section_id')
                ->references('id')
                ->on($sections_table)
                ->onDelete('cascade');

            if (Schema::hasTable($users_table)) {
                $table->foreign('user_id')
                    ->references($users_table_id)
                    ->on($users_table);
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
        $notes_table    = config('chronicle.notes_table');

        Schema::dropIfExists($notes_table);
    }
}
