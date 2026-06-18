/**
 * ============================================
 * MAGERWA - Premium SAAS Vehicle Tracking System
 * Enterprise-Grade JavaScript
 * ============================================
 */

(function() {
    'use strict';

    // ============================================
    // 1. Configuration
    // ============================================
    const CONFIG = {
        animationDuration: 300,
        toastDuration: 5000,
        apiBase: '/vehicle-tracking-system/api/',
        debounceDelay: 300,
        maxRetries: 3,
        retryDelay: 1000,
    };

    // ============================================
    // 2. State Management
    // ============================================
    const AppState = {
        currentPage: 1,
        totalPages: 1,
        perPage: 10,
        isLoading: false,
        notifications: [],
    };

    // ============================================
    // 3. Utility Functions
    // ============================================
    const Utils = {
        formatCurrency: (amount) => {
            return new Intl.NumberFormat('rw-RW', {
                style: 'currency',
                currency: 'RWF',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        },

        formatDate: (dateString) => {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
            if (diff < 86400000) return `${Math.floor(diff / 3600000)}h ago`;
            if (diff < 604800000) return `${Math.floor(diff / 86400000)}d ago`;
            
            return date.toLocaleDateString('en-RW', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        generateId: () => {
            return Date.now().toString(36) + Math.random().toString(36).substr(2, 5);
        },

        debounce: (func, wait = CONFIG.debounceDelay) => {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        isValidEmail: (email) => {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },

        isValidPhone: (phone) => {
            return /^((0|\+250|250)?[7,8,9,6][0-9]{8})$/.test(phone.replace(/\s/g, ''));
        },

        isValidNationalID: (nid) => {
            return /^[0-9]{16}$/.test(nid);
        },

        getUrlParam: (name) => {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        },

        generatePlateNumber: (prefix = 'RWA') => {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = prefix + '-';
            for (let i = 0; i < 6; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        },

        truncate: (text, length = 50) => {
            if (text.length <= length) return text;
            return text.substr(0, length) + '...';
        },

        escapeHtml: (text) => {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // ============================================
    // 4. Notification System
    // ============================================
    class NotificationSystem {
        constructor() {
            this.container = null;
            this.init();
        }

        init() {
            this.container = document.createElement('div');
            this.container.className = 'position-fixed top-0 end-0 p-3';
            this.container.style.zIndex = '9999';
            this.container.style.maxWidth = '400px';
            this.container.style.width = '100%';
            this.container.style.pointerEvents = 'none';
            document.body.appendChild(this.container);
        }

        show(message, type = 'info', duration = CONFIG.toastDuration) {
            const id = Utils.generateId();
            
            const toast = document.createElement('div');
            toast.className = 'toast-premium animate-fade-up mb-3';
            toast.style.pointerEvents = 'auto';
            toast.id = id;
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            const colors = {
                success: 'success',
                error: 'error',
                warning: 'warning',
                info: 'info'
            };

            toast.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <div class="toast-icon ${colors[type] || 'info'}">
                        <i class="fas ${icons[type] || 'fa-info-circle'}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0 fw-semibold">${message}</p>
                    </div>
                    <button class="btn btn-sm btn-link text-muted" onclick="window.notificationSystem.dismiss('${id}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            this.container.appendChild(toast);

            if (duration > 0) {
                setTimeout(() => {
                    this.dismiss(id);
                }, duration);
            }

            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });

            return id;
        }

        dismiss(id) {
            const toast = document.getElementById(id);
            if (!toast) return;
            
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }

        success(message, duration = CONFIG.toastDuration) {
            return this.show(message, 'success', duration);
        }

        error(message, duration = CONFIG.toastDuration) {
            return this.show(message, 'error', duration);
        }

        warning(message, duration = CONFIG.toastDuration) {
            return this.show(message, 'warning', duration);
        }

        info(message, duration = CONFIG.toastDuration) {
            return this.show(message, 'info', duration);
        }
    }

    // ============================================
    // 5. Loading Overlay
    // ============================================
    class LoadingOverlay {
        constructor() {
            this.overlay = null;
            this.init();
        }

        init() {
            this.overlay = document.createElement('div');
            this.overlay.className = 'position-fixed top-0 start-0 w-100 h-100 d-none';
            this.overlay.style.background = 'rgba(0,0,0,0.5)';
            this.overlay.style.backdropFilter = 'blur(4px)';
            this.overlay.style.zIndex = '9998';
            this.overlay.style.display = 'none';
            this.overlay.style.alignItems = 'center';
            this.overlay.style.justifyContent = 'center';
            
            this.overlay.innerHTML = `
                <div class="text-center">
                    <div class="spinner-premium mx-auto mb-3"></div>
                    <h5 class="text-white font-weight-light" id="loadingMessage">Loading...</h5>
                </div>
            `;
            
            document.body.appendChild(this.overlay);
        }

        show(message = 'Loading...') {
            this.overlay.style.display = 'flex';
            const msgEl = this.overlay.querySelector('#loadingMessage');
            if (msgEl) msgEl.textContent = message;
            document.body.style.overflow = 'hidden';
        }

        hide() {
            this.overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // ============================================
    // 6. API Client
    // ============================================
    class APIClient {
        constructor() {
            this.baseUrl = CONFIG.apiBase;
        }

        async request(endpoint, method = 'GET', data = null, retries = CONFIG.maxRetries) {
            try {
                const options = {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                };

                if (data && (method === 'POST' || method === 'PUT')) {
                    options.body = JSON.stringify(data);
                }

                const response = await fetch(this.baseUrl + endpoint, options);
                
                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Request failed');
                }

                return await response.json();
            } catch (error) {
                if (retries > 0) {
                    await new Promise(resolve => setTimeout(resolve, CONFIG.retryDelay));
                    return this.request(endpoint, method, data, retries - 1);
                }
                throw error;
            }
        }

        get(endpoint) {
            return this.request(endpoint, 'GET');
        }

        post(endpoint, data) {
            return this.request(endpoint, 'POST', data);
        }

        put(endpoint, data) {
            return this.request(endpoint, 'PUT', data);
        }

        delete(endpoint) {
            return this.request(endpoint, 'DELETE');
        }
    }

    // ============================================
    // 7. Main Application Class
    // ============================================
    class MAGERWAApp {
        constructor() {
            this.api = new APIClient();
            this.notifications = new NotificationSystem();
            this.loading = new LoadingOverlay();
            this.currentPage = 1;
            this.init();
        }

        init() {
            this.initNavigation();
            this.initForms();
            this.initTables();
            this.initPagination();
            this.initSearch();
            this.loadDashboardStats();
        }

        initNavigation() {
            // Hamburger menu handler
            const toggler = document.querySelector('.navbar-toggler');
            const collapse = document.querySelector('.navbar-collapse');
            
            if (toggler && collapse) {
                toggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    collapse.classList.toggle('show');
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-bars');
                        icon.classList.toggle('fa-times');
                    }
                });
            }

            document.querySelectorAll('.navbar-premium .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992 && collapse) {
                        collapse.classList.remove('show');
                        const icon = toggler?.querySelector('i');
                        if (icon) {
                            icon.classList.add('fa-bars');
                            icon.classList.remove('fa-times');
                        }
                    }
                });
            });

            const navbar = document.querySelector('.navbar-premium');
            if (navbar) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }

            const currentPath = window.location.pathname;
            document.querySelectorAll('.navbar-premium .nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href)) {
                    link.classList.add('active');
                }
            });
        }

        initForms() {
            document.querySelectorAll('.form-premium .form-control').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        }

        initTables() {
            document.querySelectorAll('.table-premium thead th').forEach(th => {
                th.style.cursor = 'pointer';
                th.addEventListener('click', function() {
                    const table = this.closest('table');
                    const index = Array.from(this.parentElement.children).indexOf(this);
                    const rows = Array.from(table.querySelectorAll('tbody tr'));
                    const isAsc = this.dataset.sort === 'asc';
                    
                    table.querySelectorAll('thead th').forEach(h => {
                        h.dataset.sort = '';
                        h.querySelector('.sort-icon')?.remove();
                    });
                    
                    rows.sort((a, b) => {
                        const aVal = a.children[index]?.textContent?.trim() || '';
                        const bVal = b.children[index]?.textContent?.trim() || '';
                        return isAsc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
                    });
                    
                    const tbody = table.querySelector('tbody');
                    rows.forEach(row => tbody.appendChild(row));
                    
                    this.dataset.sort = isAsc ? 'desc' : 'asc';
                    const icon = document.createElement('i');
                    icon.className = `fas fa-sort-${isAsc ? 'up' : 'down'} sort-icon ms-1`;
                    this.appendChild(icon);
                });
            });
        }

        initPagination() {
            document.querySelectorAll('[data-pagination]').forEach(container => {
                const total = parseInt(container.dataset.total) || 0;
                const current = parseInt(container.dataset.current) || 1;
                const perPage = parseInt(container.dataset.perPage) || 10;
                this.renderPagination(container, total, current, perPage);
            });
        }

        renderPagination(container, total, current, perPage) {
            const totalPages = Math.ceil(total / perPage);
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<ul class="pagination pagination-premium justify-content-center">';
            
            html += `<li class="page-item ${current <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${current - 1}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>`;

            const startPage = Math.max(1, current - 2);
            const endPage = Math.min(totalPages, current + 2);

            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                if (startPage > 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${i === current ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
            }

            html += `<li class="page-item ${current >= totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${current + 1}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>`;

            html += '</ul>';
            container.innerHTML = html;

            container.querySelectorAll('[data-page]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const page = parseInt(e.target.closest('[data-page]').dataset.page);
                    if (page >= 1 && page <= totalPages) {
                        const event = new CustomEvent('pageChange', { detail: { page } });
                        container.dispatchEvent(event);
                    }
                });
            });
        }

        initSearch() {
            const searchInput = document.querySelector('[data-search]');
            if (searchInput) {
                const debouncedSearch = Utils.debounce((query) => {
                    const event = new CustomEvent('search', { 
                        detail: { query } 
                    });
                    searchInput.dispatchEvent(event);
                }, 300);

                searchInput.addEventListener('input', (e) => {
                    debouncedSearch(e.target.value);
                });
            }
        }

        // FIXED: Dashboard Stats Loading
        loadDashboardStats() {
            // Load clients count
            $.get('../api/clients.php', function(data) {
                if (data.success) {
                    const total = data.pagination.total;
                    $('#totalClients').text(total);
                    $('#statClients').text(total);
                    const percent = Math.min((total / 100) * 100, 100);
                    $('#progressClients').css('width', percent + '%');
                }
            });

            // Load vehicles count
            $.get('../api/vehicles.php', function(data) {
                if (data.success) {
                    const total = data.pagination.total;
                    $('#totalVehicles').text(total);
                    $('#statVehicles').text(total);
                    const percent = Math.min((total / 100) * 100, 100);
                    $('#progressVehicles').css('width', percent + '%');
                }
            });

            // Load linked vehicles count
            $.get('../api/links.php', function(data) {
                if (data.success) {
                    const total = data.pagination.total;
                    $('#totalLinked').text(total);
                    $('#statLinked').text(total);
                    const percent = Math.min((total / 100) * 100, 100);
                    $('#progressLinked').css('width', percent + '%');
                }
            });

            // Load available vehicles
            $.get('../api/links.php?available=true', function(data) {
                if (data.success) {
                    $('#totalAvailable').text(data.data.length);
                }
            });
        }
    }

    // ============================================
    // 8. Global CRUD Functions
    // ============================================
    
    // Client CRUD
    window.deleteClient = function(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            $.ajax({
                url: `../api/clients.php?id=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(response.message);
                        location.reload();
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                },
                error: function() {
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                }
            });
        }
    };

    window.editClient = function(id) {
        window.MAGERWA.app.notifications.info('Edit functionality coming soon!');
    };

    // Vehicle CRUD
    window.deleteVehicle = function(id, chassis) {
        if (confirm(`Are you sure you want to delete vehicle with chassis "${chassis}"? This action cannot be undone.`)) {
            $.ajax({
                url: `../api/vehicles.php?id=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(response.message);
                        location.reload();
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                },
                error: function() {
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                }
            });
        }
    };

    window.editVehicle = function(id) {
        window.MAGERWA.app.notifications.info('Edit functionality coming soon!');
    };

    // Link CRUD
    window.unlinkVehicle = function(id, plate) {
        if (confirm(`Are you sure you want to unlink vehicle with plate "${plate}"?`)) {
            $.ajax({
                url: `../api/links.php?id=${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(response.message);
                        location.reload();
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                },
                error: function() {
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                }
            });
        }
    };

    window.editPlate = function(id, currentPlate) {
        const newPlate = prompt('Enter new plate number:', currentPlate);
        if (newPlate && newPlate !== currentPlate) {
            $.ajax({
                url: `../api/links.php?id=${id}`,
                method: 'PUT',
                data: JSON.stringify({ plate_number: newPlate }),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        window.MAGERWA.app.notifications.success(response.message);
                        location.reload();
                    } else {
                        window.MAGERWA.app.notifications.error(response.message);
                    }
                },
                error: function() {
                    window.MAGERWA.app.notifications.error('An error occurred. Please try again.');
                }
            });
        }
    };

    // ============================================
    // 9. Initialize Application
    // ============================================
    const app = new MAGERWAApp();
    
    window.MAGERWA = {
        app,
        Utils,
        NotificationSystem,
        LoadingOverlay,
        APIClient,
        AppState,
        CONFIG
    };

    window.notificationSystem = app.notifications;

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.animate-on-load').forEach(el => {
            el.classList.add('animate-fade-up');
        });
        
        document.querySelectorAll('.stagger-children').forEach(el => {
            el.classList.add('stagger-children');
        });
        
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
        
        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
            new bootstrap.Popover(el);
        });
    });

})();