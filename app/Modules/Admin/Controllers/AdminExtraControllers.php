<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\UserModel;
use App\Models\MemberApplicationModel;

// ══════════════════════════════════════════════════════════════════════════════
// ADMIN HERD CONTROLLER
// Full herd view + add/edit animals. Extends manager capabilities.
// ══════════════════════════════════════════════════════════════════════════════

class HerdController extends BaseController
{
    private GoatModel $goats;

    public function __construct()
    {
        $this->goats = new GoatModel();
    }

    public function index(): string
    {
        return $this->dashboardView('admin/herd', [
            'pageTitle' => 'Herd Overview',
            'herd'      => $this->goats->getFullHerd(),
            'stats'     => $this->goats->getStats(),
        ]);
    }

    public function show(int $id): string
    {
        $goat = $this->goats->find($id);
        if (! $goat) {
            return redirect()->to('/admin/herd')->with('error', 'Animal not found.');
        }

        $userModel = new UserModel();
        return $this->dashboardView('admin/goat_detail', [
            'pageTitle' => $goat['name'] . ' (' . $goat['tag_number'] . ')',
            'goat'      => $goat,
            'members'   => $userModel->getByRole('member'),
        ]);
    }

    public function create(): string
    {
        $userModel = new UserModel();
        return $this->dashboardView('admin/goat_form', [
            'pageTitle' => 'Add Animal to Herd',
            'members'   => $userModel->getByRole('member'),
        ]);
    }

    public function store()
    {
        $rules = [
            'tag_number' => 'required|max_length[50]|is_unique[goats.tag_number]',
            'name'       => 'required|max_length[100]',
            'sex'        => 'required|in_list[male,female]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->goats->insert([
            'tag_number' => strtoupper(trim($this->request->getPost('tag_number'))),
            'name'       => $this->request->getPost('name'),
            'breed'      => $this->request->getPost('breed'),
            'sex'        => $this->request->getPost('sex'),
            'dob'        => $this->request->getPost('dob') ?: null,
            'pen_id'     => $this->request->getPost('pen_id'),
            'member_id'  => $this->request->getPost('member_id') ?: null,
            'status'     => 'active',
            'notes'      => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/admin/herd')->with('success', 'Animal added to herd.');
    }

    public function edit(int $id): string
    {
        $goat = $this->goats->find($id);
        if (! $goat) {
            return redirect()->to('/admin/herd')->with('error', 'Animal not found.');
        }

        $userModel = new UserModel();
        return $this->dashboardView('admin/goat_form', [
            'pageTitle' => 'Edit — ' . $goat['name'],
            'goat'      => $goat,
            'members'   => $userModel->getByRole('member'),
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'sex'  => 'required|in_list[male,female]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->goats->update($id, [
            'name'      => $this->request->getPost('name'),
            'breed'     => $this->request->getPost('breed'),
            'sex'       => $this->request->getPost('sex'),
            'dob'       => $this->request->getPost('dob') ?: null,
            'pen_id'    => $this->request->getPost('pen_id'),
            'member_id' => $this->request->getPost('member_id') ?: null,
            'status'    => $this->request->getPost('status'),
            'notes'     => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/admin/herd')->with('success', 'Animal record updated.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// ADMIN MEMBER CONTROLLER
// Full member management — deactivate/reactivate accounts.
// ══════════════════════════════════════════════════════════════════════════════

class MemberController extends BaseController
{
    private UserModel              $users;
    private MemberApplicationModel $applications;
    private GoatModel              $goats;

    public function __construct()
    {
        $this->users        = new UserModel();
        $this->applications = new MemberApplicationModel();
        $this->goats        = new GoatModel();
    }

    public function index(): string
    {
        $db = \Config\Database::connect();

        $members = $db->table('users u')
                      ->select('u.*, COUNT(g.id) as goat_count')
                      ->join('goats g', 'g.member_id = u.id AND g.status = "active"', 'left')
                      ->where('u.role', 'member')
                      ->where('u.deleted_at', null)
                      ->groupBy('u.id')
                      ->orderBy('u.created_at', 'DESC')
                      ->get()->getResultArray();

        return $this->dashboardView('admin/members', [
            'pageTitle' => 'Goat Banking Members',
            'members'   => $members,
        ]);
    }

    public function show(int $id): string
    {
        $user = $this->users->find($id);

        if (! $user || $user['role'] !== 'member') {
            return redirect()->to('/admin/members')->with('error', 'Member not found.');
        }

        $application = $this->applications->findByUserId($id);
        $goats       = $this->goats->getByMember($id);

        return $this->dashboardView('admin/member_detail', [
            'pageTitle'   => $user['first_name'] . ' ' . $user['last_name'] . ' — Member Profile',
            'member'      => $user,
            'application' => $application,
            'goats'       => $goats,
        ]);
    }

    public function deactivate(int $id)
    {
        $user = $this->users->find($id);

        if (! $user || $user['role'] !== 'member') {
            return redirect()->to('/admin/members')->with('error', 'Member not found.');
        }

        $this->users->deactivate($id);

        return redirect()->to('/admin/members')
                         ->with('success', $user['first_name'] . '\'s account has been deactivated.');
    }

    public function reactivate(int $id)
    {
        $user = $this->users->find($id);

        if (! $user || $user['role'] !== 'member') {
            return redirect()->to('/admin/members')->with('error', 'Member not found.');
        }

        $this->users->activate($id);

        return redirect()->to('/admin/members')
                         ->with('success', $user['first_name'] . '\'s account has been reactivated.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// ADMIN SETTINGS CONTROLLER
// ══════════════════════════════════════════════════════════════════════════════

class SettingsController extends BaseController
{
    public function index(): string
    {
        return $this->dashboardView('admin/settings', [
            'pageTitle' => 'System Settings',
        ]);
    }

    public function update()
    {
        // In production, store settings in the DB via a settings table.
        // For now, acknowledge the submission.
        return redirect()->to('/admin/settings')
                         ->with('success', 'Settings saved.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// DOCUMENT SERVING CONTROLLER
// Serves private NID documents to authorised admin users only.
// Route: GET /admin/documents/:applicationId/:docType
// ══════════════════════════════════════════════════════════════════════════════

class DocumentController extends BaseController
{
    public function serve(int $applicationId, string $docType)
    {
        // Only super admins can view documents
        if ($this->currentUserRole() !== 'super_admin') {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        $applications = new MemberApplicationModel();
        $application  = $applications->find($applicationId);

        if (! $application) {
            return $this->response->setStatusCode(404)->setBody('Application not found');
        }

        // Map docType to field name
        $fieldMap = [
            'nid_front'     => 'nid_front_path',
            'nid_back'      => 'nid_back_path',
            'photo'         => 'photo_path',
            'nok_nid_front' => 'nok_nid_front_path',
            'nok_nid_back'  => 'nok_nid_back_path',
        ];

        $field = $fieldMap[$docType] ?? null;

        if (! $field || empty($application[$field])) {
            return $this->response->setStatusCode(404)->setBody('Document not found');
        }

        $uploader = new \App\Libraries\FileUploader();
        $path     = $uploader->getAbsolutePath($application[$field]);

        if (! file_exists($path)) {
            return $this->response->setStatusCode(404)->setBody('File missing from server');
        }

        $mime     = mime_content_type($path);
        $basename = basename($path);

        return $this->response
                    ->setHeader('Content-Type', $mime)
                    ->setHeader('Content-Disposition', 'inline; filename="' . $basename . '"')
                    ->setHeader('Cache-Control', 'private, no-cache')
                    ->setBody(file_get_contents($path));
    }
}
