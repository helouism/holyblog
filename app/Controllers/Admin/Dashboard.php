<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\TagModel;

class Dashboard extends BaseController
{
    protected $postModel;
    protected $tagModel;

    public function __construct()
    {
        helper(["url"]);
        $this->postModel = new PostModel();
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        // Basic stats
        $totalPosts = $this->postModel->countAll();
        $publishedPosts = $this->postModel
            ->where("status", "published")
            ->countAllResults();
        $draftPosts = $this->postModel
            ->where("status", "draft")
            ->countAllResults();

        // Recent posts
        $recentPosts = $this->postModel
            ->withTags()
            ->orderBy("created_at", "DESC")
            ->limit(5)
            ->find();

        return view("admin/dashboard", [
            "title" => "Dashboard",
            "totalPosts" => $totalPosts,
            "publishedPosts" => $publishedPosts,
            "draftPosts" => $draftPosts,
            "recentPosts" => $recentPosts,
        ]);
    }
}
