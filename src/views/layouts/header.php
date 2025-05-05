<!--**********************************
    Nav header start
***********************************-->
<div class="nav-header">
    <a href="https://youtu.be/dQw4w9WgXcQ" class="brand-logo">
        <img class="logo-compact" src="assets/images/._.jpg" alt="">
        <img class="brand-title" src="assets/images/._.jpg" alt="">
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>
<!--**********************************
    Nav header end
***********************************-->

<!--**********************************
    Header start
***********************************-->
<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                </div>
                <ul class="navbar-nav header-right">
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="icon-user"></i>
                            <span class="ml-2 d-none d-lg-inline-block"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="/profile" class="dropdown-item">
                                <i class="icon-user"></i>
                                <span class="ml-2">Profile </span>
                            </a>
                            <a href="/logout" class="dropdown-item">
                                <i class="icon-key"></i>
                                <span class="ml-2">Log out </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!--**********************************
    Header end
***********************************-->