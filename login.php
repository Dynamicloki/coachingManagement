<?php
include "dbms.php";
include "includes/top.php";
?>
<title>Sign In - Apex Coaching Institute</title>

<!-- Three.js Ambient Background Canvas -->
<div id="three-login-canvas" class="three-canvas-container" style="position: fixed; z-index: 0; background: radial-gradient(circle at 50% 50%, #1e1b4b 0%, #0f172a 100%);"></div>

<div class="container d-flex align-items-center justify-content-center min-vh-100 position-relative py-5" style="z-index: 10;">
  <div class="col-12 col-sm-10 col-md-8 col-lg-5 animate-fade-in">
    
    <!-- Brand Header -->
    <div class="text-center mb-4">
      <a href="index.php" class="text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
        <span class="fs-1">🎓</span>
        <span class="fs-3 fw-bold text-white">Apex Coaching</span>
      </a>
      <p class="text-white-50 small m-0">Sign in to your Student Portal or Admin Management</p>
    </div>

    <!-- Glassmorphic Login Card -->
    <div class="glass-card-dark p-4 p-md-5 shadow-lg border border-white-10">
      
      <!-- Role Switcher Pills -->
      <div class="d-flex p-1 bg-dark bg-opacity-50 rounded-pill mb-4 border border-white-10">
        <button type="button" class="btn btn-sm flex-fill rounded-pill py-2 fw-semibold text-white active" id="btnStudentTab" onclick="switchLoginMode('student')">
          <i class="bi bi-mortarboard me-1"></i> Student Portal
        </button>
        <button type="button" class="btn btn-sm flex-fill rounded-pill py-2 fw-semibold text-white-50" id="btnAdminTab" onclick="switchLoginMode('admin')">
          <i class="bi bi-shield-lock me-1"></i> Admin Panel
        </button>
      </div>

      <!-- Alerts -->
      <?php if (isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-25 text-white" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <?php
            if ($_GET['err'] === 'admin_required') echo "Administrator privileges required to access that page.";
            elseif ($_GET['err'] === 'student_required') echo "Student session required. Please sign in as student.";
            else echo "Invalid credentials. Please verify your login details.";
          ?>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logout'): ?>
        <div class="alert alert-info alert-dismissible fade show border-0 bg-info bg-opacity-25 text-white" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i> You have logged out successfully.
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Form -->
      <form id="loginForm" method="POST" action="login-process.php">
        <div class="mb-3">
          <label id="inputLabel" for="loginInput" class="form-label text-white-50 small fw-semibold">
            Student Email or Mobile Number
          </label>
          <div class="input-group">
            <span class="input-group-text bg-dark border-secondary text-white-50"><i class="bi bi-person"></i></span>
            <input type="text" id="loginInput" name="email" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="e.g. student@test.com" required>
          </div>
        </div>

        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="loginPassword" class="form-label text-white-50 small fw-semibold m-0">Password</label>
            <a href="#" class="text-white-50 small text-decoration-none" onclick="alert('Please contact your administrator or teacher to reset credentials.'); return false;">Forgot?</a>
          </div>
          <div class="input-group">
            <span class="input-group-text bg-dark border-secondary text-white-50"><i class="bi bi-key"></i></span>
            <input type="password" id="loginPassword" name="password" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Enter password" required>
          </div>
        </div>

        <div class="d-grid mb-3">
          <button type="submit" id="btnSubmit" class="btn btn-gradient-primary btn-lg fw-bold py-3">
            Sign In to Student Portal
          </button>
        </div>
      </form>

      <!-- 1-Click Demo Credentials Quick Fill -->
      <div class="mt-4 pt-3 border-top border-white-10 text-center">
        <span class="text-white-50 small d-block mb-2">⚡ Quick 1-Click Demo Testing:</span>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-3" onclick="fillDemo('student@test.com', 'student123', 'student')">
            <i class="bi bi-person-check me-1"></i> Demo Student
          </button>
          <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3" onclick="fillDemo('admin', 'admin123', 'admin')">
            <i class="bi bi-shield-check me-1"></i> Demo Admin
          </button>
        </div>
      </div>
    </div>

    <!-- Back to Homepage Link -->
    <div class="text-center mt-4">
      <a href="index.php" class="text-white-50 text-decoration-none small">
        &larr; Back to Coaching Homepage
      </a>
    </div>

  </div>
</div>

<!-- Switch Mode & Demo Filler Script -->
<script>
function switchLoginMode(mode) {
    const btnStudent = document.getElementById("btnStudentTab");
    const btnAdmin = document.getElementById("btnAdminTab");
    const label = document.getElementById("inputLabel");
    const input = document.getElementById("loginInput");
    const submitBtn = document.getElementById("btnSubmit");

    if (mode === 'admin') {
        btnAdmin.classList.add("active", "btn-primary");
        btnAdmin.classList.remove("text-white-50");
        btnStudent.classList.remove("active", "btn-primary");
        btnStudent.classList.add("text-white-50");

        label.innerText = "Administrator Username";
        input.placeholder = "e.g. admin";
        submitBtn.innerText = "Sign In to Admin Panel";
    } else {
        btnStudent.classList.add("active", "btn-primary");
        btnStudent.classList.remove("text-white-50");
        btnAdmin.classList.remove("active", "btn-primary");
        btnAdmin.classList.add("text-white-50");

        label.innerText = "Student Email or Mobile Number";
        input.placeholder = "e.g. student@test.com";
        submitBtn.innerText = "Sign In to Student Portal";
    }
}

function fillDemo(user, pass, mode) {
    switchLoginMode(mode);
    document.getElementById("loginInput").value = user;
    document.getElementById("loginPassword").value = pass;
}
</script>

<!-- Three.js Ambient Particle Motion Background -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("three-login-canvas");
    if (!container || typeof THREE === "undefined") return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 25;

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Dynamic 3D Geometric Ring / Torus Knot
    const knotGeometry = new THREE.TorusKnotGeometry(8, 2, 80, 16);
    const knotMaterial = new THREE.MeshBasicMaterial({
        color: 0x4f46e5,
        wireframe: true,
        transparent: true,
        opacity: 0.18
    });
    const knot = new THREE.Mesh(knotGeometry, knotMaterial);
    knot.position.set(0, 0, -10);
    scene.add(knot);

    // Ambient floating starfield particles
    const particleCount = 250;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);

    for (let i = 0; i < particleCount * 3; i += 3) {
        positions[i] = (Math.random() - 0.5) * 80;
        positions[i + 1] = (Math.random() - 0.5) * 60;
        positions[i + 2] = (Math.random() - 0.5) * 40;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    const material = new THREE.PointsMaterial({
        color: 0x38bdf8,
        size: 1.0,
        transparent: true,
        opacity: 0.6,
        blending: THREE.AdditiveBlending
    });

    const starfield = new THREE.Points(geometry, material);
    scene.add(starfield);

    // Mouse Tracking for dynamic camera tilt
    let mouseX = 0, mouseY = 0;
    window.addEventListener("mousemove", (e) => {
        mouseX = (e.clientX - window.innerWidth / 2) * 0.005;
        mouseY = (e.clientY - window.innerHeight / 2) * 0.005;
    });

    window.addEventListener("resize", () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    function renderLoop() {
        requestAnimationFrame(renderLoop);
        knot.rotation.x += 0.003;
        knot.rotation.y += 0.005;

        starfield.rotation.y -= 0.0008;

        camera.position.x += (mouseX - camera.position.x) * 0.05;
        camera.position.y += (-mouseY - camera.position.y) * 0.05;
        camera.lookAt(scene.position);

        renderer.render(scene, camera);
    }
    renderLoop();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
