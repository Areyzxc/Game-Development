<?php
/**
 * ==========================================================
 * File: admin_dashboard.php
 * 
 * Description:
 *   - Admin Dashboard for Code Gaming platform
 *   - Features:
 *       • Stats cards for users, content, announcements, and system status
 *       • Analytics charts for user activity and content distribution
 *       • Recent announcements and quick actions panel
 *       • Recent activity table and system notifications
 *       • Modals for new/edit announcements, system settings, pin/unpin actions
 *       • Responsive, modern UI with Bootstrap and custom styles
 * 
 * Usage:
 *   - Accessible only to logged-in admins
 *   - Central hub for managing users, content, announcements, and system status
 * 
 * Files Included:
 *   - assets/css/admin_dashboard.css
 *   - assets/js/admin_global.js
 *   - assets/js/admin_dashboard.js
 *   - includes/admin_header.php, includes/admin_footer.php
 *   - External: Bootstrap, Font Awesome
 * 
 * Author: [Santiago]
 * Last Updated: [July 22, 2025]
 * -- Code Gaming Team --
 * ==========================================================
 */
require_once 'includes/Auth.php';

$auth = Auth::getInstance();

// Redirect if not logged in
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Redirect if not admin
if (!$auth->isAdmin()) {
    header('Location: home_page.php');
    exit;
}

$currentUser = $auth->getCurrentUser();
$currentRole = $auth->getCurrentRole();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/admin_dashboard.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" />
</head>
<body class="admin-theme">
<?php
require_once 'includes/admin_header.php';

// Check for the login success flag to show a notification
$showLoginNotification = isset($_GET['login']) && $_GET['login'] === 'success';
?>

<?php if ($showLoginNotification): ?>
<div class="login-notification" id="loginNotification">
    <div class="d-flex align-items-center">
        <i class="fas fa-user-shield me-3 text-accent fa-2x"></i>
        <div>
            <span class="fw-bold">Logged in as: <?php echo htmlspecialchars($currentUser['username'] ?? 'Admin'); ?></span>
            <small class="d-block text-muted">Role: <?php echo ucfirst($currentRole); ?></small>
        </div>
    </div>
    <button type="button" class="btn-close" id="closeLoginNotification" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="admin-main-content p-4">
  <!-- Stats Cards Row -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card admin-card text-center small-stats-card">
        <div class="card-body">
          <div class="mb-2"><i class="fas fa-users fa-lg text-primary"></i></div>
          <div class="fw-bold text-muted">Total Users</div>
          <span class="display-7" id="totalUsersStat">1,234</span>
          <div class="text-success small mt-1"><i class="fas fa-arrow-up"></i> 12% this week</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card admin-card text-center small-stats-card">
        <div class="card-body">
          <div class="mb-2"><i class="fas fa-user-check fa-lg text-success"></i></div>
          <div class="fw-bold text-muted">Active Users</div>
          <span class="display-7" id="activeUsersStat">87</span>
          <div class="text-info small mt-1">Currently Online</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card admin-card text-center small-stats-card">
        <div class="card-body">
          <div class="mb-2"><i class="fas fa-book-open fa-lg text-warning"></i></div>
          <div class="fw-bold text-muted">Total Content</div>
          <span class="display-7" id="totalContentStat">56</span>
          <div class="text-warning small mt-1">Tutorials, Quizzes & Challenges</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card admin-card text-center small-stats-card">
        <div class="card-body">
          <div class="mb-2"><i class="fas fa-bullhorn fa-lg text-danger"></i></div>
          <div class="fw-bold text-muted">Total Announcements</div>
          <span class="display-7" id="totalAnnouncementsStat">0</span>
          <div class="text-muted small mt-1"><span id="publishedAnnouncementsStat">0</span> published, <span id="draftAnnouncementsStat">0</span> drafts</div>
          <hr class="my-2">
          <div class="fw-bold text-muted mt-2">System Status</div>
          <span class="badge bg-success fs-7" id="systemStatusStat">Online</span>
          <div class="text-muted small mt-1">All systems operational</div>
        </div>
      </div>
    </div>
  </div>
  <!-- Analytics/Charts Section -->
  <div class="row mb-4">
    <div class="col-lg-8 mb-4 mb-lg-0">
      <div class="card admin-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-info"></i>User Activity</h5>
            <div class="btn-group btn-group-sm" role="group">
              <button class="btn btn-outline-secondary active">Daily</button>
              <button class="btn btn-outline-secondary">Weekly</button>
              <button class="btn btn-outline-secondary">Monthly</button>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="userActivityChart" height="180"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card admin-card">
        <div class="card-body">
          <h5 class="mb-3"><i class="fas fa-chart-pie me-2 text-purple"></i>Content Distribution</h5>
          <div class="chart-container">
            <canvas id="contentPieChart" height="180"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Announcements & Quick Actions -->
  <div class="row mb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
      <div class="card admin-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0"><i class="fas fa-bullhorn me-2 text-warning"></i>Recent Announcements</h5>
            <button class="btn btn-sm btn-primary" id="openAnnouncementModalBtn" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal"><i class="fas fa-plus"></i> New</button>
          </div>
          <ul class="list-group list-group-flush" id="dashboardAnnouncementsList">
            <!-- Announcements will be loaded here dynamically -->
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card admin-card">
        <div class="card-body">
          <h5 class="mb-3"><i class="fas fa-bolt me-2 text-success"></i>Quick Actions</h5>
          <div class="d-grid gap-2 compact-quick-actions">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal"><i class="fas fa-bullhorn me-2"></i>New Announcement</button>
            <button class="btn btn-success btn-sm"><i class="fas fa-plus me-2"></i>Add Content</button>
            <button class="btn btn-info btn-sm"><i class="fas fa-users me-2"></i>Generate Report</button>
            <button class="btn btn-warning btn-sm"><i class="fas fa-cog me-2"></i>System Settings</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Recent Activity Table -->
  <div class="card admin-card mb-4">
    <div class="card-body">
      <h5 class="mb-3"><i class="fas fa-history me-2 text-secondary"></i>Recent Activity</h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>User</th>
              <th>Action</th>
              <th>Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><i class="fas fa-user-circle text-primary"></i> Jane Doe</td>
              <td>Completed Quiz: <span class="fw-bold">JavaScript Basics</span></td>
              <td>5 min ago</td>
              <td><span class="badge bg-success">Success</span></td>
            </tr>
            <tr>
              <td><i class="fas fa-user-circle text-primary"></i> John Smith</td>
              <td>Submitted Feedback</td>
              <td>12 min ago</td>
              <td><span class="badge bg-info">Info</span></td>
            </tr>
            <tr>
              <td><i class="fas fa-user-circle text-primary"></i> Alice Lee</td>
              <td>Started Challenge: <span class="fw-bold">Python Loops</span></td>
              <td>30 min ago</td>
              <td><span class="badge bg-warning">Pending</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- System Notifications -->
  <div class="card admin-card mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0"><i class="fas fa-bell me-2 text-danger"></i>System Notifications</h5>
        <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-sync-alt"></i> Refresh</button>
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><i class="fas fa-exclamation-circle text-danger me-2"></i>3 failed login attempts detected.</li>
        <li class="list-group-item"><i class="fas fa-info-circle text-info me-2"></i>New user registered: <b>coder123</b></li>
        <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i>System backup completed successfully.</li>
      </ul>
    </div>
  </div>
