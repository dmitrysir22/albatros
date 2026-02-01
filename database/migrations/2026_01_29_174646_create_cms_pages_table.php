<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(): void
{
    Schema::create('cms_pages', function (Blueprint $table) {
        $table->id();
        
        // Basic Info
        $table->string('title');
        $table->string('slug')->unique(); // For URL (e.g., 'about-us')
        $table->string('section')->default('General'); // To group pages (General, Legal, etc.)
        
        // Content
        $table->longText('content')->nullable(); // Main HTML content
        
        // SEO Fields (PrestaShop style)
        $table->string('meta_title')->nullable();
        $table->text('meta_description')->nullable();
        $table->string('meta_keywords')->nullable();
        
        // Advanced (Dynamic Scripts)
        $table->text('custom_css')->nullable(); // <style>...</style>
        $table->text('custom_js')->nullable();  // <script>...</script>
        
        // Status
        $table->boolean('is_active')->default(true);
        
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
        Schema::dropIfExists('cms_pages');
    }
}
