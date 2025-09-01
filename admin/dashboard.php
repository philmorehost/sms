<?php
$page_title = 'Admin Dashboard';
include 'includes/header.php';

// Fetch stats for the dashboard
function get_count($conn, $sql) {
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['count'];
        $stmt->close();
        return $count;
    }
    return 0;
}

// Total Users
$total_users = get_count($conn, "SELECT COUNT(id) as count FROM users WHERE is_admin = 0");
// Total Messages Sent
$total_messages = get_count($conn, "SELECT COUNT(id) as count FROM messages");
// Total Groups
$total_groups = get_count($conn, "SELECT COUNT(id) as count FROM phonebook_groups");
// Total Contacts
$total_contacts = get_count($conn, "SELECT COUNT(id) as count FROM phonebook_contacts");

?>

<div class="row d-flex align-items-stretch mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-box bg-primary">
            <div class="inner">
                <h3><?php echo $total_users; ?></h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="users.php" class="stat-box-footer">Manage Users <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-box bg-success">
            <div class="inner">
                <h3><?php echo $total_messages; ?></h3>
                <p>Messages Sent</p>
            </div>
            <div class="icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <a href="reports.php" class="stat-box-footer">View Reports <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-box bg-info">
            <div class="inner">
                <h3><?php echo $total_groups; ?></h3>
                <p>Contact Groups</p>
            </div>
            <div class="icon">
                <i class="fas fa-address-book"></i>
            </div>
            <a href="#" class="stat-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-box bg-warning">
            <div class="inner">
                <h3><?php echo $total_contacts; ?></h3>
                <p>Total Contacts</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="#" class="stat-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent User Registrations</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt_users = $conn->prepare("SELECT username, email, created_at FROM users WHERE is_admin = 0 ORDER BY created_at DESC LIMIT 10");
                            if ($stmt_users) {
                                $stmt_users->execute();
                                $recent_users_result = $stmt_users->get_result();
                                while ($row = $recent_users_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php
                                endwhile;
                                $stmt_users->close();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Transactions</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt_trans = $conn->prepare("SELECT t.*, u.username FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC LIMIT 10");
                            if ($stmt_trans) {
                                $stmt_trans->execute();
                                $recent_trans_result = $stmt_trans->get_result();
                                while ($row = $recent_trans_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo get_currency_symbol() . number_format($row['amount'], 2); ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $row['type'])); ?></td>
                                <td>
                                     <?php
                                        $status = strtolower($row['status']);
                                        $badge_class = 'bg-secondary';
                                        if (in_array($status, ['completed', 'success', 'approved'])) $badge_class = 'bg-success';
                                        elseif (in_array($status, ['failed', 'cancelled', 'rejected'])) $badge_class = 'bg-danger';
                                        elseif ($status === 'pending') $badge_class = 'bg-warning';
                                        echo '<span class="badge ' . $badge_class . '">' . htmlspecialchars(ucfirst($status)) . '</span>';
                                    ?>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php
                                endwhile;
                                $stmt_trans->close();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>
