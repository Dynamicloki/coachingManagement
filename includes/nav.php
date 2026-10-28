  </head>
  <body class="bg-light">
    <!-- Modern Admin Suite Top Bar -->
    <nav class="navbar navbar-expand-lg modern-nav sticky-top py-2 shadow-sm border-bottom border-secondary border-opacity-10">
      <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand modern-nav-brand d-flex align-items-center gap-2" href="table.php">
          <span class="fs-4">⚡</span>
          <span>Apex Admin Panel</span>
        </a>

        <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarContent" aria-controls="adminNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <i class="bi bi-list fs-2 text-white"></i>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbarContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-lg-1">
            <li class="nav-item">
              <a class="nav-link text-white-50 px-2 hover-text-white" href="index.php" target="_blank" title="Preview Public Site">
                <i class="bi bi-box-arrow-up-right me-1"></i> Public Site
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-2 hover-text-white" href="table.php">Students</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-2 hover-text-white" href="class-table.php">Classes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-2 hover-text-white" href="subjects-table.php">Subjects</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-2 hover-text-white" href="test-results-table.php">Test Results</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-2 hover-text-white" href="logs.php">Activity Logs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-2 hover-text-white" href="backups.php">Backups</a>
            </li>
          </ul>

          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 bg-dark bg-opacity-50 px-3 py-1 rounded-pill border border-white-10">
              <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.8rem;">
                <i class="bi bi-shield-lock"></i>
              </div>
              <span class="text-white small fw-semibold">
                <?php echo htmlspecialchars($_SESSION['adminusername'] ?? 'Admin'); ?>
              </span>
            </div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
              <i class="bi bi-power me-1"></i> Logout
            </a>
          </div>
        </div>
      </div>
    </nav>
