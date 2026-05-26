<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'identificacion')) {
                $table->string('identificacion', 30)->nullable()->after('nombre');
            }

            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->text('direccion')->nullable()->after('email');
            }
        });

        $clientes = DB::table('clientes')->select('id', 'identificacion')->get();

        foreach ($clientes as $cliente) {
            if (empty($cliente->identificacion)) {
                DB::table('clientes')
                    ->where('id', $cliente->id)
                    ->update([
                        'identificacion' => 'CLI-' . str_pad($cliente->id, 4, '0', STR_PAD_LEFT)
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (Schema::hasColumn('clientes', 'direccion')) {
                $table->dropColumn('direccion');
            }

            if (Schema::hasColumn('clientes', 'identificacion')) {
                $table->dropColumn('identificacion');
            }
        });
    }
};