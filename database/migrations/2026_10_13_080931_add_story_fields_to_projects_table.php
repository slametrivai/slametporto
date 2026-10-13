<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('role');
            $table->text('result')->nullable()->after('solution');
            $table->string('volume', 150)->nullable()->after('result');
            $table->json('components')->nullable()->after('workflow_steps');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['summary', 'result', 'volume', 'components']);
        });
    }
};
