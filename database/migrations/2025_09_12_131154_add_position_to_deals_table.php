<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->after('stage_id');
        });
        
        DB::transaction(function () {
            $stageIds = DB::table('stages')->pluck('id');

            foreach ($stageIds as $stageId) {
                $ids = DB::table('deals')
                    ->where('stage_id', $stageId)
                    ->orderBy('updated_at')      
                    ->pluck('id');               

                $i = 0;                         
                foreach ($ids as $id) {
                    DB::table('deals')
                        ->where('id', $id)
                        ->update(['position' => $i++]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
