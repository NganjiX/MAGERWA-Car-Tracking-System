<?php
// pages/vehicles.php - COMPLETE with working Edit/Delete
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGERWA - Vehicle Management</title>
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
                        <a class="nav-link active" href="vehicles.php">
                            <i class="fas fa-car"></i> Vehicles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="link-vehicle.php">
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
        <div class="row">
            <div class="col-lg-4">
                <div class="card-premium animate-fade-up">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-car text-primary me-2"></i>
                            Register Vehicle
                        </h5>
                        <span class="badge-premium badge-premium-info">New</span>
                    </div>
                    <div class="card-body">
                        <form id="vehicleForm" class="form-premium">
                            <div class="mb-3">
                                <label for="chassis_number" class="form-label">
                                    <i class="fas fa-fingerprint me-1"></i>Chassis Number
                                </label>
                                <input type="text" class="form-control" id="chassis_number" 
                                       placeholder="e.g., VIN123456789" required>
                            </div>

                            <div class="mb-3">
                                <label for="manufacture_company" class="form-label">
                                    <i class="fas fa-building me-1"></i>Manufacture Company
                                </label>
                                <input type="text" class="form-control" id="manufacture_company" 
                                       placeholder="e.g., Toyota" required>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="manufacture_year" class="form-label">
                                            <i class="fas fa-calendar me-1"></i>Year
                                        </label>
                                        <input type="number" class="form-control" id="manufacture_year" 
                                               placeholder="2024" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            <i class="fas fa-money-bill me-1"></i>Price (RWF)
                                        </label>
                                        <input type="number" class="form-control" id="price" 
                                               placeholder="25000000" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="model_name" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Model Name
                                </label>
                                <input type="text" class="form-control" id="model_name" 
                                       placeholder="e.g., Land Cruiser" required>
                            </div>

                            <button type="submit" class="btn-premium btn-premium-primary w-100">
                                <i class="fas fa-save me-2"></i>Register Vehicle
                            </button>
                        </form>
                        <div id="vehicleMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-premium animate-fade-up" style="animation-delay: 0.1s;">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-list text-success me-2"></i>
                            Registered Vehicles
                        </h5>
                        <div>
                            <span class="badge-premium badge-premium-success" id="vehicleCount">0</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" 
                                       data-search placeholder="Search vehicles..." 
                                       style="border-left: none;">
                            </div>
                        </div>
                        <div id="vehiclesList">
                            <div class="text-center py-5">
                                <div class="loader-dots">
                                    <span class="dot"></span>
                                    <span class="dot"></span>
                                    <span class="dot"></span>
                                </div>
                                <p class="text-muted mt-3">Loading vehicles...</p>
                            </div>
                        </div>
                        <div id="vehiclePagination" data-pagination class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Vehicle
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editVehicleForm" class="form-premium">
                        <input type="hidden" id="edit_vehicle_id">
                        <div class="mb-3">
                            <label for="edit_chassis_number" class="form-label">Chassis Number</label>
                            <input type="text" class="form-control" id="edit_chassis_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_manufacture_company" class="form-label">Manufacture Company</label>
                            <input type="text" class="form-control" id="edit_manufacture_company" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="edit_manufacture_year" class="form-label">Year</label>
                                    <input type="number" class="form-control" id="edit_manufacture_year" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="edit_price" class="form-label">Price (RWF)</label>
                                    <input type="number" class="form-control" id="edit_price" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_model_name" class="form-label">Model Name</label>
                            <input type="text" class="form-control" id="edit_model_name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-premium btn-premium-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-premium btn-premium-primary" onclick="updateVehicle()">
                        <i class="fas fa-save me-2"></i>Update Vehicle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
    let currentPage = 1;
    let editVehicleModal = null;

    $(document).ready(function() {
        editVehicleModal = new bootstrap.Modal(document.getElementById('editVehicleModal'));
        loadVehicles(currentPage);

        $('#vehicleForm').on('submit', function(e) {
            e.preventDefault();
            
            const vehicleData = {
                chassis_number: $('#chassis_number').val(),
                manufacture_company: $('#manufacture_company').val(),
                manufacture_year: $('#manufacture_year').val(),
                price: $('#price').val(),
                model_name: $('#model_name').val()
            };

            const btn = $(this).find('button[type="submit"]');
            btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Registering...');
            btn.prop('disabled', true);

            $.ajax({
                url: '../api/vehicles.php',
                method: 'POST',
                data: JSON.stringify(vehicleData),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success('Vehicle registered successfully!');
                        $('#vehicleForm')[0].reset();
                        loadVehicles(currentPage);
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                    btn.html('<i class="fas fa-save me-2"></i>Register Vehicle');
                    btn.prop('disabled', false);
                },
                error: function() {
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                    btn.html('<i class="fas fa-save me-2"></i>Register Vehicle');
                    btn.prop('disabled', false);
                }
            });
        });
    });

    function loadVehicles(page) {
        $.get(`../api/vehicles.php?page=${page}`, function(data) {
            if (data.success) {
                renderVehicles(data.data);
                $('#vehicleCount').text(data.pagination.total);
                
                const paginationContainer = document.getElementById('vehiclePagination');
                if (paginationContainer) {
                    window.MAGERWA.app.renderPagination(
                        paginationContainer,
                        data.pagination.total,
                        page,
                        10
                    );
                    paginationContainer.addEventListener('pageChange', (e) => {
                        loadVehicles(e.detail.page);
                    });
                }
            }
        });
    }

    function renderVehicles(vehicles) {
        if (vehicles.length === 0) {
            $('#vehiclesList').html(`
                <div class="text-center py-5">
                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No vehicles registered yet.</p>
                    <p class="text-muted small">Click "Register Vehicle" to add your first vehicle.</p>
                </div>
            `);
            return;
        }

        let html = '<div class="table-responsive"><table class="table-premium">';
        html += '<thead><tr>';
        html += '<th>Chassis</th>';
        html += '<th>Company</th>';
        html += '<th>Year</th>';
        html += '<th>Price</th>';
        html += '<th>Model</th>';
        html += '<th>Actions</th>';
        html += '</tr></thead><tbody>';

        vehicles.forEach(vehicle => {
            html += `<tr>
                <td><code class="bg-light px-2 py-1 rounded">${vehicle.chassis_number}</code></td>
                <td><strong>${vehicle.manufacture_company}</strong></td>
                <td>${vehicle.manufacture_year}</td>
                <td><span class="fw-bold">${Number(vehicle.price).toLocaleString()} RWF</span></td>
                <td><span class="badge-premium badge-premium-primary">${vehicle.model_name}</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editVehicle(${vehicle.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteVehicle(${vehicle.id}, '${vehicle.chassis_number}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        });

        html += '</tbody></table></div>';
        $('#vehiclesList').html(html);
    }

    // ========== EDIT VEHICLE ==========
    function editVehicle(id) {
        $.get(`../api/vehicles.php?id=${id}`, function(response) {
            if (response.success) {
                const vehicle = response.data;
                $('#edit_vehicle_id').val(vehicle.id);
                $('#edit_chassis_number').val(vehicle.chassis_number);
                $('#edit_manufacture_company').val(vehicle.manufacture_company);
                $('#edit_manufacture_year').val(vehicle.manufacture_year);
                $('#edit_price').val(vehicle.price);
                $('#edit_model_name').val(vehicle.model_name);
                editVehicleModal.show();
            } else {
                window.MAGERWA.app.notifications.error('Failed to load vehicle data');
            }
        });
    }

    function updateVehicle() {
        const id = $('#edit_vehicle_id').val();
        const data = {
            chassis_number: $('#edit_chassis_number').val(),
            manufacture_company: $('#edit_manufacture_company').val(),
            manufacture_year: $('#edit_manufacture_year').val(),
            price: $('#edit_price').val(),
            model_name: $('#edit_model_name').val()
        };

        $.ajax({
            url: `../api/vehicles.php?id=${id}`,
            method: 'PUT',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    window.MAGERWA.app.notifications.success('Vehicle updated successfully!');
                    editVehicleModal.hide();
                    loadVehicles(currentPage);
                } else {
                    window.MAGERWA.app.notifications.error(response.message);
                }
            },
            error: function() {
                window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
            }
        });
    }

    // ========== DELETE VEHICLE ==========
    function deleteVehicle(id, chassis) {
        if (confirm(`Are you sure you want to delete vehicle with chassis "${chassis}"? This action cannot be undone.`)) {
            $.ajax({
                url: `../api/vehicles.php?id=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(response.message);
                        loadVehicles(currentPage);
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                },
                error: function() {
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                }
            });
        }
    }
    </script>
</body>
</html>