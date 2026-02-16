<?php
// Format date
function format_date($date) {
    return date('M j, Y', strtotime($date));
}

// Role badge colors
function role_badge($role) {
    switch($role) {
        case 'admin': return 'danger';
        case 'teacher': return 'success';
        default: return 'primary';
    }
}
?>