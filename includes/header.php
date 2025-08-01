<?php
/**
 * ==========================================================
 * File: includes/header.php
 * 
 * Description:
 *   - Global header and navigation bar for Code Game web application for users and guests.
 *   - Provides consistent navigation, branding, and user authentication controls across the site.
 *   - Contains:
 *       • Responsive Bootstrap navbar with brand/logo
 *       • Navigation links for all main sections (Home, Game Modes, Profile, etc.)
 *       • User authentication controls (Log In, Sign Up, Profile, Logout)
 *       • Mobile menu and dropdown support
 *       • Navbar theming and style hooks
 * 
 * 
 * Usage:
 *   - Included at the top of all main pages (excluding anchor page) for consistent navigation and branding.
 *   - Relies on session/auth variables to display user-specific links.
 * 
 * Author: [Santiago]
 * Last Updated: [June 13, 2025]
 * ==========================================================
 */

// Get current page for active nav highlighting
$currentPage = basename($_SERVER['PHP_SELF']);

// Ensure $auth is available from the global scope
global $auth;

// Check if user is logged in
$isLoggedIn = isset($auth) && $auth->isLoggedIn();
$currentUser = $isLoggedIn ? $auth->getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' • ' : ''; ?>Code Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js"></script>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/audio-player.css" rel="stylesheet">
    <?php if (isset($additionalStyles)) echo $additionalStyles; ?>  
</head>
<body class="bg-dark text-light">
    <!-- Enhanced Navigation Bar -->
    <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <!-- Brand with Logo -->
            <a class="navbar-brand d-flex align-items-center" href="home_page.php">
                <img src="images/logo.png" alt="Code Game Logo" class="navbar-logo me-2" width="40" height="40">
                <span class="brand-text">Code Gaming</span>
            </a>

            <!-- Anchor Page Button -->
            <a href="anchor.php" class="anchor-btn me-3" title="Go to Anchor Page">
                <i class="fas fa-anchor"></i>
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Left Navigation Links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Announcements -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'announcements.php' ? 'active' : ''; ?>" 
                           href="announcements.php">
                            <i class="fas fa-bullhorn me-1"></i>Announcements
                        </a>
                    </li>
                    
                    <!-- Tutorial -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'tutorial.php' ? 'active' : ''; ?>" 
                           href="tutorial.php">
                            <i class="fas fa-book me-1"></i>Tutorial
                        </a>
                    </li>
                    
                    <!-- Game Modes Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['mini-game.php', 'quiz.php', 'challenges.php']) ? 'active' : ''; ?>" 
                           href="#" id="gameModesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-gamepad me-1"></i>Game
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="gameModesDropdown">
                            <li>
                                <a class="dropdown-item <?php echo $currentPage === 'mini-game.php' ? 'active' : ''; ?>" 
                                   href="mini-game.php">
                                    <i class="fas fa-puzzle-piece me-2"></i>Mini-Game
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo $currentPage === 'quiz.php' ? 'active' : ''; ?>" 
                                   href="quiz.php">
                                    <i class="fas fa-question-circle me-2"></i>Quiz
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo $currentPage === 'challenges.php' ? 'active' : ''; ?>" 
                                   href="challenges.php">
                                    <i class="fas fa-trophy me-2"></i>Challenges
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Profile (Only for logged-in users) -->
                    <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>" 
                           href="profile.php">
                            <i class="fas fa-user me-1"></i>Profile
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Right-side Controls -->
                <ul class="navbar-nav align-items-center mb-2 mb-lg-0">
                    <!-- Authentication Section -->
                    <?php if ($isLoggedIn): ?>
                        <!-- Logged-in User -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?php echo !empty($currentUser['profile_picture']) ? htmlspecialchars($currentUser['profile_picture']) : 'images/PTC.png'; ?>"
                                     alt="Avatar" class="rounded-circle me-2" width="35" height="35" id="userAvatar"/>
                                <span id="usernameDisplay"><?php echo htmlspecialchars($currentUser['username']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo !empty($currentUser['profile_picture']) ? htmlspecialchars($currentUser['profile_picture']) : 'images/PTC.png'; ?>"
                                             alt="Avatar" class="rounded-circle me-2" width="30" height="30"/>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($currentUser['username']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($currentUser['email']); ?></small>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="profile.php">
                                        <i class="fas fa-user me-2"></i>My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="settings.php">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Visitor/Guest -->
                        <li class="nav-item me-2">
                            <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-sign-in-alt me-1"></i>Log In
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#signInModal">
                                <i class="fas fa-user-plus me-1"></i>Sign Up
                            </button>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay (for smaller screens) -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay">
        <div class="mobile-menu-content">
            <?php if ($isLoggedIn): ?>
                <!-- Logged-in User Mobile Menu -->
                <div class="mobile-user-info">
                    <img src="<?php echo !empty($currentUser['profile_picture']) ? htmlspecialchars($currentUser['profile_picture']) : 'images/PTC.png'; ?>"
                         alt="Avatar" class="mobile-avatar"/>
                    <div class="mobile-user-details">
                        <h6 class="mb-0"><?php echo htmlspecialchars($currentUser['username']); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($currentUser['email']); ?></small>
                    </div>
                </div>
            <?php endif; ?>
            
            <ul class="mobile-nav-list">
                <li><a href="announcements.php"><i class="fas fa-bullhorn"></i>Announcements</a></li>
                <li><a href="tutorial.php"><i class="fas fa-book"></i>Tutorial</a></li>
                <li class="mobile-dropdown">
                    <a href="#" class="mobile-dropdown-toggle">
                        <i class="fas fa-gamepad"></i>Game Modes
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="mini-game.php"><i class="fas fa-puzzle-piece"></i>Mini-Game</a></li>
                        <li><a href="quiz.php"><i class="fas fa-question-circle"></i>Quiz</a></li>
                        <li><a href="challenges.php"><i class="fas fa-trophy"></i>Challenges</a></li>
                    </ul>
                </li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i>Settings</a></li>
                    <li><a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
                <?php else: ?>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt"></i>Log In</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#signInModal"><i class="fas fa-user-plus"></i>Sign Up</a></li>
                <?php endif; ?>
            </ul>

            <!-- Mobile Menu Close Button -->
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Add padding to account for fixed navbar -->
    <div style="padding-top: 56px;">
        <?php if (isset($includeAnnouncementBanner) && $includeAnnouncementBanner): ?>
            <?php include 'announcement_banner.php'; ?>
        <?php endif; ?>
    </div>

    <script>
    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const navbarToggler = document.querySelector('.navbar-toggler');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const mobileDropdowns = document.querySelectorAll('.mobile-dropdown-toggle');
        
        // Toggle mobile menu
        navbarToggler.addEventListener('click', function() {
            mobileMenuOverlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
        
        // Close mobile menu when clicking overlay
        mobileMenuOverlay.addEventListener('click', function(e) {
            if (e.target === mobileMenuOverlay) {
                mobileMenuOverlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
        
        // Close mobile menu when clicking close button
        mobileMenuClose.addEventListener('click', function() {
            mobileMenuOverlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        // Mobile dropdown functionality
        mobileDropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent event from bubbling up
                const dropdownMenu = this.nextElementSibling;
                const icon = this.querySelector('.fa-chevron-down');
                
                dropdownMenu.classList.toggle('active');
                icon.style.transform = dropdownMenu.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
            });
        });
        
        // Close mobile menu when clicking a link (but not dropdown toggles)
        const mobileLinks = mobileMenuOverlay.querySelectorAll('a[href]:not(.mobile-dropdown-toggle)');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuOverlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        });
    });
    </script>
</body>
</html> 