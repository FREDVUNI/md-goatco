<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * FileUploader
 *
 * Handles all file uploads for MD Goatco.
 *
 * IMPORTANT: Upload destination is OUTSIDE the webroot (writable/uploads/).
 * Files are served only through a controller that checks auth, never directly.
 *
 * Directory structure:
 *   writable/uploads/nid/          ← NID front + back (applicant)
 *   writable/uploads/headshots/    ← applicant passport photo
 *   writable/uploads/nok_nid/      ← next-of-kin NID scans
 */
class FileUploader
{
    private string $baseDir;
    private array  $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
    ];
    private int $maxSizeKb = 5120; // 5 MB

    public function __construct()
    {
        $this->baseDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR;
    }

    /**
     * Upload a single file to the given subdirectory.
     *
     * @param  UploadedFile $file        The uploaded file from the request
     * @param  string       $subfolder   One of: nid | headshots | nok_nid
     * @param  string       $prefix      Prefix for the filename (e.g. 'user_42_nid_front')
     * @return string                    Relative path stored in DB  e.g. 'nid/user_42_nid_front_abc123.jpg'
     *
     * @throws \RuntimeException on validation failure
     */
    public function upload(UploadedFile $file, string $subfolder, string $prefix): string
    {
        if (! $file->isValid()) {
            throw new \RuntimeException('File upload failed: ' . $file->getErrorString());
        }

        // MIME type check
        if (! in_array($file->getMimeType(), $this->allowedMimeTypes, true)) {
            throw new \RuntimeException(
                'Invalid file type. Only JPG, PNG, WebP and PDF files are allowed.'
            );
        }

        // Size check
        if ($file->getSizeByUnit('kb') > $this->maxSizeKb) {
            throw new \RuntimeException('File is too large. Maximum size is 5 MB.');
        }

        // Build destination
        $dest      = $this->baseDir . $subfolder . DIRECTORY_SEPARATOR;
        $extension = $file->getExtension();
        $filename  = $prefix . '_' . bin2hex(random_bytes(6)) . '.' . $extension;

        if (! is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        // Move file
        if (! $file->move($dest, $filename)) {
            throw new \RuntimeException('Could not save uploaded file. Check server permissions.');
        }

        return $subfolder . '/' . $filename;
    }

    /**
     * Upload all four application documents in one call.
     *
     * @param  array $files  Keyed: nid_front, nid_back, headshot, nok_nid_front, nok_nid_back
     * @param  int   $userId
     * @return array         Keyed paths for storing in DB
     */
    public function uploadApplicationDocs(array $files, int $userId): array
    {
        $prefix = 'user_' . $userId;
        $paths  = [];

        $map = [
            'nid_front'     => ['subfolder' => 'nid',       'prefix' => "{$prefix}_nid_front"],
            'nid_back'      => ['subfolder' => 'nid',       'prefix' => "{$prefix}_nid_back"],
            'headshot'      => ['subfolder' => 'headshots', 'prefix' => "{$prefix}_headshot"],
            'nok_nid_front' => ['subfolder' => 'nok_nid',  'prefix' => "{$prefix}_nok_nid_front"],
            'nok_nid_back'  => ['subfolder' => 'nok_nid',  'prefix' => "{$prefix}_nok_nid_back"],
        ];

        foreach ($map as $field => $config) {
            if (isset($files[$field]) && $files[$field] instanceof UploadedFile && $files[$field]->isValid()) {
                $paths[$field . '_path'] = $this->upload($files[$field], $config['subfolder'], $config['prefix']);
            }
        }

        return $paths;
    }

    /**
     * Delete a previously uploaded file.
     */
    public function delete(string $relativePath): void
    {
        $fullPath = $this->baseDir . $relativePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    /**
     * Return the absolute path for a stored file (for serving via controller).
     */
    public function getAbsolutePath(string $relativePath): string
    {
        return $this->baseDir . $relativePath;
    }

    /**
     * Check whether a stored file exists.
     */
    public function exists(string $relativePath): bool
    {
        return file_exists($this->baseDir . $relativePath);
    }
}
