document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    toggleSidebarBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');

        // On mobile, toggle active class to show/hide
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(event.target) && !toggleSidebarBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });

    // Initialize Charts
    initializeDonationTrendChart();
    initializeDonationCategoryChart();
});

// Donation Trend Chart (Line Chart)
function initializeDonationTrendChart() {
    const ctx = document.getElementById('donationTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Jumlah Donasi',
                data: [65, 78, 90, 81, 96, 115],
                borderColor: '#000957',
                backgroundColor: 'rgba(0, 9, 87, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Donation Category Chart (Doughnut Chart)
function initializeDonationCategoryChart() {
    const ctx = document.getElementById('donationCategoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Makanan', 'Barang', 'Uang'],
            datasets: [{
                data: [300, 150, 100],
                backgroundColor: [
                    '#000957',
                    '#17a2b8',
                    '#28a745'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}