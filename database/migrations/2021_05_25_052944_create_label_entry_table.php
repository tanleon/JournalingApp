<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelEntryTable extends Migration
{
     /**
      * Run the migrations.
      *
      * @return void
      */
     public function up()
     {
          Schema::create('label_entry', function (Blueprint $table) {
               $table->id();

               $table->unsignedBigInteger("entry_id");
               $table->unsignedBigInteger("label_id");

               $table->foreign("entry_id")->references("id")->on("entries")->onDelete("cascade")->onUpdate("cascade");
               $table->foreign("label_id")->references("id")->on("labels")->onDelete("cascade")->onUpdate("cascade");

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
          Schema::dropIfExists('label_entry');
     }
}