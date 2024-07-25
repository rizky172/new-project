<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meta', function (Blueprint $table) {
            $table->index(['fk_id', 'table_name', 'key'], 'fk_key_index');

            $table->index('log_category_id');
            $table->foreign('log_category_id')
            ->references('id')->on('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meta', function (Blueprint $table) {
            $table->dropIndex('fk_key_index');

            $table->dropForeign(['log_category_id']);
            $table->dropIndex(['log_category_id']);
        });
    }
}
