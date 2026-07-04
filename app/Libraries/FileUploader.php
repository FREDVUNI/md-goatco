<?php
declare(strict_types=1);
namespace App\Libraries;

class FileUploader
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR;
    }

    public function uploadApplicationDocs(array $files, int $userId): array
    {
        $paths = [];
        $map   = [
            'nid_front'     => 'nid',
            'nid_back'      => 'nid',
            'headshot'      => 'headshots',
            'nok_nid_front' => 'nok_nid',
            'nok_nid_back'  => 'nok_nid',
        ];
        $fieldMap = [
            'nid_front'     => 'nid_front_path',
            'nid_back'      => 'nid_back_path',
            'headshot'      => 'photo_path',
            'nok_nid_front' => 'nok_nid_front_path',
            'nok_nid_back'  => 'nok_nid_back_path',
        ];
        foreach ($files as $key => $file) {
            if (! $file || ! $file->isValid() || $file->hasMoved()) continue;
            $dir  = $this->basePath . ($map[$key] ?? 'other') . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
            if (! is_dir($dir)) mkdir($dir, 0755, true);
            $name = $key . '_' . time() . '.' . $file->getExtension();
            $file->move($dir, $name);
            $relativePath = ($map[$key] ?? 'other') . '/' . $userId . '/' . $name;
            $paths[$fieldMap[$key] ?? $key . '_path'] = $relativePath;
        }
        return $paths;
    }

    public function getAbsolutePath(string $relativePath): string
    {
        return $this->basePath . $relativePath;
    }
}
