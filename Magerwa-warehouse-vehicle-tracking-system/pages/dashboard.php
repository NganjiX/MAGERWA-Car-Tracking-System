<?php
// pages/dashboard.php - COMPLETE with premium Recent Activity
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAGERWA - Premium Dashboard</title>
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
                        <a class="nav-link active" href="dashboard.php">
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

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card-premium p-4 animate-fade-up">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h2 class="mb-1">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_names']); ?>!</h2>
                            <p class="text-muted-premium mb-0">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?php echo date('l, F j, Y'); ?>
                            </p>
                        </div>
                        <div>
                            <span class="badge-premium badge-premium-success">
                                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="dashboard-stats stagger-children" id="dashboardStats">
            <div class="stat-card animate-fade-up">
                <div class="stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value" id="totalClients">0</div>
                <div class="stat-label">Total Clients</div>
            </div>

            <div class="stat-card animate-fade-up">
                <div class="stat-icon success">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stat-value" id="totalVehicles">0</div>
                <div class="stat-label">Total Vehicles</div>
            </div>

            <div class="stat-card animate-fade-up">
                <div class="stat-icon info">
                    <i class="fas fa-link"></i>
                </div>
                <div class="stat-value" id="totalLinked">0</div>
                <div class="stat-label">Linked Vehicles</div>
            </div>

            <div class="stat-card animate-fade-up">
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value" id="totalAvailable">0</div>
                <div class="stat-label">Available Vehicles</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card-premium p-4">
                    <h5 class="mb-3">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Quick Actions
                    </h5>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="clients.php" class="btn-premium btn-premium-primary">
                            <i class="fas fa-user-plus me-2"></i>Add Client
                        </a>
                        <a href="vehicles.php" class="btn-premium btn-premium-success">
                            <i class="fas fa-car-plus me-2"></i>Add Vehicle
                        </a>
                        <a href="link-vehicle.php" class="btn-premium btn-premium-outline">
                            <i class="fas fa-link me-2"></i>Link Vehicle
                        </a>
                        <a href="view-vehicles.php" class="btn-premium btn-premium-ghost">
                            <i class="fas fa-eye me-2"></i>View All
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Stats & Recent Activity -->
        <div class="row mt-4">
            <!-- Quick Stats -->
            <div class="col-lg-4">
                <div class="card-premium">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-pie me-2"></i>Quick Stats</h5>
                        <span class="badge-premium badge-premium-info">Live</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Clients</span>
                                <span class="fw-bold" id="statClients">0</span>
                            </div>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-primary" id="progressClients" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Vehicles</span>
                                <span class="fw-bold" id="statVehicles">0</span>
                            </div>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-success" id="progressVehicles" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Linked</span>
                                <span class="fw-bold" id="statLinked">0</span>
                            </div>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-info" id="progressLinked" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity - FIXED Premium Design -->
            <div class="col-lg-8">
                <div class="card-premium">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-clock me-2"></i>
                            Recent Activity
                        </h5>
                        <div>
                            <span class="badge-premium badge-premium-success">
                                <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i>
                                Live
                            </span>
                            <span class="badge-premium badge-premium-info ms-1" id="activityCount">0</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="recentActivity" class="p-3">
                            <div class="text-center py-4">
                                <div class="loader-dots">
                                    <span class="dot"></span>
                                    <span class="dot"></span>
                                    <span class="dot"></span>
                                </div>
                                <p class="text-muted mt-3">Loading activity...</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center p-2">
                        <a href="view-vehicles.php" class="text-decoration-none text-muted small">
                            <i class="fas fa-arrow-right me-1"></i>View all activity
                        </a>
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
        loadDashboardStats();
        loadRecentActivity();
    });

    function loadDashboardStats() {
        $.get('../api/clients.php', function(data) {
            if (data.success) {
                const total = data.pagination.total;
                $('#totalClients').text(total);
                $('#statClients').text(total);
                const percent = Math.min((total / 100) * 100, 100);
                $('#progressClients').css('width', percent + '%');
            }
        });

        $.get('../api/vehicles.php', function(data) {
            if (data.success) {
                const total = data.pagination.total;
                $('#totalVehicles').text(total);
                $('#statVehicles').text(total);
                const percent = Math.min((total / 100) * 100, 100);
                $('#progressVehicles').css('width', percent + '%');
            }
        });

        $.get('../api/links.php', function(data) {
            if (data.success) {
                const total = data.pagination.total;
                $('#totalLinked').text(total);
                $('#statLinked').text(total);
                const percent = Math.min((total / 100) * 100, 100);
                $('#progressLinked').css('width', percent + '%');
            }
        });

        $.get('../api/links.php?available=true', function(data) {
            if (data.success) {
                $('#totalAvailable').text(data.data.length);
            }
        });
    }

    function loadRecentActivity() {
        $.get('../api/links.php?page=1&limit=5', function(data) {
            if (data.success && data.data.length > 0) {
                $('#activityCount').text(data.data.length);
                
                let html = '<div class="activity-timeline">';
                data.data.forEach((link, index) => {
                    const isLast = index === data.data.length - 1;
                    html += `
                        <div class="activity-item d-flex align-items-center gap-3 py-3 px-2 ${!isLast ? 'border-bottom' : ''}">
                            <div class="activity-icon flex-shrink-0">
                                <span class="badge-premium badge-premium-primary rounded-circle p-2">
                                    <i class="fas fa-link"></i>
                                </span>
                            </div>
                            <div class="activity-content flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="badge-premium badge-premium-primary">
                                        <i class="fas fa-hashtag me-1"></i>${link.plate_number}
                                    </span>
                                    <span class="fw-semibold">${link.model_name}</span>
                                    <span class="text-muted small">•</span>
                                    <span class="text-muted small">${link.manufacture_company}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <i class="fas fa-user text-muted small"></i>
                                    <span class="text-muted small">${link.names}</span>
                                    <span class="text-muted small">•</span>
                                    <span class="text-muted small">
                                        <i class="far fa-clock me-1"></i>
                                        ${window.MAGERWA.Utils.formatDate(link.linked_at)}
                                    </span>
                                </div>
                            </div>
                            <div class="activity-status flex-shrink-0">
                                <span class="badge-premium badge-premium-success">
                                    <i class="fas fa-check-circle me-1"></i>Linked
                                </span>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                $('#recentActivity').html(html);
            } else {
                $('#recentActivity').html(`
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-1">No recent activity</p>
                            <p class="text-muted small">Start linking vehicles to see activity here.</p>
                            <a href="link-vehicle.php" class="btn-premium btn-premium-primary btn-sm mt-2">
                                <i class="fas fa-link me-1"></i>Link a Vehicle
                            </a>
                        </div>
                    </div>
                `);
            }
        });
    }
    </script>
</body>
</html>