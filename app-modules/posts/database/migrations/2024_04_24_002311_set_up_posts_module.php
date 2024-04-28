<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Modules\Posts\Enums\StatusesEnum;

return new class() extends Migration {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('title');
            $table->text('content');
            $table->dateTime('publish_date')->nullable();
            $table->integer('status')->default(StatusesEnum::DRAFT->value);
            $table->unsignedBigInteger('telegram_key_id');
            $table->foreign('telegram_key_id')->references('id')->on('telegram_keys')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
