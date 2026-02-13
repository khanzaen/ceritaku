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
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Gabungkan path segments
        $filePath = implode('/', $segments);
        
        // Sanitasi path untuk mencegah directory traversal
        $filePath = str_replace(['..', '\\'], ['', '/'], $filePath);
        
        // Path lengkap file: writable/uploads/{covers|profiles}/filename
        $fullPath = WRITEPATH . 'uploads/' . $filePath;
        
        // Cek apakah file ada
        if (!is_file($fullPath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);

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
