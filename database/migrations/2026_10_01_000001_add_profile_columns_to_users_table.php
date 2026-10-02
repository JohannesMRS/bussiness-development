<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('major', 100)->nullable();
            $table->string('business_name')->nullable();
            $table->text('business_description')->nullable();
            $table->string('avatar_url')->nullable();
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn([
                'whatsapp_number',
                'major',
                'business_name',
                'business_description',
                'avatar_url',
            ]);
        });
    }
};
