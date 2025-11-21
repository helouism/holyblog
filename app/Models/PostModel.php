<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = "posts";
    protected $primaryKey = "id";
    protected $useAutoIncrement = true;
    protected $returnType = "array";
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        "user_id",
        "title",
        "slug",
        "meta_description",
        "content",
        "image_cover",
        "status",
        "is_featured",
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = "datetime";
    protected $createdField = "created_at";
    protected $updatedField = "updated_at";
    protected $deletedField = "deleted_at";

    // Validation
    protected $validationRules = [
        "title" => "required|min_length[3]|max_length[255]",
        "meta_description" => "required|min_length[10]|max_length[255]", // <— add this

        "status" => "required|in_list[draft,published]",
        "content" => "required",
    ];
    protected $validationMessages = [
        "title.required" => "Title is required",
        "title.min_length" => "Title must be at least 3 characters long",
        "title.max_length" => "Title cannot exceed 255 characters",
        "meta_description.required" => "Meta description is required",
        "meta_description.min_length" =>
            "Meta description must be at least 10 characters long",
        "meta_description.max_length" =>
            "Meta description cannot exceed 255 characters",


        "status.required" => "Status is required",
        "status.in_list" => "Status must be either 'draft' or 'published'",
        "content.required" => "Content is required",
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function withTags()
    {
        return $this->select(
            'posts.*, GROUP_CONCAT(tags.name SEPARATOR ", ") as tag_names',
        )
            ->join("post_tags", "post_tags.post_id = posts.id", "left")
            ->join("tags", "tags.id = post_tags.tag_id", "left")
            ->groupBy("posts.id");
    }

    public function getPublishedWithTags()
    {
        return $this->withTags()
            ->where("status", "published")
            ->orderBy("created_at", "DESC");
    }

    public function getFeatured($limit = 3)
    {
        return $this->getPublishedWithTags()
            ->where("is_featured", 1)
            ->limit($limit)
            ->find();
    }

    public function getLatest($limit = 6)
    {
        return $this->getPublishedWithTags()->limit($limit)->find();
    }

    public function findBySlug(string $slug)
    {
        return $this->getPublishedWithTags()
            ->where("posts.slug", $slug) // assuming $table = 'posts'
            ->first();
    }
}
