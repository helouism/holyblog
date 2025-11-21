<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\TagModel;
use CodeIgniter\Controller;

class Home extends BaseController
{
    protected $postModel;
    protected $tagModel;

    public function __construct()
    {
        helper(["url", "text"]);
        $this->postModel = new PostModel();
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        $featured = $this->postModel->getFeatured(3);
        $latest = $this->postModel->getLatest(6);

        return view("home/index", [
            "featured" => $featured,
            "latest" => $latest,
            "title" => "Home",
            "meta_description" => "Read the latest articles from My Blog.",
            "og_type" => "website",
        ]);
    }

    public function show($slug)
    {
        $post = $this->postModel->findBySlug($slug);
        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tags = $this->tagModel->getTagsForPost($post["id"]);

        // Meta / OG
        $metaDescription =
            $post["meta_description"] ?:
            word_limiter(strip_tags($post["content"]), 25);
        $ogImage = !empty($post["image_cover"])
            ? base_url("uploads/covers/" . $post["image_cover"])
            : base_url("uploads/og-default.jpg"); // fallback

        return view("home/show", [
            "post" => $post,
            "tags" => $tags,
            "title" => $post["title"],
            "meta_description" => $metaDescription,
            "og_type" => "article",
            "og_image" => $ogImage,
        ]);
    }

    public function byTag(string $slug)
    {
        $tag = $this->tagModel->where("slug", $slug)->first();
        if (!$tag) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $posts = $this->postModel
            ->getPublishedWithTags() // already joins post_tags + tags
            ->where("post_tags.tag_id", $tag["id"])
            ->findAll();

        return view("home/index", [
            "featured" => [],
            "latest" => $posts,
            "activeTag" => $tag,
            "title" => "Posts tagged: " . $tag["name"],
            "meta_description" => "Articles tagged with " . $tag["name"],
        ]);
    }
}
