<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Uploads extends BaseController
{
    public function cover()
    {
        $file = $this->request->getFile('image_cover'); // FilePond sends field name "file" by default

        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setBody('Invalid upload');
        }

        // Basic validation
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedExt, true)) {
            return $this->response->setStatusCode(415)->setBody('Invalid file type');
        }

        if ($file->getSize() > 3 * 1024 * 1024) { // 3 MB
            return $this->response->setStatusCode(413)->setBody('File too large');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/covers', $newName);

        if (!$file->hasMoved()) {
            return $this->response->setStatusCode(500)->setBody('Could not save file');
        }

        // Return the filename as plain text; FilePond will store it
        return $this->response->setStatusCode(200)->setBody($newName);
    }
}
