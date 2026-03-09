<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHostsReservadosToRedesTable extends Migration
{
    public function up()
    {
        Schema::table('redes', function (Blueprint $table) {
            $table->json('hosts_reservados')->nullable()->after('usa_segmentos');
        });
    }

    public function down()
    {
        Schema::table('redes', function (Blueprint $table) {
            $table->dropColumn('hosts_reservados');
        });
    }
}