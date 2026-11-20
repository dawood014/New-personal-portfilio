<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void { Schema::create('admin_setup',function(Blueprint $table){$table->unsignedTinyInteger('id')->primary();$table->foreignId('user_id')->constrained('users')->restrictOnDelete();$table->timestamps();}); }
 public function down():void {Schema::dropIfExists('admin_setup');}
};
