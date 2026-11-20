<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::create('project_images',function(Blueprint $t){$t->id();$t->foreignId('portfolio_item_id')->constrained('portfolio_items')->cascadeOnDelete();$t->string('path');$t->timestamps();});}
 public function down():void {Schema::dropIfExists('project_images');}
};
