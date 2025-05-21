<nav class="navbar navbar-expand-lg shadow-sm" style="background-color: #1E2A38; border-radius: 12px; margin: 10px;">
    <div class="container-fluid px-3">
        <div class="d-flex align-items-center">
            <img src="/application/libs/img/logo.png" alt="CasaNote Logo" width="35" height="35" class="me-2" style="border-radius: 8px;">
            <span class="navbar-brand mb-0 h4 text-white" style="user-select:none; cursor: default;">CasaNote</span>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #4a90e2;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo (str_ends_with($_SERVER['REQUEST_URI'], '/register')) ? 'active text-white' : 'text-light'; ?>"
                       href="<?php echo URL ?>register" style="font-weight: 500; transition: color 0.3s;">
                        Register
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (str_ends_with($_SERVER['REQUEST_URI'], '/login')) ? 'active text-white' : 'text-light'; ?>"
                       href="<?php echo URL ?>login" style="font-weight: 500; transition: color 0.3s;">
                        Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-nav .nav-link.active {
        color: #4a90e2 !important; /* Colore azzurro brillante per active */
        font-weight: 600;
    }
    .navbar-nav .nav-link:hover {
        color: #82b1ff !important;
    }
    .navbar-toggler {
        border-radius: 8px;
        border-color: #4a90e2 !important;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }


</style>
