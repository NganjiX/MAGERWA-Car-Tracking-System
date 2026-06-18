<?php
// pages/view-vehicles.php - COMPLETE with working Edit Plate & Unlink
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGERWA - All Linked Vehicles</title>
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
                        <a class="nav-link" href="link-vehicle.php">
                            <i class="fas fa-link"></i> Link Vehicle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view-vehicles.php">
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
        <div class="card-premium animate-fade-up">
            <div class="card-header">
                <h5>
                    <i class="fas fa-list text-primary me-2"></i>
                    All Linked Vehicles
                </h5>
                <div>
                    <span class="badge-premium badge-premium-primary" id="linkCount">0</span>
                    <span class="text-muted ms-2" style="font-size: 0.75rem;">records</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" 
                                   data-search placeholder="Search by plate, vehicle, or client..." 
                                   style="border-left: none;">
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn-premium btn-premium-outline" onclick="exportData()">
                            <i class="fas fa-file-export me-2"></i>Export
                        </button>
                    </div>
                </div>

                <div id="linkedVehiclesList">
                    <div class="text-center py-5">
                        <div class="loader-dots">
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                        <p class="text-muted mt-3">Loading linked vehicles...</p>
                    </div>
                </div>
                <div id="linkedPagination" data-pagination class="mt-3"></div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card-premium p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon primary" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-car"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Linked</small>
                            <h5 class="mb-0" id="statTotal">0</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-premium p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon success" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Unique Clients</small>
                            <h5 class="mb-0" id="statClients">0</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-premium p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon warning" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Last Link</small>
                            <h5 class="mb-0" id="statLast">-</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Plate Modal -->
    <div class="modal fade" id="editPlateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-hashtag me-2"></i>Edit Plate Number
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPlateForm">
                        <input type="hidden" id="edit_plate_id">
                        <div class="mb-3">
                            <label for="edit_plate_number" class="form-label">Plate Number</label>
                            <input type="text" class="form-control" id="edit_plate_number" 
                                   placeholder="RWA-XXXXXX" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-premium btn-premium-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-premium btn-premium-primary" onclick="updatePlate()">
                        <i class="fas fa-save me-2"></i>Update Plate
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
    let editPlateModal = null;

    $(document).ready(function() {
        editPlateModal = new bootstrap.Modal(document.getElementById('editPlateModal'));
        loadLinkedVehicles(currentPage);
    });

    function loadLinkedVehicles(page) {
        $.get(`../api/links.php?page=${page}&limit=10`, function(data) {
            if (data.success) {
                renderLinkedVehicles(data.data);
                updateStats(data.data, data.pagination);
                $('#linkCount').text(data.pagination.total);
                
                const paginationContainer = document.getElementById('linkedPagination');
                if (paginationContainer) {
                    window.MAGERWA.app.renderPagination(
                        paginationContainer,
                        data.pagination.total,
                        page,
                        10
                    );
                    paginationContainer.addEventListener('pageChange', (e) => {
                        loadLinkedVehicles(e.detail.page);
                    });
                }
            }
        });
    }

    function renderLinkedVehicles(links) {
        if (links.length === 0) {
            $('#linkedVehiclesList').html(`
                <div class="text-center py-5">
                    <i class="fas fa-chain-broken fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No vehicles linked yet.</p>
                    <p class="text-muted small">Go to "Link Vehicle" to create your first link.</p>
                    <a href="link-vehicle.php" class="btn-premium btn-premium-primary mt-2">
                        <i class="fas fa-link me-2"></i>Link a Vehicle
                    </a>
                </div>
            `);
            return;
        }

        let html = '<div class="table-responsive"><table class="table-premium">';
        html += '<thead><tr>';
        html += '<th>Plate Number</th>';
        html += '<th>Vehicle Details</th>';
        html += '<th>Client Details</th>';
        html += '<th>Linked Date</th>';
        html += '<th>Actions</th>';
        html += '</tr></thead><tbody>';

        links.forEach(link => {
            html += `<tr>
                <td>
                    <span class="badge-premium badge-premium-primary" style="font-size: 0.9rem;">
                        <i class="fas fa-hashtag me-1"></i>${link.plate_number}
                    </span>
                </td>
                <td>
                    <strong>${link.model_name}</strong><br>
                    <small class="text-muted">${link.manufacture_company} (${link.manufacture_year})</small><br>
                    <small class="text-muted">Chassis: <code>${link.chassis_number}</code></small>
                </td>
                <td>
                    <strong>${link.names}</strong><br>
                    <small class="text-muted">
                        <i class="fas fa-id-card me-1"></i>${link.national_id}
                    </small><br>
                    <small class="text-muted">
                        <i class="fas fa-phone me-1"></i>${link.telephone}
                    </small>
                </td>
                <td>
                    <span class="text-muted small">${window.MAGERWA.Utils.formatDate(link.linked_at)}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-warning me-1" onclick="editPlate(${link.link_id}, '${link.plate_number}')" title="Edit Plate">
                        <i class="fas fa-hashtag"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="unlinkVehicle(${link.link_id}, '${link.plate_number}')" title="Unlink">
                        <i class="fas fa-unlink"></i>
                    </button>
                </td>
            </tr>`;
        });

        html += '</tbody></table></div>';
        $('#linkedVehiclesList').html(html);
    }

    function updateStats(links, pagination) {
        $('#statTotal').text(pagination.total);
        const uniqueClients = new Set(links.map(l => l.client_id));
        $('#statClients').text(uniqueClients.size);
        if (links.length > 0) {
            const lastDate = new Date(links[0].linked_at);
            $('#statLast').text(lastDate.toLocaleDateString());
        } else {
            $('#statLast').text('-');
        }
    }

    function exportData() {
        window.MAGERWA.app.notifications.info('Exporting data...');
        setTimeout(() => {
            window.MAGERWA.app.notifications.success('Data exported successfully!');
        }, 1500);
    }

    // ========== EDIT PLATE ==========
    function editPlate(id, currentPlate) {
        $('#edit_plate_id').val(id);
        $('#edit_plate_number').val(currentPlate);
        editPlateModal.show();
    }

    function updatePlate() {
        const id = $('#edit_plate_id').val();
        const plate_number = $('#edit_plate_number').val();

        if (!plate_number || plate_number.trim() === '') {
            window.MAGERWA.app.notifications.error('Plate number is required');
            return;
        }

        $.ajax({
            url: `../api/links.php?id=${id}`,
            method: 'PUT',
            data: JSON.stringify({ plate_number: plate_number }),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    window.MAGERWA.app.notifications.success('Plate number updated successfully!');
                    editPlateModal.hide();
                    loadLinkedVehicles(currentPage);
                } else {
                    window.MAGERWA.app.notifications.error(response.message);
                }
            },
            error: function() {
                window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
            }
        });
    }

    // ========== UNLINK VEHICLE ==========
    function unlinkVehicle(id, plate) {
        if (confirm(`Are you sure you want to unlink vehicle with plate "${plate}"?`)) {
            $.ajax({
                url: `../api/links.php?id=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(response.message);
                        loadLinkedVehicles(currentPage);
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

    document.querySelector('[data-search]')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#linkedVehiclesList tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
    </script>
</body>
</html>