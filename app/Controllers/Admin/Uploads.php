<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Images\Exceptions\ImageException;

class Uploads extends BaseController
{
    protected $imageService;

    public function __construct()
    {
        $this->imageService = service("image");
    }

    public function cover()
    {
        $file = $this->request->getFile("image_cover");

        if (!$file) {
            $file = $this->request->getFile("file");
        }

        if (!$file || !$file->isValid()) {
            return $this->response
                ->setStatusCode(400)
                ->setBody("Invalid upload");
        }

        $allowedExt = ["jpg", "jpeg", "png", "gif", "webp"];
        $ext = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedExt, true)) {
            return $this->response
                ->setStatusCode(415)
                ->setBody("Invalid file type");
        }

        if ($file->getSize() > 3 * 1024 * 1024) {
            // 3 MB
            return $this->response
                ->setStatusCode(413)
                ->setBody("File too large");
        }

        // Destination folder
        $targetDir = FCPATH . "uploads/covers";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Generate a random name, but force .webp extension
        $randomName = $file->getRandomName(); // e.g. "abc123.jpg"
        $baseName = pathinfo($randomName, PATHINFO_FILENAME); // e.g. "abc123"
        $newName = $baseName . ".webp"; // e.g. "abc123.webp"
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $newName;

        $sourcePath = $file->getTempName(); // temp upload path

        try {
            $this->imageService
                ->withFile($sourcePath)
                ->convert(IMAGETYPE_WEBP)
                ->save($targetPath, 100); // quality 0–100, works for JPEG & WebP since CI 4.4.0
        } catch (ImageException $e) {
            log_message("error", "Cover upload failed: {msg}", [
                "msg" => $e->getMessage(),
            ]);
            return $this->response
                ->setStatusCode(500)
                ->setBody("Could not process image");
        }

        if (!is_file($targetPath)) {
            return $this->response
                ->setStatusCode(500)
                ->setBody("Could not save image");
        }

        // Return the *webp* filename as plain text, for FilePond to use as the field value
        return $this->response->setStatusCode(200)->setBody($newName);
    }
}
