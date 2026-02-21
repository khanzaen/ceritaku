<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class FileController extends BaseController
{
    /**
     * Serve file dari writable/uploads folder
     * 
     * @param string $folder Folder (harus 'uploads')
     * @param string ...$segments Path segments untuk file (e.g., covers/image.jpg atau profiles/photo.jpg)
     */
    public function serve(string $folder, ...$segments): ResponseInterface
    {
        // Validasi bahwa folder awal adalah 'uploads'
        if ($folder !== 'uploads') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Gabungkan path segments
        $filePath = implode('/', $segments);
        
        // Sanitasi path untuk mencegah directory traversal
        $filePath = str_replace(['..', '\\'], ['', '/'], $filePath);
        
        // Path lengkap file: hanya dari public/uploads
        $fullPath = FCPATH . 'uploads/' . $filePath;
        if (!is_file($fullPath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Get mime type dari extension
        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
        ];
        $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';

        // Set headers untuk caching
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', filesize($fullPath));
        $this->response->setHeader('Cache-Control', 'public, max-age=31536000'); // 1 tahun
        $this->response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        // Output file
        $this->response->setBody(file_get_contents($fullPath));
        
        return $this->response;
    }
}
