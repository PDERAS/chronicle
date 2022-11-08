<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTypeFromSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sections_table = config('chronicle.sections_table');

        Schema::table($sections_table, function(Blueprint $table) {
            $table->dropColumn('type');
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

        Schema::table($sections_table, function(Blueprint $table) {
            $table->enum('type', ['MEDIA', 'NOTE'])->default('NOTE')->after('access_type');
        });
    }
}
