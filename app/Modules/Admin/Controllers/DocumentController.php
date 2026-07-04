<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Libraries\FileUploader;
use App\Models\MemberApplicationModel;

class DocumentController extends BaseController
{
    public function serve(int $applicationId, string $docType)
    {
        if ($this->currentUserRole() !== 'super_admin') {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }
        $app = (new MemberApplicationModel())->find($applicationId);
        if (! $app) return $this->response->setStatusCode(404)->setBody('Not found');
        $fieldMap = ['nid_front'=>'nid_front_path','nid_back'=>'nid_back_path','photo'=>'photo_path','nok_nid_front'=>'nok_nid_front_path','nok_nid_back'=>'nok_nid_back_path'];
        $field = $fieldMap[$docType] ?? null;
        if (! $field || empty($app[$field])) return $this->response->setStatusCode(404)->setBody('Document not found');
        $path = (new FileUploader())->getAbsolutePath($app[$field]);
        if (! file_exists($path)) return $this->response->setStatusCode(404)->setBody('File missing');
        return $this->response
            ->setHeader('Content-Type', mime_content_type($path))
            ->setHeader('Content-Disposition','inline; filename="'.basename($path).'"')
            ->setHeader('Cache-Control','private, no-cache')
            ->setBody(file_get_contents($path));
    }
}
