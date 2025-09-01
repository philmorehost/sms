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

<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="users.php" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total Users</p>
                    <h5 class="stat-value"><?php echo $total_users; ?></h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="reports.php" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Messages Sent</p>
                    <h5 class="stat-value"><?php echo $total_messages; ?></h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="#" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-address-book"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Contact Groups</p>
                    <h5 class="stat-value"><?php echo $total_groups; ?></h5>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="#" class="stat-card-link">
            <div class="stat-card">
                <div class="stat-icon bg-secondary">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total Contacts</p>
                    <h5 class="stat-value"><?php echo $total_contacts; ?></h5>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
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
                            <th>Phone Number</th>
                            <th>Registration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT username, email, phone_number, created_at FROM users WHERE is_admin = 0 ORDER BY created_at DESC LIMIT 5");
                            if ($stmt) {
                                $stmt->execute();
                                $recent_users_result = $stmt->get_result();
                                while ($row = $recent_users_result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                            </tr>
                            <?php
                                endwhile;
                                $stmt->close();
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
