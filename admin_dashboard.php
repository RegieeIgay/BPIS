<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      padding-top: 70px;
    }
    .chart-container {
  position: relative;
  height: 350px; /* was 500px */
  width: 100%;
}

  </style>
</head>
<body>

  <!-- Include Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Main Content with Sidebar -->
  <div class="d-flex">
    <?php include 'sidebar.php'; ?>

    <div class="flex-grow-1 p-4">
      
      <!-- Dashcards Section -->
      <div class="row g-4 mb-4">
        <!-- Total Registered Businesses -->
        <div class="col-md-4">
          <div class="card text-white bg-primary h-100 shadow">
            <div class="card-body">
              <h5 class="card-title">
                <i class="fas fa-briefcase me-2"></i> Total Registered Businesses
              </h5>
              <p class="display-5 fw-bold">120</p>
            </div>
          </div>
        </div>

        <!-- Active Businesses -->
        <div class="col-md-4">
          <div class="card text-white bg-success h-100 shadow">
            <div class="card-body">
              <h5 class="card-title">
                <i class="fas fa-check-circle me-2"></i> Active Businesses
              </h5>
              <p class="display-5 fw-bold">95</p>
            </div>
          </div>
        </div>

        <!-- Expired Businesses -->
        <div class="col-md-4">
          <div class="card text-white bg-danger h-100 shadow">
            <div class="card-body">
              <h5 class="card-title">
                <i class="fas fa-times-circle me-2"></i> Expired Businesses
              </h5>
              <p class="display-5 fw-bold">25</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="row g-4">
        
        <!-- Line Chart -->
        <div class="col-md-6">
          <div class="card shadow" style="min-height: 600px;">
            <div class="card-body">
              <h5 class="card-title mb-4">Business Registrations (Weekly)</h5>
              <div class="chart-container">
                <canvas id="registrationChart"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Bar Chart -->
        <div class="col-md-6">
          <div class="card shadow" style="min-height: 600px;">
            <div class="card-body">
              <h5 class="card-title mb-4">Active vs Expired Businesses</h5>
              <div class="chart-container">
                <canvas id="statusChart"></canvas>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Chart.js Scripts -->
  <script>
    // Line Chart (Registrations per Day)
    const lineCtx = document.getElementById('registrationChart').getContext('2d');
    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: ['April 1', 'April 2', 'April 3', 'April 4', 'April 5', 'April 6', 'April 7'],
        datasets: [{
          label: 'New Registrations',
          data: [12, 19, 8, 15, 10, 14, 18],
          fill: false,
          borderColor: 'rgb(75, 192, 192)',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: 'Businesses' }
          },
          x: {
            title: { display: true, text: 'Date' }
          }
        }
      }
    });

    // Bar Chart (Active vs Expired)
    const barCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['Active Businesses', 'Expired Businesses'],
        datasets: [{
          label: 'Total Count',
          data: [95, 25],
          backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(220, 53, 69, 0.7)'],
          borderColor: ['rgba(40, 167, 69, 1)', 'rgba(220, 53, 69, 1)'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: 'Number of Businesses' }
          }
        }
      }
    });
  </script>

</body>
</html>
