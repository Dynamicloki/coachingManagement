# 🎓 Apex Coaching Institute Management System (v2.0)

[![PHP](https://img.shields.io/badge/PHP-8.3-777bb4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![SQLite](https://img.shields.io/badge/Database-SQLite_3-003B57.svg?style=flat-square&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Three.js](https://img.shields.io/badge/3D_Graphics-Three.js_r128-black.svg?style=flat-square&logo=three.js&logoColor=white)](https://threejs.org/)
[![Bootstrap](https://img.shields.io/badge/UI_Framework-Bootstrap_5-7952B3.svg?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Tests](https://img.shields.io/badge/Automated_Tests-26_Passed_%7C_100%25-brightgreen.svg?style=flat-square)]()
[![Security](https://img.shields.io/badge/Security-RBAC_%7C_Anti--SQLi_%7C_Audit_Logs-blue.svg?style=flat-square)]()

A modern, full-stack Coaching Academy Management platform featuring **Role-Based Access Control (RBAC)**, an interactive **Three.js WebGL 3D interface**, a dedicated **Student Self-Service Portal**, an enterprise **Audit Logging System**, and **Autonomous Monthly Backups**.

---

## 🌟 Key Features

### 1. 🛡️ Role-Based Access Control (RBAC) & Dedicated Student Portal
- **Strict Boundary Enforcement:** Physical separation between Administrative control and Student views via server-side guards (`require_admin()`, `require_student()`).
- **Student Dashboard:** View student profile details, assigned batch, school, and guardian emergency contact records.
- **Subject-Wise Test Results:** Visual score breakdown, percentage performance badges, and teacher feedback.
- **Self-Service Profile Editing:** Allows students to update personal contact information with read-only batch protection.

### 2. ⚡ Interactive 3D WebGL Graphics (Three.js) & Motion UI
- **Hero Constellation (`index.php`):** Interactive 3D particle constellation and floating geometric crystal nodes responding dynamically to cursor movement.
- **Starfield & Torus Knot (`login.php`):** Animated 3D Torus Knot with starfield warp effect and seamless role toggle.
- **Holographic Admin Command Banner (`table.php`):** 3D glowing wireframe dodecahedron and rotating particle ring.
- **Data Vault (`backups.php`):** Holographic wireframe octahedron visualizer for database backup integrity.

### 3. 📚 Full Academic Management (CRUD)
- **Students:** Register, search with real-time client filtering, inspect full records, edit, and delete.
- **Classes & Batches:** Manage classes, track active status, and dynamically map multi-subject curricula.
- **Subjects:** Create and update curriculum subjects with duplicate prevention.
- **Examinations & Test Scores:** Record test names, maximum marks, marks obtained, dates, and instructor remarks.

### 4. 📜 Enterprise Audit Logging System (`tbllogs`)
- Automatically records every system mutation with user type (`Admin` / `Student` / `System`), user ID, username, action type (`LOGIN`, `LOGOUT`, `INSERT`, `UPDATE`, `DELETE`, `BACKUP`, `DOWNLOAD`), description, IP address, and timestamp.
- Dedicated Activity Logs viewer (`logs.php`) with real-time search and filter.

### 5. 💾 Autonomous Monthly Backups & Local System Downloads
- **Autonomous Monthly Trigger:** Checks database status every month; automatically exports all SQL tables and audit logs into `backups/`.
- **Dual Export Formats:** Generates human-readable, structured JSON archives with metadata and raw SQLite snapshots (`.sqlite`).
- **Local Download:** One-click download streaming files directly to the administrator's local computer with path-traversal prevention.

---

## 🔒 Defensive Security Architecture

- **100% Prepared SQL Statements:** Mitigates SQL Injection (SQLi) using `PDO::prepare()` parameter binding across all endpoints.
- **Session Fixation Defense:** Issues `session_regenerate_id(true)` upon successful authentication.
- **Hardened Cookie Parameters:** Configured with `session.cookie_httponly = 1`, `session.use_only_cookies = 1`, and `session.cookie_samesite = 'Lax'`.
- **Cross-Site Scripting (XSS) Mitigation:** Output escaping via `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Password Security:** Hashes passwords with `password_hash(..., PASSWORD_DEFAULT)` with backward-compatible verification.
- **Path Traversal Protection:** Validates canonical paths with `realpath()` before serving file downloads.

---

## 🚀 Quickstart Guide

### Prerequisites
- [PHP 8.0+](https://www.php.net/) with `pdo_sqlite` extension enabled.
- Web browser with WebGL support.

### 1. Clone the Repository
```bash
git clone https://github.com/Dynamicloki/coachingManagement.git
cd coachingManagement
```

### 2. Start the Local Server
```bash
php -S 127.0.0.1:8000
```

### 3. Open in Browser
Visit [http://localhost:8000](http://localhost:8000)

---

## 🔑 Default Credentials

| Portal | Username / Email | Password | Role |
| :--- | :--- | :--- | :--- |
| **Admin Command Center** | `admin` | `admin123` | Full Administrator |
| **Student Portal** | `student@test.com` | `student123` | Student |

*Note: Database auto-initializes on first launch if `database.sqlite` is not present.*

---

## 🧪 Automated QA & Security Test Suite

The project includes an end-to-end automated testing harness ([`test_suite.py`](test_suite.py)) verifying 26 assertions across 8 test suites:

```bash
python test_suite.py
```

### Test Coverage
- [x] Public landing page & Three.js canvas initialization (HTTP 200)
- [x] Login page & 3D WebGL background (HTTP 200)
- [x] Unauthenticated boundary access blocks (HTTP 302 to login)
- [x] Student authentication & dashboard access
- [x] Student examination results & subject mastery breakdown
- [x] Student access to admin endpoints strictly rejected by RBAC
- [x] Student self-profile updates in SQLite
- [x] Admin full CRUD: Classes, Subjects, Test Results, and Students
- [x] Audit Logging engine verification
- [x] Monthly Backup generation, catalogue indexing, and local download streaming

---

## 📂 Project Architecture

```text
├── backups/                       # Local backup snapshots (.htaccess protected)
├── img/                           # Application brand assets & media
├── includes/
│   ├── left-side.php              # Modern glassmorphic sidebar (Admin)
│   ├── nav.php                    # Admin top navigation bar
│   ├── student-nav.php            # Isolated Student Portal navbar
│   ├── top.php                    # HTML headers & Bootstrap CDN
│   └── end.php                    # Footers & script inclusions
├── database.sqlite                # Portable SQLite 3 database
├── dbms.php                       # Database connection, schemas, RBAC guards & helpers
├── index.php                      # Public coaching website with Three.js 3D constellation
├── login.php                      # Multi-role login portal with Three.js 3D Torus Knot
├── login-process.php              # Authentication & session regeneration handler
├── logout.php                     # Session termination & audit logger
├── table.php                      # Admin Command Center & Student directory
├── class-table.php                # Class / Batch management
├── subjects-table.php             # Curriculum subject management
├── test-results-table.php         # Examination scores management
├── backups.php                    # Monthly backup catalogue & 3D vault
├── backup-download.php            # Secure local file download handler
├── logs.php                       # Enterprise audit activity log viewer
├── student-dashboard.php          # Dedicated student profile portal
├── student-results.php            # Student exam history & performance
├── student-profile-edit.php       # Student self-service editing interface
├── test_suite.py                  # Automated 8-suite regression & security tests
└── README.md                      # Project documentation
```

---

## 📄 License
This project is open-source under the MIT License.
