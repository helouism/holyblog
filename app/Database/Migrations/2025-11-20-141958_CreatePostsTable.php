<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
                "auto_increment" => true,
            ],

            "username" => [
                "type" => "VARCHAR",
                "constraint" => "30",
                "null" => false,
            ],
            "title" => [
                "type" => "VARCHAR",
                "constraint" => "255",
            ],
            "slug" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "unique" => true,
            ],
            "meta_description" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => false,
            ],
            "content" => [
                "type" => "TEXT",
            ],
            "image_cover" => [
                "type" => "VARCHAR",
                "constraint" => "255",
                "null" => true,
            ],
            "status" => [
                "type" => "ENUM",
                "constraint" => ["draft", "published"],
                "default" => "draft",
            ],
            "is_featured" => [
                "type" => "TINYINT",
                "constraint" => 1,
                "default" => 0,
            ],
            "created_at" => [
                "type" => "DATETIME",
                "null" => true,
            ],
            "updated_at" => [
                "type" => "DATETIME",
                "null" => true,
            ],

        ]);

        $this->forge->addKey("id", true);
        $this->forge->addForeignKey("username", "users", "username", "CASCADE", "CASCADE");

        $this->forge->createTable("posts");
    }

    public function down()
    {
        $this->forge->dropTable("posts");
    }
}
