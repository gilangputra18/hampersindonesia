<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // google_id is created directly in create_users_table migration
    }

    public function down(): void
    {
        //
    }
};
