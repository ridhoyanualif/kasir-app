<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id_member');
            $table->string('name', 255);
            $table->string('telephone', 15);
            $table->integer('point')->default(0);
            $table->enum('status', ['active', 'non-active'])->default('active');
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
};
