  </head>
  <body class="bg-light">
    <!-- Modern Student Portal Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top py-3 modern-nav shadow-sm mb-4">
      <div class="container">
        <a class="navbar-brand modern-nav-brand d-flex align-items-center gap-2" href="student-dashboard.php">
          <span class="fs-4">🎓</span>
          <span>Apex Student Portal</span>
        </a>
        <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#studentNav" aria-controls="studentNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="bi bi-list fs-2 text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="studentNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-2 ms-lg-3">
            <li class="nav-item">
              <a class="nav-link text-white-50 px-3 py-2 rounded-pill hover-text-white d-flex align-items-center gap-2" href="student-dashboard.php">
                <i class="bi bi-person-badge"></i> My Profile
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-3 py-2 rounded-pill hover-text-white d-flex align-items-center gap-2" href="student-results.php">
                <i class="bi bi-graph-up-arrow"></i> My Test Results
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 px-3 py-2 rounded-pill hover-text-white d-flex align-items-center gap-2" href="student-profile-edit.php">
                <i class="bi bi-pencil-square"></i> Edit Details
              </a>
            </li>
          </ul>
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 bg-dark bg-opacity-50 px-3 py-2 rounded-pill border border-white-10">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                <?php echo strtoupper(substr($_SESSION['studentname'] ?? 'S', 0, 1)); ?>
              </div>
              <span class="text-white small fw-semibold">
                <?php echo htmlspecialchars($_SESSION['studentname'] ?? 'Student'); ?>
              </span>
            </div>
            <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
              <i class="bi bi-box-arrow-right me-1"></i> Sign Out
            </a>
          </div>
        </div>
      </div>
    </nav>
