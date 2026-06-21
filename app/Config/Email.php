<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Email Configuration
 *
 * Used by CI4's Email service (service('email')) for:
 *   - Application approval / rejection notifications
 *   - Password reset links
 *   - Welcome emails to new staff
 *
 * Override in .env:
 *   email.SMTPHost = smtp.gmail.com
 *   email.SMTPUser = hello@mdgoatco.farm
 *   email.SMTPPass = your-app-password
 */
class Email extends BaseConfig
{
    public string $fromEmail  = 'hello@mdgoatco.farm';
    public string $fromName   = 'MD Goatco Farm Limited';
    public string $recipients = '';

    public string $userAgent  = 'CodeIgniter';
    public string $protocol   = 'smtp';   // mail | sendmail | smtp

    public string $mailPath   = '/usr/sbin/sendmail';

    public string $SMTPHost   = 'smtp.gmail.com';
    public string $SMTPUser   = '';
    public string $SMTPPass   = '';
    public string $SMTPCrypto = 'tls';    // '' | ssl | tls
    public int    $SMTPPort   = 587;
    public int    $SMTPTimeout = 5;
    public bool   $SMTPKeepAlive = false;

    public bool   $wordWrap   = true;
    public int    $wrapChars  = 76;
    public string $mailType   = 'html';   // html | text
    public string $charset    = 'UTF-8';
    public bool   $validate   = true;
    public int    $priority   = 3;
    public string $CRLF       = "\r\n";
    public string $newline    = "\r\n";
    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN        = false;
}
