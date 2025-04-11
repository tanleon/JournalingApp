<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
     /**
      * Run the migrations.
      *
      * @return void
      */
     public function up()
     {
          Schema::create('entries', function (Blueprint $table) {
               $table->id();
               $table->string("title");
               $table->text("body"); // Ensure this is not nullable
               $table->boolean("delete")->default(false);
               $table->unsignedBigInteger("emotion_id")->nullable();
               $table->unsignedBigInteger("user_id");

               $table->foreign("emotion_id")
                    ->references("id")
                    ->on("emotions")
                    ->onUpdate("cascade")
                    ->onDelete("set null");

               $table->foreign("user_id")
                    ->references("id")
                    ->on("users")
                    ->onUpdate("cascade")
                    ->onDelete("cascade");

               $table->timestamps();
          });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
          Schema::dropIfExists('entries');
     }
}