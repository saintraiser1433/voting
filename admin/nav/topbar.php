<?php
$admin_mode = isset($_SESSION['admin_mode']) ? $_SESSION['admin_mode'] : 'general';
?>
<nav class="navbar header-navbar pcoded-header">
    <div class="navbar-wrapper">

        <div class="navbar-logo">
            <a class="mobile-menu" id="mobile-collapse" href="#!">
                <i class="feather icon-menu"></i>
            </a>
            <a href="dashboard.php">
                <div class="d-flex justify-content-center align-items-center">
                    <img class="img-fluid" src="../libraries/img/glanlogo.png" alt="Theme-Logo" width="32px" height="32px">
                 <span class="ml-2">GIT MOBILE BASED VOTING SYSTEM</span>
                </div>
                
            </a>
            <a class="mobile-options">
                <i class="feather icon-more-horizontal"></i>
            </a>
        </div>

        <div class="navbar-container container-fluid">
            <ul class="nav-left">
                <li class="admin-mode-toggle mr-2">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="switch_admin_mode.php?mode=general" class="btn btn-sm <?php echo $admin_mode === 'general' ? 'btn-primary' : 'btn-outline-secondary'; ?>">General Voting</a>
                        <a href="switch_admin_mode.php?mode=department" class="btn btn-sm <?php echo $admin_mode === 'department' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Department Voting</a>
                    </div>
                </li>
                <li class="header-search">
                    <div class="main-search morphsearch-search">
                        <div class="input-group">
                            <span class="input-group-addon search-close"><i class="feather icon-x"></i></span>
                            <input type="text" class="form-control">
                            <span class="input-group-addon search-btn"><i class="feather icon-search"></i></span>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#!" onclick="javascript:toggleFullScreen()">
                        <i class="feather icon-maximize full-screen"></i>
                    </a>
                </li>
            </ul>
            <ul class="nav-right">
                <li class="user-profile header-notification">
                    <div class="dropdown-primary dropdown">
                        <div class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../libraries/img/glanlogo.png" class="img-radius" alt="User-Profile-Image">
                            <span>Admin</span>
                            <i class="feather icon-chevron-down"></i>
                        </div>
                        <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                            <li>
                                <a href="#mytitleqw" data-toggle="modal" class="w-100">
                                    <i class="feather icon-lock"></i> Change Password
                                </a>
                            </li>
                            <li>
                                <a href="logout.php" class="w-100">
                                    <i class="feather icon-log-out"></i> Logout
                                </a>
                            </li>
                        </ul>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="admin-mode-toggle-mobile-bar d-lg-none">
    <div class="btn-group btn-group-sm w-100" role="group">
        <a href="switch_admin_mode.php?mode=general" class="btn <?php echo $admin_mode === 'general' ? 'btn-primary' : 'btn-outline-secondary'; ?>">General Voting</a>
        <a href="switch_admin_mode.php?mode=department" class="btn <?php echo $admin_mode === 'department' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Department Voting</a>
    </div>
</div>
<style>
    .admin-mode-toggle-mobile-bar {
        display: none;
        position: fixed;
        top: 56px;
        left: 0;
        right: 0;
        z-index: 1029;
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
        padding: 6px 10px;
    }

    @media only screen and (max-width: 991px) {
        .admin-mode-toggle-mobile-bar {
            display: block;
        }

        .pcoded-main-container {
            margin-top: 102px !important;
        }
    }
</style>