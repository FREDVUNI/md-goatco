<?php
if (! function_exists('emailButton')) {
    function emailButton(string $label, string $url, string $color='blue'): string {
        $colors=['blue'=>'#2B5BA8','green'=>'#059669','amber'=>'#D97706','red'=>'#DC2626'];
        $c=$colors[$color]??'#2B5BA8';
        return '<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:24px 0"><tr><td align="center"><a href="'.esc($url).'" target="_blank" style="display:inline-block;background:'.$c.';color:#fff;font-size:15px;font-weight:700;text-decoration:none;padding:14px 32px;border-radius:8px;font-family:Arial,sans-serif">'.esc($label).'</a></td></tr></table>';
    }
}
if (! function_exists('emailAlert')) {
    function emailAlert(string $message, string $type='info'): string {
        $s=['success'=>['#ECFDF5','#059669','#065F46'],'warning'=>['#FFFBEB','#D97706','#92400E'],'error'=>['#FEF2F2','#DC2626','#991B1B'],'info'=>['#EDF2FB','#2B5BA8','#1E3F7A']];
        [$bg,$border,$color]=$s[$type]??$s['info'];
        return '<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:18px 0;background:'.$bg.';border-left:4px solid '.$border.';border-radius:0 8px 8px 0"><tr><td style="padding:14px 18px;color:'.$color.';font-size:14px;line-height:1.6;font-family:Arial,sans-serif">'.$message.'</td></tr></table>';
    }
}
if (! function_exists('emailDivider')) {
    function emailDivider(): string {
        return '<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:24px 0"><tr><td style="border-top:1px solid #D8E1F0;font-size:0;line-height:0">&nbsp;</td></tr></table>';
    }
}
if (! function_exists('emailHeading')) {
    function emailHeading(string $text): string {
        return '<h2 style="margin:0 0 12px;font-size:22px;font-weight:700;color:#1E3F7A;line-height:1.2;font-family:Arial,sans-serif">'.esc($text).'</h2>';
    }
}
if (! function_exists('emailParagraph')) {
    function emailParagraph(string $text): string {
        return '<p style="margin:0 0 16px;font-size:15px;color:#4A5568;line-height:1.7;font-family:Arial,sans-serif">'.$text.'</p>';
    }
}
if (! function_exists('emailLabel')) {
    function emailLabel(string $key, string $value): string {
        return '<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom:8px"><tr><td width="40%" style="font-size:13px;color:#718096;font-weight:600;padding:6px 0;vertical-align:top;font-family:Arial,sans-serif">'.esc($key).'</td><td style="font-size:14px;color:#1A2238;font-weight:500;padding:6px 0;font-family:Arial,sans-serif">'.esc($value).'</td></tr></table>';
    }
}
if (! function_exists('emailStatRow')) {
    function emailStatRow(array $stats): string {
        $cols=count($stats);$width=$cols>0?floor(100/$cols).'%':'33%';
        $html='<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:20px 0"><tr>';
        foreach($stats as $s){$html.='<td width="'.$width.'" align="center" style="background:#F4F7FD;border-radius:8px;padding:16px 12px;vertical-align:top"><p style="margin:0 0 4px;font-size:22px;font-weight:800;color:#2B5BA8;font-family:Arial,sans-serif">'.esc($s['value']).'</p><p style="margin:0;font-size:11px;color:#718096;text-transform:uppercase;letter-spacing:1px;font-family:monospace">'.esc($s['label']).'</p></td>';}
        return $html.'</tr></table>';
    }
}
