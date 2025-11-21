<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table = "tags";
    protected $primaryKey = "id";
    protected $useAutoIncrement = true;
    protected $returnType = "array";
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ["name", "slug"];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = "datetime";
    protected $createdField = "created_at";
    protected $updatedField = "updated_at";


    // Validation
    protected $validationRules = [
        "name" => "required|min_length[2]|max_length[100]",
        "slug" => "required|alpha_dash|min_length[2]|max_length[100]",
    ];

    protected $validationMessages = [
        "name" => [
            "required" => "The name field is required.",
            "min_length" => "The name must be at least 2 characters long.",
            "max_length" => "The name cannot exceed 100 characters.",
        ],
        "slug" => [
            "required" => "The slug field is required.",
            "alpha_dash" =>
                "The slug can only contain letters, numbers, underscores, and dashes.",
            "min_length" => "The slug must be at least 2 characters long.",
            "max_length" => "The slug cannot exceed 100 characters.",
        ],
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

    public function getOrCreateByNames(array $names): array
    {
        $tags = [];
        foreach ($names as $name) {
            $name = trim($name);
            if ($name === "") {
                continue;
            }

            $slug = url_title(mb_strtolower($name), "-", true);

            $existing = $this->where("slug", $slug)->first();
            if ($existing) {
                $tags[] = $existing["id"];
                continue;
            }

            $id = $this->insert([
                "name" => $name,
                "slug" => $slug,
            ]);

            $tags[] = $id;
        }

        return $tags;
    }

    public function getTagsForPost(int $postId): array
    {
        return $this->select("tags.*")
            ->join("post_tags", "post_tags.tag_id = tags.id")
            ->where("post_tags.post_id", $postId)
            ->findAll();
    }
}
