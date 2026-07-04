<?php
declare(strict_types=1);
namespace Config;
use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'hello@mdgoatco.farm';
    public string $fromName   = 'MD Goatco Farm Limited';
    public string $recipients = '';
    public string $userAgent  = 'MD Goatco Farm Mailer';
    public string $protocol   = 'smtp';
    public string $mailPath   = '/usr/sbin/sendmail';
    public string $SMTPHost   = '';       // ← e.g. smtp.gmail.com
    public string $SMTPUser   = '';       // ← your SMTP username
    public string $SMTPPass   = '';       // ← your SMTP password / app password
    public int    $SMTPPort   = 587;
    public string $SMTPCrypto = 'tls';
    public bool   $SMTPKeepAlive = false;
    public int    $SMTPTimeout   = 5;
    public bool   $SMTPVerifyPeer = true;
    public int    $wordWrap   = 76;
    public bool   $wordWrapSet = true;
    public string $mailType   = 'html';
    public string $charset    = 'utf-8';
    public bool   $validate   = true;
    public int    $priority   = 3;
    public string $newline    = "\r\n";
    public string $CRLF       = "\r\n";
    public bool   $DSN        = false;
    public bool   $sendMultipart = true;
    public bool   $BCCBatchMode  = false;
    public int    $BCCBatchSize  = 200;
}
