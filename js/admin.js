document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
    
    // Initialize charts
    initCharts();
    
    // Initialize data tables
    initDataTable();
});

function initCharts() {
    // Donation Statistics Chart
    const donationCtx = document.getElementById('donationChart').getContext('2d');
    const donationChart = new Chart(donationCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Food Rescue',
                data: [65, 59, 80, 81, 56, 55, 70, 85, 90, 95, 100, 110],
                backgroundColor: 'rgba(92, 107, 192, 0.2)',
                borderColor: 'rgba(92, 107, 192, 1)',
                borderWidth: 1,
                tension: 0.4,
                fill: true
            }, {
                label: 'Wishlist',
                data: [28, 48, 40, 39, 46, 47, 55, 60, 65, 70, 75, 80],
                backgroundColor: 'rgba(102, 187, 106, 0.2)',
                borderColor: 'rgba(102, 187, 106, 1)',
                borderWidth: 1,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Donation Type Chart
    const donationTypeCtx = document.getElementById('donationTypeChart').getContext('2d');
    const donationTypeChart = new Chart(donationTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Food Rescue', 'Wishlist'],
            datasets: [{
                data: [65, 35],
                backgroundColor: [
                    'rgba(92, 107, 192, 0.8)',
                    'rgba(102, 187, 106, 0.8)'
                ],
                borderColor: [
                    'rgba(92, 107, 192, 1)',
                    'rgba(102, 187, 106, 1)'
                ],
                borderWidth: 1
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

function initDataTable() {
    // This would initialize the data table with DataTables.net or similar library
    // For demo purposes, we'll just log that it would be initialized
    console.log('Data table would be initialized here');
}