</div>
<!-- New/Edit Announcement Modal -->
<div class="modal fade" id="newAnnouncementModal" tabindex="-1" aria-labelledby="newAnnouncementModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newAnnouncementModalLabel">New Announcement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="announcementForm">
          <input type="hidden" id="announcementId" value="">
          <div class="mb-3">
            <label for="announcementTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="announcementTitle" placeholder="Enter title">
          </div>
          <div class="mb-3">
            <label for="announcementContent" class="form-label">Content</label>
            <textarea class="form-control" id="announcementContent" rows="3" placeholder="Enter content"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="publishAnnouncementBtn">Publish</button>
      </div>
    </div>
  </div>
</div>
<!-- System Settings Modal (UI only) -->
<div class="modal fade" id="systemSettingsModal" tabindex="-1" aria-labelledby="systemSettingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="systemSettingsModalLabel">System Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Settings UI coming soon...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Max Pins Modal -->
<div class="modal fade" id="maxPinsModal" tabindex="-1" aria-labelledby="maxPinsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="maxPinsModalLabel"><i class="fas fa-thumbtack text-warning me-2"></i>Pin Limit Reached</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>You can only have <b>3 pinned announcements</b> at a time.<br>Unpin another announcement before pinning a new one.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
      </div>
    </div>
  </div>
</div>
<!-- Pin/Unpin Confirmation Modal -->
<div class="modal fade" id="pinUnpinModal" tabindex="-1" aria-labelledby="pinUnpinModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning-subtle">
        <h5 class="modal-title" id="pinUnpinModalLabel"><i class="fas fa-thumbtack me-2 text-warning"></i><span id="pinUnpinModalTitle">Pin Announcement</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="pinUnpinModalBody">
        Are you sure you want to pin this announcement as featured?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Dismiss</button>
        <button type="button" class="btn btn-warning" id="confirmPinUnpinBtn">Confirm</button>
      </div>
    </div>
  </div>
</div>
<!-- End Admin Dashboard Main Content -->
<?php
include 'includes/admin_footer.php';
?>
<script src="assets/js/admin_global.js"></script>
<script src="assets/js/admin_dashboard.js"></script>
</body>
</html> 