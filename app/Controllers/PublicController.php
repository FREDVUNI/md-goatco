<?php
declare(strict_types=1);
namespace App\Controllers;

class PublicController extends BaseController
{
    public function index(): string   { return view('public/home',        ['pageTitle'=>'MD Goatco Farm Limited — Ethics · Service · Genetics','currentUser'=>$this->currentUser()]); }
    public function about(): string   { return view('public/home',        ['pageTitle'=>'About — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function services(): string{ return view('public/home',        ['pageTitle'=>'Services — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function goatBanking(): string { return view('public/home',    ['pageTitle'=>'Goat Banking — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function contact(): string { return view('public/home',        ['pageTitle'=>'Contact — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function privacy(): string { return view('public/privacy',     ['pageTitle'=>'Privacy Policy — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function terms(): string   { return view('public/terms',       ['pageTitle'=>'Terms & Conditions — MD Goatco Farm Limited','currentUser'=>$this->currentUser()]); }
    public function notFound(): string{ return view('errors/404',         ['pageTitle'=>'Page Not Found']); }

    public function sendContact()
    {
        if (! $this->validate(['name'=>'required','email'=>'required|valid_email','subject'=>'required','message'=>'required|min_length[10]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // TODO: send email to hello@mdgoatco.farm
        log_message('info', 'Contact form from: '.$this->request->getPost('email'));
        return redirect()->to('/contact')->with('success', 'Thank you! We will get back to you within 1–2 working days.');
    }
}
