<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ContactMessageModel;
use App\Libraries\EmailService;

class PublicController extends BaseController
{
    public function index(): string   { return view('public/home',        ['pageTitle'=>'MD Goatco Farm Limited — Ethics · Service · Genetics','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function about(): string   { return view('public/home',        ['pageTitle'=>'About — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function services(): string{ return view('public/home',        ['pageTitle'=>'Services — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function goatBanking(): string { return view('public/home',    ['pageTitle'=>'Goat Banking — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function contact(): string { return view('public/home',        ['pageTitle'=>'Contact — MD Goatco Farm Limited','currentUser'=>$this->currentUser(),'errors'=>session('errors')]); }
    public function privacy(): string { return view('public/privacy',     ['pageTitle'=>'Privacy Policy — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function terms(): string   { return view('public/terms',       ['pageTitle'=>'Terms & Conditions — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function notFound(): string{ return view('errors/404',         ['pageTitle'=>'Page Not Found']); }

    public function sendContact()
    {
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

        log_message('info', 'Contact form from: '.$contact['email']);
        return redirect()->to('/contact')->with('success', 'Thank you! We will get back to you within 1–2 working days.');
    }
}
