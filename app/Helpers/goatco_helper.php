<?php
if (! function_exists('goatAge')) {
    function goatAge(?string $dob): string {
        if (! $dob) return '—';
        $diff = (new DateTime($dob))->diff(new DateTime());
        if ($diff->y >= 1) return $diff->y . ' yr' . ($diff->y !== 1 ? 's' : '');
        if ($diff->m >= 1) return $diff->m . ' mo';
        return $diff->d . ' day' . ($diff->d !== 1 ? 's' : '');
    }
}
if (! function_exists('formatUgx')) {
    function formatUgx(float $amount): string { return 'UGX ' . number_format($amount); }
}
if (! function_exists('statusBadge')) {
    function statusBadge(string $status): string {
        $map = ['active'=>'badge-active','pending'=>'badge-pending','rejected'=>'badge-rejected','inactive'=>'badge-rejected'];
        $cls = $map[$status] ?? 'badge-pending';
        return '<span class="badge ' . $cls . '">' . esc(ucfirst($status)) . '</span>';
    }
}
if (! function_exists('roleLabel')) {
    function roleLabel(string $role): string {
        return match($role) {
            'super_admin' => 'Super Administrator',
            'manager'     => 'Farm Manager',
            'vet'         => 'Veterinarian',
            'member'      => 'Goat Banking Member',
            default       => ucfirst($role),
        };
    }
}
if (! function_exists('initials')) {
    function initials(string $first, string $last): string {
        return strtoupper(substr($first, 0, 1) . substr($last, 0, 1));
    }
}
if (! function_exists('time_ago')) {
    function time_ago(string $datetime): string {
        $diff = time() - strtotime($datetime);
        if ($diff < 60)     return 'just now';
        if ($diff < 3600)   return floor($diff/60) . ' min ago';
        if ($diff < 86400)  return floor($diff/3600) . ' hr ago';
        if ($diff < 604800) return floor($diff/86400) . ' day' . (floor($diff/86400)!==1?'s':'') . ' ago';
        return date('j M Y', strtotime($datetime));
    }
}
if (! function_exists('visitTypeLabel')) {
    function visitTypeLabel(string $type): string {
        return match($type) {
            'routine'      => 'Routine Check',
            'vaccination'  => 'Vaccination',
            'treatment'    => 'Treatment',
            'emergency'    => 'Emergency',
            'weight_check' => 'Weight Check',
            default        => ucfirst(str_replace('_',' ',$type)),
        };
    }
}
