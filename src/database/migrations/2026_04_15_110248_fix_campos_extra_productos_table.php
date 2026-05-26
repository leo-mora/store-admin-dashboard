<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'marca')) {
                $table->string('marca', 100)->default('Sin marca')->after('nombre');
            }

            if (!Schema::hasColumn('productos', 'sku')) {
                $table->string('sku', 50)->nullable()->after('marca');
            }

            if (!Schema::hasColumn('productos', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('sku');
            }
        });

        $productos = DB::table('productos')->select('id', 'sku')->get();

        foreach ($productos as $producto) {
            if (empty($producto->sku)) {
                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update([
                        'sku' => 'PROD-' . str_pad($producto->id, 4, '0', STR_PAD_LEFT)
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'descripcion')) {
                $table->dropColumn('descripcion');
            }

            if (Schema::hasColumn('productos', 'sku')) {
                $table->dropColumn('sku');
            }

            if (Schema::hasColumn('productos', 'marca')) {
                $table->dropColumn('marca');
            }
        });
    }
};  