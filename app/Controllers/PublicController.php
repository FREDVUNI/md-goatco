<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GoatModel;
use App\Models\ContactMessageModel;
use App\Models\TestimonialModel;
use App\Libraries\EmailService;

class PublicController extends BaseController
{
    /** MD Goatco Farm was founded in 2012 — drives the "N yrs experience" hero stat. */
    private const FOUNDED_YEAR = 2012;

    /** Real, live counts for the homepage hero stats — no more made-up numbers. */
    private function homeStats(): array
    {
        return [
            'goatsOnFarm'     => (new GoatModel())->getStats()['total'] ?? 0,
            'bankingMembers'  => count((new UserModel())->getByRole('member')),
            'farmYears'       => max(0, (int) date('Y') - self::FOUNDED_YEAR),
            'testimonials'    => (new TestimonialModel())->getActive(),
        ];
    }

    public function index(): string   { return view('public/home',        $this->homeStats() + ['pageTitle'=>'MD Goatco Farm Limited — Ethics · Service · Genetics','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function about(): string   { return view('public/home',        $this->homeStats() + ['pageTitle'=>'About — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function services(): string{ return view('public/home',        $this->homeStats() + ['pageTitle'=>'Services — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function goatBanking(): string { return view('public/home',    $this->homeStats() + ['pageTitle'=>'Goat Banking — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function contact(): string { return view('public/home',        $this->homeStats() + ['pageTitle'=>'Contact — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function privacy(): string { return view('public/privacy',     ['pageTitle'=>'Privacy Policy — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function terms(): string   { return view('public/terms',       ['pageTitle'=>'Terms & Conditions — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function notFound(): string{ return view('errors/404',         ['pageTitle'=>'Page Not Found']); }

    public function sendContact()
    {
        if ($this->tooManyAttempts('contact_form', 5, 600)) {
            return redirect()->to('/contact')->withInput()->with('error', 'Too many messages sent. Please wait a few minutes and try again.');
        }
        if (! $this->validate([
            'name'=>'required','email'=>'required|valid_email','phone'=>'required|min_length[10]',
            'subject'=>'required','message'=>'required|min_length[10]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $contact = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];

        (new ContactMessageModel())->insert($contact);

        try {
            $mailer = new EmailService();
            $users  = new UserModel();

            $recipients = array_merge($users->getByRole('super_admin'), $users->getByRole('manager'));
            foreach ($recipients as $staff) {
                $mailer->sendContactMessage($staff['email'], $contact);
            }
            $mailer->sendContactMessage('hello@mdgoatco.farm', $contact);
            $mailer->sendContactAutoReply($contact);
        } catch (\Throwable $e) {
            log_message('error', 'Contact form email failed: '.$e->getMessage());
        }

        $notifs = new \App\Models\NotificationModel();
        foreach (array_merge((new UserModel())->getByRole('super_admin'), (new UserModel())->getByRole('manager')) as $staff) {
            $notifs->notifyUser((int) $staff['id'], 'New contact message', $contact['name'].': '.$contact['subject'], 'info', 'manager/contact');
        }

        log_message('info', 'Contact form from: '.$contact['email']);
        return redirect()->to('/contact')->with('success', 'Thank you! We will get back to you within 1–2 working days.');
    }
}
