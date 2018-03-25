<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $comments_table = config('secretary.comments_table');

        Schema::create($comments_table, function(Blueprint $table) {
            $notes_table    = config('secretary.notes_table');
            $users_table    = config('secretary.users_table');
            $users_table_id = config('secretary.users_table_id');

            $table->increments('id');
            $table->unsignedInteger('note_id');
            $table->unsignedInteger('user_id');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('note_id')
                ->references('id')
                ->on($notes_table);

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
        $comments_table = config('secretary.comments_table');

        Schema::dropIfExists($comments_table);
    }
}
