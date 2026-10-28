<?php
include "dbms.php";
include "includes/top.php";

// Fetch total students and subjects for live badge counts
$studentCount = $conn->query("SELECT COUNT(*) FROM tblstudent2")->fetchColumn() ?: 1200;
$classCount = $conn->query("SELECT COUNT(*) FROM tblclass")->fetchColumn() ?: 6;
$subjectCount = $conn->query("SELECT COUNT(*) FROM tblsubject")->fetchColumn() ?: 8;
?>
<title>Apex Coaching Institute - Premier Academic Mentorship</title>

<!-- Modern Navbar -->
<nav class="navbar navbar-expand-lg modern-nav sticky-top py-3">
  <div class="container">
    <a class="navbar-brand modern-nav-brand d-flex align-items-center gap-2" href="index.php">
      <span class="fs-4">🎓</span>
      <span>Apex Coaching</span>
    </a>
    <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list fs-2 text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">
        <li class="nav-item"><a class="nav-link text-white-50 hover-text-white" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link text-white-50 hover-text-white" href="#courses">Courses</a></li>
        <li class="nav-item"><a class="nav-link text-white-50 hover-text-white" href="#portal">Student Portal</a></li>
        <li class="nav-item"><a class="nav-link text-white-50 hover-text-white" href="#results">Results</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
          <?php if (($_SESSION['role'] ?? '') === 'student'): ?>
            <a href="student-dashboard.php" class="btn btn-gradient-primary">Student Dashboard</a>
          <?php else: ?>
            <a href="table.php" class="btn btn-gradient-primary">Admin Panel</a>
          <?php endif; ?>
          <a href="logout.php" class="btn btn-outline-light btn-sm ms-2">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-gradient-secondary">Sign In</a>
          <a href="login.php" class="btn btn-gradient-primary">Student Portal &rarr;</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- 3D Hero Section -->
<header class="hero-section">
  <!-- Three.js Canvas Container -->
  <div id="three-hero-canvas" class="three-canvas-container"></div>

  <div class="container hero-content">
    <div class="row align-items-center g-5">
      <div class="col-lg-7 animate-fade-in">
        <div class="mb-3">
          <span class="badge-glow">
            <span class="spinner-grow spinner-grow-sm text-info" role="status"></span>
            Admissions Open For Academic Year 2026-27
          </span>
        </div>
        <h1 class="hero-title mb-4">
          Empowering Minds, <br>
          Engineering Success.
        </h1>
        <p class="lead text-light mb-4 fs-5" style="max-width: 580px; opacity: 0.9;">
          Experience interactive learning, personalized performance tracking, and expert mentorship. Built with modern technology to help students excel in board & competitive examinations.
        </p>
        <div class="d-flex flex-wrap gap-3 mb-5">
          <a href="login.php" class="btn btn-gradient-primary btn-lg px-4 py-3">
            <i class="bi bi-person-workspace me-2"></i> Enter Student Portal
          </a>
          <a href="#courses" class="btn btn-gradient-secondary btn-lg px-4 py-3">
            <i class="bi bi-mortarboard me-2"></i> Explore Batches
          </a>
        </div>

        <!-- Quick Live Stats -->
        <div class="row g-4 pt-3 border-top border-white-10">
          <div class="col-4">
            <div class="stat-number">98.6%</div>
            <div class="stat-label">Pass Percentage</div>
          </div>
          <div class="col-4">
            <div class="stat-number">1,200+</div>
            <div class="stat-label">Enrolled Students</div>
          </div>
          <div class="col-4">
            <div class="stat-number">100%</div>
            <div class="stat-label">Individual Tracking</div>
          </div>
        </div>
      </div>

      <!-- Hero 3D Card / Visual Showcase -->
      <div class="col-lg-5 d-none d-lg-block animate-float">
        <div class="glass-card-dark p-4 shadow-lg border border-white-10">
          <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-white-10">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-success p-2 rounded-circle"></span>
              <span class="fw-bold">Student Portal Live</span>
            </div>
            <span class="text-white-50 small">Session 2026-27</span>
          </div>

          <div class="p-3 rounded-4 mb-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-white-50 small">Mathematics Test 1</span>
              <span class="badge bg-primary">92% (Grade A+)</span>
            </div>
            <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1);">
              <div class="progress-bar bg-info" role="progressbar" style="width: 92%"></div>
            </div>
          </div>

          <div class="p-3 rounded-4 mb-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-white-50 small">Physics & Chemistry</span>
              <span class="badge bg-success">88.5% (Grade A)</span>
            </div>
            <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1);">
              <div class="progress-bar bg-success" role="progressbar" style="width: 88.5%"></div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center pt-2">
            <span class="small text-white-50">Instant Grade Reporting</span>
            <a href="login.php" class="text-info text-decoration-none small fw-bold">Sign In &rarr;</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Courses & Batches Section -->
