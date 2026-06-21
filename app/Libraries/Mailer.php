<?php

declare(strict_types=1);

namespace App\Libraries;

/**
 * Mailer
 *
 * Thin wrapper around CodeIgniter's built-in Email service that renders
 * branded HTML templates from app/Views/emails/.
 *
 * IMPORTANT: send() never throws. If SMTP isn't configured (e.g. fresh
 * local install with no .env email settings) it logs the failure and
 * returns false instead of breaking the calling request — registration,
 * approvals, password resets, etc. all keep working even when mail
 * can't actually go out.
 *
 * ── Customising templates ───────────────────────────────────────────
 * Every email extends app/Views/emails/layout.php (the same
 * extend/section pattern used by the dashboard views). To change the
 * logo, colours or footer for ALL emails, edit layout.php once. To
 * change the wording of a specific notification, edit its template in
 * app/Views/emails/ — e.g. application_approved.php.
 */
class Mailer
{
    /**
     * Render a template and send it.
     *
     * @param string $to       Recipient email address
     * @param string $subject  Email subject line
     * @param string $template Template name (without .php), e.g. 'password_reset'
     * @param array  $data     Variables passed to the view
     * @param string $toName   Optional recipient display name
     */
    public function send(string $to, string $subject, string $template, array $data = [], string $toName = ''): bool
    {
        if (empty($to)) {
            log_message('warning', "Mailer: refused to send '{$template}' — empty recipient address.");
            return false;
        }

        try {
            $data['subject'] = $subject;
            $body = view('emails/' . $template, $data);
        } catch (\Throwable $e) {
            log_message('error', "Mailer: failed to render template '{$template}': " . $e->getMessage());
            return false;
        }

        try {
            $email = \Config\Services::email(null, false);

            $emailConfig = config('Email');
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($to, $toName ?: $to);
            $email->setSubject($subject);
            $email->setMessage($body);

            $sent = $email->send();

            if (! $sent) {
                log_message(
                    'error',
                    "Mailer: send failed for '{$template}' to {$to}. " . strip_tags($email->printDebugger(['headers']))
                );
            }

            return $sent;
        } catch (\Throwable $e) {
            // Most common cause in local dev: no SMTP credentials in .env.
            // We log it and move on rather than breaking the user's request.
            log_message('error', "Mailer: exception sending '{$template}' to {$to}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send to every admin (super_admin role). Used for new-application
     * and support-ticket alerts.
     */
    public function sendToAdmins(string $subject, string $template, array $data = []): void
    {
        $userModel = new \App\Models\UserModel();
        $admins    = $userModel->getByRole('super_admin');

        foreach ($admins as $admin) {
            if (! empty($admin['email'])) {
                $this->send($admin['email'], $subject, $template, $data, trim($admin['first_name'] . ' ' . $admin['last_name']));
            }
        }
    }
}
