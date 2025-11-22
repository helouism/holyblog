<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\TagModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Posts extends BaseController
{
    protected $postModel;
    protected $tagModel;

    public function __construct()
    {
        helper(["form", "url", "text", "session"]);
        $this->postModel = new PostModel();
        $this->tagModel = new TagModel();

    }

    public function index()
    {
        $username = auth()->user()->username;
        $page = (int) ($this->request->getGet("page") ?? 1);
        $perPage = 10;
        $q = trim((string) $this->request->getGet("q"));

        $builder = $this->postModel->withTags()->orderBy("created_at", "DESC")->where('posts.username', $username);

        if ($q !== "") {
            $builder = $builder
                ->groupStart()
                ->like("posts.title", $q)
                ->orLike("posts.content", $q)
                ->orLike("tags.name", $q)
                ->groupEnd();

        }

        $posts = $builder->paginate($perPage, "default", $page);
        $pager = $this->postModel->pager;

        $data = [
            "posts" => $posts,
            "pager" => $pager,
            "q" => $q,
            "title" => "Posts",
        ];

        if ($this->request->isHtmx()) {
            // only the table for htmx
            return view("admin/posts/_table", $data);
        }

        // full page for normal request
        return view("admin/posts/index", $data);
    }

    public function create()
    {
        return view("admin/posts/create", [
            "validation" => service('validation'),
        ]);
    }

    public function store()
    {
        $rules = $this->postModel->getValidationRules();
        $rules['tags'] = 'permit_empty|string';
        $rules['slug'] = "required|alpha_dash|min_length[3]|max_length[255]|is_unique[posts.slug]";

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Please check the form.')
                ->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();

        $slug = url_title(mb_strtolower($postData['slug'] ?? $postData['title']), '-', true);

        // Handle image cover (FilePond async OR classic upload)
        $imageName = null;

        // FilePond async: field holds filename string
        $pondValue = $this->request->getPost('image_cover');
        if (!empty($pondValue) && !is_array($pondValue)) {
            $imageName = $pondValue;
        } else {
            // Fallback: classic file upload (no JS / no FilePond)
            $imageFile = $this->request->getFile('image_cover');
            if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
                $imageName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . 'uploads/covers', $imageName);
            }
        }

        $id = $this->postModel->insert([
            'username' => auth()->user()->username,
            'title' => $postData['title'],
            'slug' => $slug,
            'meta_description' => $postData['meta_description'],
            'content' => $postData['content'],
            'image_cover' => $imageName,
            'status' => $postData['status'] ?? 'draft',
            'is_featured' => (int) ($postData['is_featured'] ?? 0),
        ]);

        // Tags: comma separated
        $tagNames = array_filter(
            array_map("trim", explode(",", $postData["tags"] ?? "")),
        );
        if (!empty($tagNames)) {
            $tagIds = $this->tagModel->getOrCreateByNames($tagNames);
            $this->syncPostTags($id, $tagIds);
        }

        return redirect()
            ->to(route_to("admin.posts.index"))
            ->with("success", "Post created successfully.");
    }

    public function edit($id)
    {
        $post = $this->postModel->find($id);
        if (!$post) {
            throw PageNotFoundException::forPageNotFound();
        }

        $tags = $this->tagModel->getTagsForPost($id);
        $post["tags_string"] = implode(", ", array_column($tags, "name"));

        return view("admin/posts/edit", [
            "post" => $post,
            "validation" => service('validation'),
        ]);
    }

    public function update($id)
    {
        $post = $this->postModel->find($id);
        if (!$post) {
            throw PageNotFoundException::forPageNotFound();
        }



        $rules = $this->postModel->getValidationRules();
        $rules['tags'] = 'permit_empty|string';
        $rules['slug'] = "required|alpha_dash|min_length[3]|max_length[255]|is_unique[posts.slug,id,{$id}]";

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Please check the form.')
                ->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        $slug = url_title(mb_strtolower($postData['slug'] ?? $postData['title']), '-', true);

        $imageName = $post['image_cover'];

        // FilePond async value (new upload)
        $pondValue = $this->request->getPost('image_cover');
        if (!empty($pondValue) && !is_array($pondValue)) {
            $imageName = $pondValue;
        } else {
            // Fallback classic upload
            $imageFile = $this->request->getFile('image_cover');
            if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
                $imageName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . 'uploads/covers', $imageName);
            }
        }

        $this->postModel->update($id, [
            'title' => $postData['title'],
            'slug' => $slug,
            'meta_description' => $postData['meta_description'],
            'content' => $postData['content'],
            'image_cover' => $imageName,
            'status' => $postData['status'] ?? 'draft',
            'is_featured' => (int) ($postData['is_featured'] ?? 0),
        ]);


        $tagNames = array_filter(
            array_map("trim", explode(",", $postData["tags"] ?? "")),
        );
        $tagIds = [];
        if (!empty($tagNames)) {
            $tagIds = $this->tagModel->getOrCreateByNames($tagNames);
        }
        $this->syncPostTags($id, $tagIds);

        return redirect()
            ->to(route_to("admin.posts.index"))
            ->with("success", "Post updated successfully.");
    }

    public function delete($id)
    {
        // Get the post first so we know what file to delete
        $post = $this->postModel->find($id);

        if (!$post) {
            // If it doesn't exist, just behave as "deleted"
            if ($this->request->isHtmx()) {
                return $this->index();
            }

            return redirect()
                ->to(route_to('admin.posts.index'))
                ->with('success', 'Post already removed.');
        }

        // Soft delete the row (your model uses useSoftDeletes = true)
        $this->postModel->delete($id);

        // Remove the cover image from disk, if any
        if (!empty($post['image_cover'])) {
            $path = FCPATH . 'uploads/covers/' . $post['image_cover'];

            if (is_file($path)) {
                @unlink($path); // suppress warning if file already gone
            }
        }

        // HTMX request: re-render the table only
        if ($this->request->isHtmx()) {
            return $this->index();
        }

        // Normal request: redirect back with flash message
        return redirect()
            ->to(route_to('admin.posts.index'))
            ->with('success', 'Post deleted.');
    }


    public function toggleStatus($id)
    {
        $post = $this->postModel->find($id);
        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $newStatus = $post["status"] === "published" ? "draft" : "published";

        $this->postModel->update($id, ["status" => $newStatus]);

        if ($this->request->isHtmx()) {
            // re-render table only
            return $this->index();
        }

        return redirect()->back()->with("success", "Status updated.");
    }

    protected function syncPostTags(int $postId, array $tagIds)
    {
        $db = db_connect();
        $db->table("post_tags")->where("post_id", $postId)->delete();

        if (empty($tagIds)) {
            return;
        }

        $rows = [];
        foreach ($tagIds as $tagId) {
            $rows[] = [
                "post_id" => $postId,
                "tag_id" => $tagId,
            ];
        }

        $db->table("post_tags")->insertBatch($rows);
    }
}
