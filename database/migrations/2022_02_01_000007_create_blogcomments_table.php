<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reconstructed from code usage: the original database schema was not in the repository.
 */
class CreateBlogcommentsTable extends Migration
{
    public function up()
    {
        // Idempotent: an existing table (e.g. the production database) is left as it is.
        if (Schema::hasTable('blogcomments')) {
            return;
        }

        Schema::create('blogcomments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Approval')->nullable();
            $table->string('Approved')->nullable();
            $table->integer('BlogID')->nullable();
            $table->text('Comment')->nullable();
            $table->string('CommentDate')->nullable();
            $table->string('CommentEmail')->nullable();
            $table->string('NameOfCommenter')->nullable();
            $table->integer('blog_model_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogcomments');
    }
}
