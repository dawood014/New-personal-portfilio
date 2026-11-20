<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
 Schema::create('contents',function(Blueprint $t){$t->id();$t->string('section');$t->string('key');$t->longText('value')->nullable();$t->timestamps();$t->unique(['section','key']);});
 Schema::create('portfolio_items',function(Blueprint $t){$t->id();$t->string('type')->index();$t->string('title');$t->string('subtitle')->nullable();$t->longText('description')->nullable();$t->string('image')->nullable();$t->string('url',2048)->nullable();$t->string('secondary_url',2048)->nullable();$t->text('meta')->nullable();$t->unsignedInteger('sort_order')->default(0);$t->boolean('is_active')->default(true);$t->boolean('is_featured')->default(false);$t->timestamps();});
 Schema::create('contact_messages',function(Blueprint $t){$t->id();$t->string('name');$t->string('email');$t->string('subject');$t->text('message');$t->boolean('is_read')->default(false);$t->timestamps();});
 }
 public function down():void {Schema::dropIfExists('contact_messages');Schema::dropIfExists('portfolio_items');Schema::dropIfExists('contents');}
};
