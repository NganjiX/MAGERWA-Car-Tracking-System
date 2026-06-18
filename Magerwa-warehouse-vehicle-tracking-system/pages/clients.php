<?php
// pages/clients.php - COMPLETE with working Edit/Delete
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGERWA - Client Management</title>
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
                        <a class="nav-link active" href="clients.php">
                            <i class="fas fa-users"></i> Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vehicles.php">
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
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            Register Client
                        </h5>
                        <span class="badge-premium badge-premium-info">New</span>
                    </div>
                    <div class="card-body">
                        <form id="clientForm" class="form-premium">
                            <div class="mb-3">
                                <label for="names" class="form-label">
                                    <i class="fas fa-user me-1"></i>Full Names
                                </label>
                                <input type="text" class="form-control" id="names" 
                                       placeholder="Client Full Name" required>
                            </div>

                            <div class="mb-3">
                                <label for="national_id" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>National ID
                                </label>
                                <input type="text" class="form-control" id="national_id" 
                                       placeholder="1234567890123456" required>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="fas fa-info-circle me-1"></i>16 digits required
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Telephone
                                </label>
                                <input type="tel" class="form-control" id="telephone" 
                                       placeholder="0788000000" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>Address
                                </label>
                                <textarea class="form-control" id="address" rows="2" 
                                          placeholder="Kigali, Rwanda"></textarea>
                            </div>

                            <button type="submit" class="btn-premium btn-premium-primary w-100">
                                <i class="fas fa-save me-2"></i>Register Client
                            </button>
                        </form>
                        <div id="clientMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-premium animate-fade-up" style="animation-delay: 0.1s;">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-users text-success me-2"></i>
                            Registered Clients
                        </h5>
                        <div>
                            <span class="badge-premium badge-premium-success" id="clientCount">0</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" 
                                       data-search placeholder="Search clients..." 
                                       style="border-left: none;">
                            </div>
                        </div>
                        <div id="clientsList">
                            <div class="text-center py-5">
                                <div class="loader-dots">
                                    <span class="dot"></span>
                                    <span class="dot"></span>
                                    <span class="dot"></span>
                                </div>
                                <p class="text-muted mt-3">Loading clients...</p>
                            </div>
                        </div>
                        <div id="clientPagination" data-pagination class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div class="modal fade" id="editClientModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Client
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editClientForm" class="form-premium">
                        <input type="hidden" id="edit_client_id">
                        <div class="mb-3">
                            <label for="edit_names" class="form-label">Full Names</label>
                            <input type="text" class="form-control" id="edit_names" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_national_id" class="form-label">National ID</label>
                            <input type="text" class="form-control" id="edit_national_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="edit_telephone" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_address" class="form-label">Address</label>
                            <textarea class="form-control" id="edit_address" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-premium btn-premium-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-premium btn-premium-primary" onclick="updateClient()">
                        <i class="fas fa-save me-2"></i>Update Client
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
    let editModal = null;

    $(document).ready(function() {
        editModal = new bootstrap.Modal(document.getElementById('editClientModal'));
        loadClients(currentPage);

        $('#clientForm').on('submit', function(e) {
            e.preventDefault();
            
            const national_id = $('#national_id').val();
            
            if (!/^[0-9]{16}$/.test(national_id)) {
                window.MAGERWA.app.notifications.error('National ID must be exactly 16 digits');
                return;
            }
            
            const clientData = {
                names: $('#names').val(),
                national_id: national_id,
                telephone: $('#telephone').val(),
                address: $('#address').val()
            };

            const btn = $(this).find('button[type="submit"]');
            btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Registering...');
            btn.prop('disabled', true);

            $.ajax({
                url: '../api/clients.php',
                method: 'POST',
                data: JSON.stringify(clientData),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success('Client registered successfully!');
                        $('#clientForm')[0].reset();
                        loadClients(currentPage);
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                    btn.html('<i class="fas fa-save me-2"></i>Register Client');
                    btn.prop('disabled', false);
                },
                error: function() {
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                    btn.html('<i class="fas fa-save me-2"></i>Register Client');
                    btn.prop('disabled', false);
                }
            });
        });
    });

    function loadClients(page) {
        $.get(`../api/clients.php?page=${page}`, function(data) {
            if (data.success) {
                renderClients(data.data);
                $('#clientCount').text(data.pagination.total);
                
                const paginationContainer = document.getElementById('clientPagination');
                if (paginationContainer) {
                    window.MAGERWA.app.renderPagination(
                        paginationContainer,
                        data.pagination.total,
                        page,
                        10
                    );
                    paginationContainer.addEventListener('pageChange', (e) => {
                        loadClients(e.detail.page);
                    });
                }
            }
        });
    }

    function renderClients(clients) {
        if (clients.length === 0) {
            $('#clientsList').html(`
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No clients registered yet.</p>
                    <p class="text-muted small">Click "Register Client" to add your first client.</p>
                </div>
            `);
            return;
        }

        let html = '<div class="table-responsive"><table class="table-premium">';
        html += '<thead><tr>';
        html += '<th>Names</th>';
        html += '<th>National ID</th>';
        html += '<th>Telephone</th>';
        html += '<th>Address</th>';
        html += '<th>Registered</th>';
        html += '<th>Actions</th>';
        html += '</tr></thead><tbody>';

        clients.forEach(client => {
            html += `<tr>
                <td><strong>${client.names}</strong></td>
                <td><code class="bg-light px-2 py-1 rounded">${client.national_id}</code></td>
                <td><a href="tel:${client.telephone}" class="text-decoration-none">${client.telephone}</a></td>
                <td>${client.address || '<span class="text-muted">N/A</span>'}</td>
                <td><span class="text-muted small">${window.MAGERWA.Utils.formatDate(client.created_at)}</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editClient(${client.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteClient(${client.id}, '${client.names}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        });

        html += '</tbody></table></div>';
        $('#clientsList').html(html);
    }

    // ========== EDIT CLIENT ==========
    function editClient(id) {
        $.get(`../api/clients.php?id=${id}`, function(response) {
            if (response.success) {
                const client = response.data;
                $('#edit_client_id').val(client.id);
                $('#edit_names').val(client.names);
                $('#edit_national_id').val(client.national_id);
                $('#edit_telephone').val(client.telephone);
                $('#edit_address').val(client.address || '');
                editModal.show();
            } else {
                window.MAGERWA.app.notifications.error('Failed to load client data');
            }
        });
    }

    function updateClient() {
        const id = $('#edit_client_id').val();
        const data = {
            names: $('#edit_names').val(),
            national_id: $('#edit_national_id').val(),
            telephone: $('#edit_telephone').val(),
            address: $('#edit_address').val()
        };

        $.ajax({
            url: `../api/clients.php?id=${id}`,
            method: 'PUT',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    window.MAGERWA.app.notifications.success('Client updated successfully!');
                    editModal.hide();
                    loadClients(currentPage);
                } else {
                    window.MAGERWA.app.notifications.error(response.message);
                }
            },
            error: function() {
                window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
            }
        });
    }

    // ========== DELETE CLIENT ==========
    function deleteClient(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            $.ajax({
                url: `../api/clients.php?id=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(response.message);
                        loadClients(currentPage);
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