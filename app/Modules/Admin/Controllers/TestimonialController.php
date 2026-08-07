<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Models\TestimonialModel;

class TestimonialController extends BaseController
{
    private TestimonialModel $testimonials;
    public function __construct() { $this->testimonials = new TestimonialModel(); }

    public function index(): string
    {
        return $this->dashboardView('admin/testimonials', [
            'pageTitle'    => 'Testimonials',
            'testimonials' => $this->testimonials->getAllOrdered(),
        ]);
    }

    public function create(): string
    {
        return $this->dashboardView('admin/testimonial_form', ['pageTitle' => 'Add Testimonial']);
    }

    public function store()
    {
        if (! $this->validate([
            'quote'       => 'required|min_length[10]',
            'author_name' => 'required',
            'author_role' => 'required',
            'rating'      => 'required|in_list[1,2,3,4,5]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'quote'       => $this->request->getPost('quote'),
            'author_name' => $this->request->getPost('author_name'),
            'author_role' => $this->request->getPost('author_role'),
            'rating'      => (int) $this->request->getPost('rating'),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order'  => $this->testimonials->nextSortOrder(),
        ];

        [$avatarUrl, $error] = $this->handleAvatarUpload();
        if ($error) return redirect()->back()->withInput()->with('error', $error);
        if ($avatarUrl) $data['avatar_url'] = $avatarUrl;

        $this->testimonials->insert($data);
        return redirect()->to('/admin/testimonials')->with('success', 'Testimonial added.');
    }

    public function edit(int $id)
    {
        $testimonial = $this->testimonials->find($id);
        if (! $testimonial) return redirect()->to('/admin/testimonials')->with('error', 'Testimonial not found.');
        return $this->dashboardView('admin/testimonial_form', [
            'pageTitle'   => 'Edit Testimonial',
            'testimonial' => $testimonial,
        ]);
    }

    public function update(int $id)
    {
        $testimonial = $this->testimonials->find($id);
        if (! $testimonial) return redirect()->to('/admin/testimonials')->with('error', 'Testimonial not found.');

        if (! $this->validate([
            'quote'       => 'required|min_length[10]',
            'author_name' => 'required',
            'author_role' => 'required',
            'rating'      => 'required|in_list[1,2,3,4,5]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'quote'       => $this->request->getPost('quote'),
            'author_name' => $this->request->getPost('author_name'),
            'author_role' => $this->request->getPost('author_role'),
            'rating'      => (int) $this->request->getPost('rating'),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];

        [$avatarUrl, $error] = $this->handleAvatarUpload();
        if ($error) return redirect()->back()->withInput()->with('error', $error);
        if ($avatarUrl) $data['avatar_url'] = $avatarUrl;

        $this->testimonials->update($id, $data);
        return redirect()->to('/admin/testimonials')->with('success', 'Testimonial updated.');
    }

    public function delete(int $id)
    {
        $testimonial = $this->testimonials->find($id);
        if ($testimonial) {
            $this->deleteLocalAvatar($testimonial['avatar_url'] ?? null);
            $this->testimonials->delete($id);
        }
        return redirect()->to('/admin/testimonials')->with('success', 'Testimonial removed.');
    }

    public function toggle(int $id)
    {
        $testimonial = $this->testimonials->find($id);
        if ($testimonial) {
            $this->testimonials->update($id, ['is_active' => $testimonial['is_active'] ? 0 : 1]);
        }
        return redirect()->to('/admin/testimonials')->with('success', 'Testimonial visibility updated.');
    }

    public function moveUp(int $id)
    {
        $this->testimonials->moveOne($id, 'up');
        return redirect()->to('/admin/testimonials');
    }

    public function moveDown(int $id)
    {
        $this->testimonials->moveOne($id, 'down');
        return redirect()->to('/admin/testimonials');
    }

    /**
     * @return array{0: ?string, 1: ?string} [$newAvatarUrl, $error] — both
     * null when no file was chosen (leave the existing avatar untouched).
     */
    private function handleAvatarUpload(): array
    {
        $file = $this->request->getFile('avatar');
        if (! $file || ! $file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return [null, null];
        }
        if (strpos((string) $file->getMimeType(), 'image/') !== 0) {
            return [null, 'Avatar must be an image file.'];
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            return [null, 'Avatar image is too large (max 2 MB).'];
        }

        $dir = FCPATH . 'img/testimonials';
        if (! is_dir($dir)) mkdir($dir, 0755, true);

        $name = 'testimonial_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getExtension();
        $file->move($dir, $name, true);

        return [base_url('img/testimonials/' . $name), null];
    }

    /** Only remove files we host ourselves — never touch external/seed avatar URLs. */
    private function deleteLocalAvatar(?string $avatarUrl): void
    {
        if (! $avatarUrl || strpos($avatarUrl, base_url('img/testimonials/')) !== 0) return;
        $path = FCPATH . 'img/testimonials/' . basename($avatarUrl);
        if (is_file($path)) @unlink($path);
    }
}
