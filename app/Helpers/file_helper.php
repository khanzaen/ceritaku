<?php

/**
 * File Helper
 * Helper functions untuk menangani file URLs
 */

if (!function_exists('file_url')) {
    /**
     * Generate URL untuk file yang disimpan di writable/uploads folder
     * 
     * @param string $path Path relatif dari file (contoh: 'covers/image.jpg' atau 'uploads/profiles/photo.jpg')
     * @return string URL lengkap untuk mengakses file
     */
    function file_url(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // Jika path sudah berupa URL lengkap, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Strip "uploads/" prefix jika ada (untuk path yang sudah punya prefix)
        if (strpos($path, 'uploads/') === 0) {
            $path = substr($path, 8); // Remove "uploads/" prefix
        }

        // Normalize path: jika dimulai dengan 'covers/', tambahkan 'uploads/' prefix
        // Karena struktur fisik: writable/uploads/covers/
        if (strpos($path, 'covers/') === 0) {
            $path = 'uploads/' . $path;
        } elseif (strpos($path, 'profiles/') === 0) {
            // Untuk profiles juga, tambahkan uploads/ prefix
            $path = 'uploads/' . $path;
        } elseif (strpos($path, 'uploads/') !== 0) {
            // Jika belum ada prefix apapun, tambahkan 'uploads/' 
            $path = 'uploads/' . $path;
        }

        // Generate URL menggunakan route file controller
        // URL format: /files/uploads/covers/image.jpg atau /files/uploads/profiles/photo.jpg
        return base_url("files/{$path}");
    }
}

if (!function_exists('cover_url')) {
    /**
     * Generate URL untuk cover image
     * 
     * @param string|null $coverPath Path relatif cover (contoh: 'covers/image.jpg')
     * @param string $default Default image jika cover kosong
     * @return string URL cover image
     */
    function cover_url(?string $coverPath, string $default = '/assets/images/default-cover.jpg'): string
    {
        if (empty($coverPath)) {
            return base_url($default);
        }

        return file_url($coverPath);
    }
}

if (!function_exists('profile_url')) {
    /**
     * Generate URL untuk profile photo
     * 
     * @param string|null $photoPath Path relatif photo (contoh: 'uploads/profiles/photo.jpg')
     * @param string $default Default avatar jika photo kosong
     * @return string URL profile photo
     */
    function profile_url(?string $photoPath, string $default = '/assets/images/default-avatar.png'): string
    {
        if (empty($photoPath)) {
            return base_url($default);
        }

        return file_url($photoPath);
    }
}
