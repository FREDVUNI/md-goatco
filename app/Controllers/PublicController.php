<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Mailer;

/**
 * PublicController
 *
 * Serves all public-facing pages — landing page, about, services,
 * Goat Banking info, legal pages, and contact form.
 */
class PublicController extends BaseController
{
    public function index(): string
    {
        return view('public/home', [
            'pageTitle' => 'MD Goatco Farm Limited — Ethics · Service · Genetics',
            'metaDesc'  => 'MD Goatco Farm Limited raises healthy, well-bred goats and offers a Goat Banking investment program in Mukono, Uganda.',
        ]);
    }

    public function about(): string
    {
        return view('public/about', [
            'pageTitle' => 'About Us — MD Goatco Farm Limited',
        ]);
    }

    public function services(): string
    {
        return view('public/services', [
            'pageTitle' => 'Our Services — MD Goatco Farm Limited',
        ]);
    }

    public function goatBanking(): string
    {
        return view('public/goat_banking', [
            'pageTitle' => 'Goat Banking — MD Goatco Farm Limited',
        ]);
    }

    public function privacy(): string
    {
        return view('public/privacy', [
            'pageTitle' => 'Privacy Policy — MD Goatco Farm Limited',
        ]);
    }

    public function terms(): string
    {
        return view('public/terms', [
            'pageTitle' => 'Terms & Conditions — MD Goatco Farm Limited',
        ]);
    }

    public function contact(): string
    {
        return view('public/contact', [
            'pageTitle' => 'Contact Us — MD Goatco Farm Limited',
        ]);
    }

    public function sendContact()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[200]',
            'email'   => 'required|valid_email',
            'subject' => 'required|min_length[3]|max_length[200]',
            'message' => 'required|min_length[20]|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Notify the team and send the visitor a confirmation.
        // Failures are logged, never block the form submission.
        $mailer    = new Mailer();
        $formData  = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];

        $mailer->sendToAdmins(
            '[Contact Form] ' . $formData['subject'],
            'contact_admin_notification',
            $formData
        );

        $mailer->send(
            $formData['email'],
            'We received your message — MD Goatco Farm',
            'contact_autoreply',
            $formData,
            $formData['name']
        );

        log_message('info', 'Contact form submission from: ' . $this->request->getPost('email'));

        return redirect()->to('/contact')
                         ->with('success', 'Thank you for your message. We will get back to you within 1–2 working days.');
    }

    public function notFound(): string
    {
        return view('errors/404', ['pageTitle' => 'Page Not Found']);
    }
}
