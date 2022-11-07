<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $media_table    = config('chronicle.media_table');

        Schema::create($media_table, function(Blueprint $table) {
            $notes_table    = config('chronicle.notes_table');
            $users_table    = config('chronicle.users_table');
            $users_table_id = config('chronicle.users_table_id');

            $table->increments('id');
            $table->unsignedInteger('note_id');
            $table->unsignedInteger('user_id');
            $table->string('filename');
            $table->string('filename_original');
            $table->string('file_mime');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('note_id')
                ->references('id')
                ->on($notes_table);

            if (Schema::hasTable($users_table)) {
                $table->foreign('user_id')
                    ->references('id')
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
        $media_table    = config('chronicle.media_table');

        Schema::dropIfExists($media_table);
    }
}
