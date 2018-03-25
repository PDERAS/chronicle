<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role_section_table = config('chronicle.role_section_table');

        Schema::create($role_section_table, function(Blueprint $table) {
            $roles_table    = config('chronicle.roles_table');
            $roles_table_id = config('chronicle.roles_table_id');
            $sections_table = config('chronicle.sections_table');

            $table->increments('id');
            $table->unsignedInteger('section_id');
            $table->string('title');
            $table->enum('access_type', ['INCLUDE', 'EXCLUDE'])->default('EXCLUDE');
            $table->timestamps();

            $table->foreign('section_id')
                ->references('id')
                ->on($sections_table);

            if (Schema::hasTable($roles_table)) {
                $table->unsignedInteger('role_id')->nullable();
                $table->foreign('role_id')
                    ->references($roles_table_id)
                    ->on($roles_table);
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
        $role_section_table = config('chronicle.role_section_table');

        Schema::dropIfExists($role_section_table);
    }
}
