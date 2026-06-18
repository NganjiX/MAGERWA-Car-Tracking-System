<?php
// pages/link-vehicle.php - FIXED working link
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGERWA - Link Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Premium Navigation -->
    <nav class="navbar navbar-expand-lg navbar-premium">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <span class="brand-icon">
                    <i class="fas fa-truck"></i>
                </span>
                MAGERWA
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon">
                    <i class="fas fa-bars text-white"></i>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clients.php">
                            <i class="fas fa-users"></i> Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vehicles.php">
                            <i class="fas fa-car"></i> Vehicles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="link-vehicle.php">
                            <i class="fas fa-link"></i> Link Vehicle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view-vehicles.php">
                            <i class="fas fa-list"></i> View All
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../api/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-premium animate-fade-up">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-link text-info me-2"></i>
                            Link Vehicle to Client
                        </h5>
                        <span class="badge-premium badge-premium-info">
                            <i class="fas fa-info-circle me-1"></i>Action Required
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block">Available Vehicles</small>
                                    <h4 class="mb-0" id="availableCount">0</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block">Total Clients</small>
                                    <h4 class="mb-0" id="clientCount">0</h4>
                                </div>
                            </div>
                        </div>

                        <form id="linkForm" class="form-premium">
                            <div class="mb-4">
                                <label for="vehicle_id" class="form-label">
                                    <i class="fas fa-car me-1"></i>Select Vehicle
                                </label>
                                <select class="form-control" id="vehicle_id" required>
                                    <option value="">Loading vehicles...</option>
                                </select>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Only unlinked vehicles are shown
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="client_id" class="form-label">
                                    <i class="fas fa-user me-1"></i>Select Client
                                </label>
                                <select class="form-control" id="client_id" required>
                                    <option value="">Loading clients...</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="plate_number" class="form-label">
                                    <i class="fas fa-hashtag me-1"></i>Plate Number
                                </label>
                                <input type="text" class="form-control" id="plate_number" 
                                       placeholder="Leave empty for auto-generation">
                                <small class="text-muted">
                                    <i class="fas fa-magic me-1"></i>Auto-generate by leaving empty
                                </small>
                            </div>

                            <button type="submit" class="btn-premium btn-premium-primary w-100" id="linkBtn">
                                <i class="fas fa-link me-2"></i>Link Vehicle
                            </button>
                        </form>
                        <div id="linkMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
    $(document).ready(function() {
        loadAvailableVehicles();
        loadAllClients();

        $('#linkForm').on('submit', function(e) {
            e.preventDefault();
            
            const vehicle_id = $('#vehicle_id').val();
            const client_id = $('#client_id').val();
            const plate_number = $('#plate_number').val() || null;

            if (!vehicle_id || !client_id) {
                window.MAGERWA.app.notifications.error('Please select both vehicle and client');
                return;
            }

            const btn = $('#linkBtn');
            btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Linking...');
            btn.prop('disabled', true);

            $.ajax({
                url: '../api/links.php',
                method: 'POST',
                data: JSON.stringify({
                    vehicle_id: parseInt(vehicle_id),
                    client_id: parseInt(client_id),
                    plate_number: plate_number
                }),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(
                            `Vehicle linked successfully! Plate: ${response.plate_number}`
                        );
                        $('#linkForm')[0].reset();
                        loadAvailableVehicles();
                        
                        $('#linkMessage').html(`
                            <div class="alert alert-success border-0 shadow-sm animate-fade-up">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">${response.message}</h6>
                                        <p class="mb-0">
                                            <strong>Plate Number:</strong> 
                                            <code class="bg-white px-2 py-1 rounded">${response.plate_number}</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `);
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                    btn.html('<i class="fas fa-link me-2"></i>Link Vehicle');
                    btn.prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                    btn.html('<i class="fas fa-link me-2"></i>Link Vehicle');
                    btn.prop('disabled', false);
                }
            });
        });
    });

    function loadAvailableVehicles() {
        $.get('../api/links.php?available=true', function(data) {
            if (data.success) {
                const select = $('#vehicle_id');
                select.empty();
                
                if (data.data.length === 0) {
                    select.append('<option value="">No available vehicles</option>');
                } else {
                    select.append('<option value="">Select a vehicle</option>');
                    data.data.forEach(vehicle => {
                        select.append(`
                            <option value="${vehicle.id}">
                                ${vehicle.chassis_number} - ${vehicle.manufacture_company} ${vehicle.model_name}
                            </option>
                        `);
                    });
                }
                $('#availableCount').text(data.data.length);
            }
        });
    }

    function loadAllClients() {
        $.get('../api/clients.php', function(data) {
            if (data.success) {
                const select = $('#client_id');
                select.empty();
                
                if (data.data.length === 0) {
                    select.append('<option value="">No clients available</option>');
                } else {
                    select.append('<option value="">Select a client</option>');
                    data.data.forEach(client => {
                        select.append(`
                            <option value="${client.id}">
                                ${client.names} - ${client.national_id}
                            </option>
                        `);
                    });
                }
                $('#clientCount').text(data.pagination.total);
            }
        });
    }
    </script>
</body>
</html>