<section id="courses" class="py-5 bg-white">
  <div class="container py-4">
    <div class="text-center mb-5">
      <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill mb-2">Academic Programs</span>
      <h2 class="fw-bold fs-1">Structured Batches For Every Goal</h2>
      <p class="text-muted mx-auto" style="max-width: 600px;">
        Curriculum tailored to school board syllabus, competitive olympiads, and foundation building.
      </p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="glass-card p-4 h-100 border">
          <div class="icon-box icon-box-primary">
            <i class="bi bi-book"></i>
          </div>
          <h4 class="fw-bold mb-2">Class 9 & 10 Foundation</h4>
          <p class="text-muted small mb-4">
            Master fundamental science, mathematics, and analytical reasoning with conceptual clarity and weekly chapter assessments.
          </p>
          <ul class="list-unstyled small text-muted mb-4 d-flex flex-column gap-2">
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> NCERT & Advanced Problem Solving</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Weekly Unit Tests & Feedback</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Olympiad & NTSE Orientation</li>
          </ul>
          <a href="login.php" class="btn btn-outline-primary w-100 rounded-pill">Enroll in Batch</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="glass-card p-4 h-100 border position-relative" style="background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);">
          <span class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">Most Popular</span>
          <div class="icon-box icon-box-success">
            <i class="bi bi-calculator"></i>
          </div>
          <h4 class="fw-bold mb-2">Class 11 & 12 Board & JEE/NEET</h4>
          <p class="text-muted small mb-4">
            Comprehensive PCM/PCB syllabus coverage integrated with speed-solving strategies and full-length simulated test papers.
          </p>
          <ul class="list-unstyled small text-muted mb-4 d-flex flex-column gap-2">
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Daily Doubt Clearing Counters</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> AI-Assisted Performance Reports</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Regular Parent-Teacher Conferences</li>
          </ul>
          <a href="login.php" class="btn btn-success w-100 rounded-pill">Enroll in Batch</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="glass-card p-4 h-100 border">
          <div class="icon-box icon-box-accent">
            <i class="bi bi-stars"></i>
          </div>
          <h4 class="fw-bold mb-2">Intensive Crash & Test Series</h4>
          <p class="text-muted small mb-4">
            High-yield revision sessions, formula mastery workshops, and timed test series with detailed rank breakdown.
          </p>
          <ul class="list-unstyled small text-muted mb-4 d-flex flex-column gap-2">
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> 50+ Mock Examination Papers</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Subject-wise Error Analysis</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i> Direct Teacher Consultations</li>
          </ul>
          <a href="login.php" class="btn btn-outline-warning text-dark w-100 rounded-pill">Enroll in Batch</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Student Portal Showcase Section -->
<section id="portal" class="py-5" style="background: #0f172a; color: #ffffff;">
  <div class="container py-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3">Self-Service Portal</span>
        <h2 class="fw-bold fs-1 mb-4">Every Student Gets A Dedicated Digital Portal</h2>
        <p class="text-light-50 fs-5 mb-4" style="color: #94a3b8;">
          Students can log in anytime to review their grades, check performance across subjects, and keep their personal profile updated in real-time.
        </p>

        <div class="d-flex flex-column gap-3 mb-4">
          <div class="d-flex gap-3 align-items-start">
            <div class="icon-box icon-box-primary flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
              <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
              <h5 class="fw-bold text-white mb-1">Subject-Wise Test Scores</h5>
              <p class="small mb-0" style="color: #94a3b8;">Track obtained marks, percentage, and teacher feedback for every test.</p>
            </div>
          </div>

          <div class="d-flex gap-3 align-items-start">
            <div class="icon-box icon-box-secondary flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
              <i class="bi bi-person-lines-fill"></i>
            </div>
            <div>
              <h5 class="fw-bold text-white mb-1">Live Profile Management</h5>
              <p class="small mb-0" style="color: #94a3b8;">Update contact information, home address, and guardian phone numbers securely.</p>
            </div>
          </div>

          <div class="d-flex gap-3 align-items-start">
            <div class="icon-box icon-box-success flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
              <i class="bi bi-shield-check"></i>
            </div>
            <div>
              <h5 class="fw-bold text-white mb-1">Strict Isolation & Privacy</h5>
              <p class="small mb-0" style="color: #94a3b8;">Students have zero connection to administrative controls, ensuring clean data segregation.</p>
            </div>
          </div>
        </div>

        <a href="login.php" class="btn btn-gradient-primary btn-lg">Access Student Portal Now &rarr;</a>
      </div>

      <div class="col-lg-6">
        <div class="glass-card-dark p-4 p-md-5">
          <h4 class="fw-bold text-white mb-4">Quick Portal Access</h4>
          <p class="small text-white-50 mb-4">Enter your enrolled email or mobile number with your student password:</p>

          <form action="login-process.php" method="POST">
            <div class="mb-3">
              <label class="form-label text-white-50 small">Student Email or Phone</label>
              <input type="text" name="email" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="e.g. student@test.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label text-white-50 small">Password</label>
              <input type="password" name="password" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Enter student password" required>
            </div>
            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-gradient-primary btn-lg">Sign In to Dashboard</button>
            </div>
          </form>

          <div class="mt-4 pt-3 border-top border-white-10 text-center">
            <small class="text-white-50">Are you an administrator? <a href="login.php" class="text-info text-decoration-none">Admin Login</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-5 border-top border-white-10">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-5">
        <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
          <span>🎓</span> Apex Coaching Institute
        </h4>
        <p class="text-white-50 small" style="max-width: 400px;">
          Empowering future scientists, doctors, and engineers through innovative learning methodologies and continuous academic guidance.
        </p>
      </div>
      <div class="col-md-3 col-6">
        <h6 class="fw-bold text-uppercase text-white-50 mb-3">Quick Navigation</h6>
        <ul class="list-unstyled small d-flex flex-column gap-2">
          <li><a href="index.php" class="text-white-50 text-decoration-none hover-text-white">Home</a></li>
          <li><a href="login.php" class="text-white-50 text-decoration-none hover-text-white">Student Login</a></li>
          <li><a href="login.php" class="text-white-50 text-decoration-none hover-text-white">Admin Portal</a></li>
        </ul>
      </div>
      <div class="col-md-4 col-6">
        <h6 class="fw-bold text-uppercase text-white-50 mb-3">Campus Contact</h6>
        <p class="small text-white-50 mb-1"><i class="bi bi-geo-alt me-2"></i> 123 Knowledge Park, Education Hub</p>
        <p class="small text-white-50 mb-1"><i class="bi bi-telephone me-2"></i> +91 98765 43210</p>
        <p class="small text-white-50"><i class="bi bi-envelope me-2"></i> info@apexcoaching.edu</p>
      </div>
    </div>
    <div class="pt-4 mt-4 border-top border-white-10 text-center text-white-50 small">
      &copy; <?php echo date('Y'); ?> Apex Coaching Institute. All rights reserved.
    </div>
  </div>
