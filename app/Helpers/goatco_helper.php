<?php

declare(strict_types=1);

/**
 * MD Goatco Farm — Helper Functions
 *
 * Loaded globally via autoload in app/Config/Autoload.php
 * or manually: helper('goatco')
 *
 * Place in: app/Helpers/goatco_helper.php
 */

if (! function_exists('goatAge')) {
    /**
     * Calculate a human-readable age from a date of birth.
     *
     * @param  string|null $dob  Date string e.g. '2025-10-15'
     * @return string            e.g. '8 months' or '1 year 3 months'
     */
    function goatAge(?string $dob): string
    {
        if (empty($dob)) {
            return 'Unknown age';
        }

        try {
            $birth = new \DateTime($dob);
            $now   = new \DateTime();
            $diff  = $now->diff($birth);

            $years  = $diff->y;
            $months = $diff->m;

            if ($years === 0 && $months === 0) {
                $days = $diff->d;
                return $days . ' day' . ($days !== 1 ? 's' : '');
            }

            if ($years === 0) {
                return $months . ' month' . ($months !== 1 ? 's' : '');
            }

            if ($months === 0) {
                return $years . ' year' . ($years !== 1 ? 's' : '');
            }

            return $years . ' yr ' . $months . ' mo';

        } catch (\Exception $e) {
            return 'Unknown age';
        }
    }
}

if (! function_exists('goatCardColor')) {
    /**
     * Return a CSS gradient string for a goat card header based on breed.
     * Used in member portfolio view to visually distinguish goats.
     *
     * @param  string $breed
     * @return string  CSS value for background property
     */
    function goatCardColor(string $breed): string
    {
        $palettes = [
            'boer'        => 'linear-gradient(135deg, #cfe3d4, #9cc6a7)',
            'boer cross'  => 'linear-gradient(135deg, #d4e3f0, #9bb8d4)',
            'galla'       => 'linear-gradient(135deg, #e3d4cf, #c4a79b)',
            'galla cross' => 'linear-gradient(135deg, #e3e0d4, #bfba9b)',
            'local cross' => 'linear-gradient(135deg, #d4cfe3, #9b97c4)',
        ];

        $key = strtolower(trim($breed));

        return $palettes[$key] ?? 'linear-gradient(135deg, #dde3ea, #b0bac4)';
    }
}

if (! function_exists('time_ago')) {
    /**
     * Return a human-readable "time ago" string.
     *
     * @param  string $datetime  MySQL datetime string
     * @return string            e.g. '3 hours ago', 'Yesterday', '5 days ago'
     */
    function time_ago(string $datetime): string
    {
        if (empty($datetime)) {
            return '';
        }

        try {
            $time = (new \DateTime($datetime))->getTimestamp();
            $now  = time();
            $diff = $now - $time;

            if ($diff < 60)          return 'Just now';
            if ($diff < 3600)        return floor($diff / 60) . ' min ago';
            if ($diff < 7200)        return '1 hour ago';
            if ($diff < 86400)       return floor($diff / 3600) . ' hours ago';
            if ($diff < 172800)      return 'Yesterday';
            if ($diff < 604800)      return floor($diff / 86400) . ' days ago';
            if ($diff < 2592000)     return floor($diff / 604800) . ' weeks ago';

            return (new \DateTime($datetime))->format('j M Y');

        } catch (\Exception $e) {
            return '';
        }
    }
}

if (! function_exists('formatUgx')) {
    /**
     * Format an integer amount as Uganda Shillings.
     *
     * @param  int|float $amount
     * @return string    e.g. 'UGX 2,400,000'
     */
    function formatUgx(int|float $amount): string
    {
        return 'UGX ' . number_format((float) $amount, 0);
    }
}

if (! function_exists('statusBadge')) {
    /**
     * Render an HTML badge for an application/account status.
     * Output is NOT escaped — do not pass user input directly.
     *
     * @param  string $status  pending|approved|active|rejected|inactive|info_requested
     * @return string          HTML <span> element
     */
    function statusBadge(string $status): string
    {
        $map = [
            'pending'        => ['class' => 'badge-pending',  'label' => 'Pending'],
            'approved'       => ['class' => 'badge-active',   'label' => 'Approved'],
            'active'         => ['class' => 'badge-active',   'label' => 'Active'],
            'rejected'       => ['class' => 'badge-flagged',  'label' => 'Rejected'],
            'inactive'       => ['class' => 'badge-inactive', 'label' => 'Inactive'],
            'info_requested' => ['class' => 'badge-pending',  'label' => 'Info needed'],
            // Payment statuses (Pesapal)
            'completed'      => ['class' => 'badge-active',   'label' => 'Completed'],
            'failed'         => ['class' => 'badge-flagged',  'label' => 'Failed'],
            'invalid'        => ['class' => 'badge-flagged',  'label' => 'Invalid'],
            'reversed'       => ['class' => 'badge-flagged',  'label' => 'Reversed'],
        ];

        $item = $map[$status] ?? ['class' => 'badge-inactive', 'label' => ucfirst($status)];

        return '<span class="badge ' . $item['class'] . '">' . $item['label'] . '</span>';
    }
}

if (! function_exists('roleLabel')) {
    /**
     * Return a human-readable label for a user role.
     *
     * @param  string $role
     * @return string
     */
    function roleLabel(string $role): string
    {
        return match ($role) {
            'super_admin' => 'Super Administrator',
            'manager'     => 'Farm Manager',
            'vet'         => 'Veterinarian',
            'member'      => 'Goat Banking Member',
            default       => ucfirst($role),
        };
    }
}

if (! function_exists('visitTypeLabel')) {
    /**
     * Return a human-readable label for a vet visit type.
     *
     * @param  string $type
     * @return string
     */
    function visitTypeLabel(string $type): string
    {
        $labels = [
            'routine_checkup' => 'Routine checkup',
            'vaccination'     => 'Vaccination',
            'weight_check'    => 'Weight check',
            'treatment'       => 'Treatment',
            'follow_up'       => 'Follow-up',
            'emergency'       => 'Emergency',
            'deworming'       => 'Deworming',
        ];

        return $labels[$type] ?? ucwords(str_replace('_', ' ', $type));
    }
}

if (! function_exists('initials')) {
    /**
     * Generate initials from a first and last name.
     *
     * @param  string $firstName
     * @param  string $lastName
     * @return string  e.g. 'RK'
     */
    function initials(string $firstName, string $lastName = ''): string
    {
        $first = strtoupper(substr(trim($firstName), 0, 1));
        $last  = strtoupper(substr(trim($lastName), 0, 1));
        return $first . $last;
    }
}
