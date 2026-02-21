<?php

/**
 * File Helper
 * Helper functions untuk menangani file URLs
 *
 * Asumsi:
 * - Web root Laragon mengarah ke folder public/
 * - File upload disimpan di public/uploads/covers/ dan public/uploads/profiles/
 * - DB menyimpan path relatif seperti: 'covers/laut-bercerita.jpg'
 *   atau 'profiles/foto.jpg' (tanpa prefix 'uploads/')
 */

if (!function_exists('file_url')) {
    /**
     * Generate URL untuk file yang disimpan di public/uploads/
     *
     * @param string|null $path Path relatif dari file, contoh: 'covers/image.jpg'
     * @return string URL lengkap untuk mengakses file
     */
    function file_url(?string $path): string
    {
        if (empty($path)) {
            return base_url('assets/images/no-image.png');
        }

        // Jika sudah berupa URL lengkap, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Buang prefix yang salah jika ada (legacy / migrasi data lama)
        $path = ltrim($path, '/');
        $path = preg_replace('#^public/#', '', $path);
        $path = preg_replace('#^uploads/#', '', $path);

        // DB simpan: 'covers/laut-bercerita.jpg'
        // File ada di: public/uploads/covers/laut-bercerita.jpg
        // Web root = public/ → URL: /uploads/covers/laut-bercerita.jpg
        return base_url('uploads/' . $path);
    }
}

if (!function_exists('cover_url')) {
    /**
     * Generate URL untuk cover image
     *
     * @param string|null $coverPath Path relatif cover, contoh: 'covers/image.jpg'
     * @param string $default Default image jika cover kosong
     * @return string URL cover image
     */
    function cover_url(?string $coverPath, string $default = 'assets/images/no-cover.png'): string
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
     * @param string|null $photoPath Path relatif photo, contoh: 'profiles/foto.jpg'
     * @param string $default Default avatar jika photo kosong
     * @return string URL profile photo
     */
    function profile_url(?string $photoPath, string $default = 'assets/images/no-profile.png'): string
    {
        if (empty($photoPath)) {
            return base_url($default);
        }
        return file_url($photoPath);
    }
}