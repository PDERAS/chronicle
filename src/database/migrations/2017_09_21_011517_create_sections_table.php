<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sections_table = config('chronicle.sections_table');

        Schema::create($sections_table, function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('tag')->unique();
            $table->enum('access_type', ['PRIVATE', 'PUBLIC'])->default('PUBLIC');
            $table->enum('type', ['MEDIA', 'NOTE'])->default('NOTE');
            $table->boolean('is_attachments_allowed')->default(true);
            $table->boolean('is_comments_allowed')->default(true);
            $table->boolean('is_deleting_allowed')->default(true);
            $table->boolean('is_editing_allowed')->default(true);
            $table->boolean('is_tasks_allowed')->default(true);
            $table->boolean('is_approval_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sections_table = config('chronicle.sections_table');

        Schema::dropIfExists(config('chronicle.sections_table'));
    }
}
