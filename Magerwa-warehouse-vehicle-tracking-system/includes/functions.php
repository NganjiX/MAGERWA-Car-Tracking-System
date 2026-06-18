<?php
// includes/functions.php

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generatePlateNumber() {
    $prefix = 'RWA';
    $suffix = strtoupper(substr(uniqid(), -6));
    return $prefix . '-' . $suffix;
}

function formatCurrency($amount) {
    return number_format($amount, 0, '.', ',') . ' RWF';
}

function getStatusBadge($status) {
    $badges = [
        'active' => '<span class="badge bg-success">Active</span>',
        'inactive' => '<span class="badge bg-danger">Inactive</span>',
        'pending' => '<span class="badge bg-warning">Pending</span>'
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
}
?>