</footer>

<!-- Three.js Interactive 3D Background Script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("three-hero-canvas");
    if (!container || typeof THREE === "undefined") return;

    // Scene & Camera
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.z = 30;

    // WebGL Renderer
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Particle Constellation Geometry
    const particleCount = 200;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);

    for (let i = 0; i < particleCount * 3; i += 3) {
        positions[i] = (Math.random() - 0.5) * 80;
        positions[i + 1] = (Math.random() - 0.5) * 50;
        positions[i + 2] = (Math.random() - 0.5) * 40;

        // Indigo and cyan hues
        colors[i] = 0.3 + Math.random() * 0.4;
        colors[i + 1] = 0.5 + Math.random() * 0.5;
        colors[i + 2] = 0.9 + Math.random() * 0.1;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const material = new THREE.PointsMaterial({
        size: 1.2,
        vertexColors: true,
        transparent: true,
        opacity: 0.7,
        blending: THREE.AdditiveBlending
    });

    const particles = new THREE.Points(geometry, material);
    scene.add(particles);

    // Floating 3D Geometric Nodes (Icosahedra)
    const nodeGeometry = new THREE.IcosahedronGeometry(2.5, 0);
    const nodeMaterial = new THREE.MeshBasicMaterial({
        color: 0x6366f1,
        wireframe: true,
        transparent: true,
        opacity: 0.4
    });

    const nodes = [];
    for (let j = 0; j < 5; j++) {
        const mesh = new THREE.Mesh(nodeGeometry, nodeMaterial);
        mesh.position.set(
            (Math.random() - 0.5) * 40,
            (Math.random() - 0.5) * 20,
            (Math.random() - 0.5) * 15
        );
        mesh.scale.setScalar(0.6 + Math.random() * 0.8);
        scene.add(mesh);
        nodes.push({
            mesh: mesh,
            rotSpeedX: (Math.random() - 0.5) * 0.015,
            rotSpeedY: (Math.random() - 0.5) * 0.015
        });
    }

    // Mouse Interaction
    let mouseX = 0, mouseY = 0;
    let targetX = 0, targetY = 0;
    const windowHalfX = window.innerWidth / 2;
    const windowHalfY = window.innerHeight / 2;

    window.addEventListener("mousemove", (event) => {
        mouseX = (event.clientX - windowHalfX) * 0.015;
        mouseY = (event.clientY - windowHalfY) * 0.015;
    });

    // Window Resize
    window.addEventListener("resize", () => {
        if (!container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);

        targetX += (mouseX - targetX) * 0.05;
        targetY += (mouseY - targetY) * 0.05;

        particles.rotation.y += 0.001;
        particles.rotation.x = targetY * 0.1;
        particles.rotation.y += targetX * 0.05;

        nodes.forEach(n => {
            n.mesh.rotation.x += n.rotSpeedX;
            n.mesh.rotation.y += n.rotSpeedY;
        });

        renderer.render(scene, camera);
    }
    animate();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
