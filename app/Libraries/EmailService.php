<?php
declare(strict_types=1);
namespace App\Libraries;
use CodeIgniter\Email\Email;

class EmailService
{
    private Email  $email;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->email     = \Config\Services::email();
        $this->fromEmail = env('email.fromEmail', 'hello@mdgoatco.farm');
        $this->fromName  = env('email.fromName',  'MD Goatco Farm Limited');
    }

    public function sendApplicationReceived(array $user, array $application): bool
    {
        return $this->send($user['email'], 'We received your Goat Banking application', 'emails/member/application_received', ['user'=>$user,'application'=>$application]);
    }
    public function sendApproval(array $user): bool
    {
        return $this->send($user['email'], 'Your Goat Banking application has been approved! 🎉', 'emails/member/approved', ['user'=>$user,'loginUrl'=>site_url('auth/login')]);
    }
    public function sendRejection(array $user, string $reason=''): bool
    {
        return $this->send($user['email'], 'Update on your Goat Banking application', 'emails/member/rejected', ['user'=>$user,'reason'=>$reason]);
    }
    public function sendInfoRequest(array $user, string $note): bool
    {
        return $this->send($user['email'], 'Action required — additional information needed', 'emails/member/info_requested', ['user'=>$user,'note'=>$note]);
    }
    public function sendPaymentConfirmation(array $user, array $payment): bool
    {
        return $this->send($user['email'], 'Payment confirmed — MD Goatco Farm', 'emails/member/payment_confirmed', ['user'=>$user,'payment'=>$payment]);
    }
    public function sendHealthAlert(array $user, array $goat, string $flagReason): bool
    {
        return $this->send($user['email'], 'Health update: '.$goat['name'].' ('.$goat['tag_number'].')', 'emails/member/health_alert', ['user'=>$user,'goat'=>$goat,'flagReason'=>$flagReason,'dashboardUrl'=>site_url('member/goats/'.$goat['id'])]);
    }
    public function sendStatementReady(array $user, string $period): bool
    {
        return $this->send($user['email'], 'Your '.$period.' statement is ready', 'emails/member/statement_ready', ['user'=>$user,'period'=>$period,'dashboardUrl'=>site_url('member/statements')]);
    }
    public function sendPasswordReset(array $user, string $token): bool
    {
        return $this->send($user['email'], 'Reset your MD Goatco password', 'emails/auth/password_reset', ['user'=>$user,'resetUrl'=>site_url('auth/reset-password/'.$token)]);
    }
    public function sendStaffWelcome(array $user, string $temporaryPassword): bool
    {
        return $this->send($user['email'], 'Welcome to MD Goatco Farm — your account is ready', 'emails/staff/welcome', ['user'=>$user,'temporaryPassword'=>$temporaryPassword,'loginUrl'=>site_url('auth/login')]);
    }
    public function sendNewApplicationAlert(array $admin, array $application): bool
    {
        return $this->send($admin['email'], 'New Goat Banking application — '.($application['first_name']??'').' '.($application['last_name']??''), 'emails/admin/new_application', ['admin'=>$admin,'application'=>$application,'reviewUrl'=>site_url('admin/applications/'.($application['id']??''))]);
    }
    public function sendHealthFlagAlert(array $manager, array $goat, string $reason): bool
    {
        return $this->send($manager['email'], '🚨 Health flag: '.$goat['name'].' ('.$goat['tag_number'].')', 'emails/admin/health_flag', ['manager'=>$manager,'goat'=>$goat,'reason'=>$reason,'flagUrl'=>site_url('manager/health')]);
    }

    public function send(string $to, string $subject, string $view, array $data=[], array $cc=[]): bool
    {
        try {
            helper(['email']);
            $data['emailSubject'] = $subject;
            $html = view('emails/layouts/base', array_merge($data, ['emailContent'=>view($view, $data)]));
            $this->email->clear();
            $this->email->setFrom($this->fromEmail, $this->fromName);
            $this->email->setTo($to);
            $this->email->setSubject($subject);
            $this->email->setMessage($html);
            $this->email->setMailType('html');
            if (! empty($cc)) $this->email->setCC($cc);
            $result = $this->email->send(false);
            if (! $result) log_message('error', 'Email failed "'.$subject.'" to '.$to);
            return $result;
        } catch (\Throwable $e) {
            log_message('error', 'EmailService: '.$e->getMessage());
            return false;
        }
    }
}
