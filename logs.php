<?php
include "dbms.php";
require_admin();

include "includes/top.php";
?>
<title>Admin Panel - Audit & Activity Logs</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

$filterAction = $_GET['action'] ?? '';
$sql = "SELECT * FROM tbllogs";
$params = [];

if (!empty($filterAction)) {
    $sql .= " WHERE action = :action";
    $params[':action'] = $filterAction;
}
$sql .= " ORDER BY logid DESC LIMIT 200";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2 flex-wrap gap-2">
    <div>
        <h2 class="text-primary fw-bold m-0">System Activity & Audit Logs 📋</h2>
        <p class="text-muted m-0">Live audit trail of logins, logouts, updates, creations, and deletions</p>
    </div>
    
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="action" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                <option value="">-- Filter by Action --</option>
                <option value="LOGIN" <?php if ($filterAction === 'LOGIN') echo 'selected'; ?>>LOGIN</option>
                <option value="LOGOUT" <?php if ($filterAction === 'LOGOUT') echo 'selected'; ?>>LOGOUT</option>
                <option value="INSERT" <?php if ($filterAction === 'INSERT') echo 'selected'; ?>>INSERT</option>
                <option value="UPDATE" <?php if ($filterAction === 'UPDATE') echo 'selected'; ?>>UPDATE</option>
                <option value="DELETE" <?php if ($filterAction === 'DELETE') echo 'selected'; ?>>DELETE</option>
            </select>
            <?php if (!empty($filterAction)): ?>
                <a href="logs.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="glass-card border shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="width: 70px;">#ID</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">User Role</th>
                    <th scope="col">Account</th>
                    <th scope="col" class="text-center">Action</th>
                    <th scope="col">Activity Description</th>
                    <th scope="col">IP Address</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">No activity logs recorded yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="text-muted fw-bold">#<?php echo htmlspecialchars($log['logid']); ?></td>
                        <td><small class="font-monospace text-dark"><?php echo htmlspecialchars($log['timestamp']); ?></small></td>
                        <td>
                            <?php if ($log['user_type'] === 'Admin'): ?>
                                <span class="badge bg-danger rounded-pill px-2 py-1">Admin</span>
                            <?php elseif ($log['user_type'] === 'Student'): ?>
                                <span class="badge bg-primary rounded-pill px-2 py-1">Student</span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill px-2 py-1"><?php echo htmlspecialchars($log['user_type']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong class="text-dark"><?php echo htmlspecialchars($log['username']); ?></strong>
                            <?php if ($log['user_id']): ?>
                                <small class="text-muted">(#<?php echo $log['user_id']; ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $act = strtoupper($log['action']);
                            $badgeClass = 'bg-secondary';
                            if ($act === 'LOGIN') $badgeClass = 'bg-success';
                            elseif ($act === 'LOGOUT') $badgeClass = 'bg-dark';
                            elseif ($act === 'INSERT') $badgeClass = 'bg-info text-dark';
                            elseif ($act === 'UPDATE') $badgeClass = 'bg-warning text-dark';
                            elseif ($act === 'DELETE') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-1"><?php echo $act; ?></span>
                        </td>
                        <td><span class="text-dark small fw-medium"><?php echo htmlspecialchars($log['description']); ?></span></td>
                        <td><small class="text-muted font-monospace"><?php echo htmlspecialchars($log['ip_address'] ?? '127.0.0.1'); ?></small></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include "includes/end.php";
?>
