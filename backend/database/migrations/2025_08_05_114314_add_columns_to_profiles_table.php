<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Add columns only if they don't exist
            if (!Schema::hasColumn('profiles', 'socials')) {
                $table->json('socials')->nullable()->after('skills');
            }
            if (!Schema::hasColumn('profiles', 'phone')) {
                $table->string('phone')->nullable()->after('location');
            }
            if (!Schema::hasColumn('profiles', 'photo')) {
                $table->string('photo')->nullable()->after('phone');
            }
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Check for columns before dropping to avoid errors
            $columns = ['socials', 'phone', 'photo'];
            $existingColumns = array_filter($columns, fn($column) => Schema::hasColumn('profiles', $column));
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
}