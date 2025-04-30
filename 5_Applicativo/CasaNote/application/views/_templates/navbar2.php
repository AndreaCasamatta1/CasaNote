<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<nav class="navbar navbar-expand-lg bg-dark m-0">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
            <img src="application/libs/img/logo.png" width="30" height="30" alt="">
        </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?php if(str_ends_with($_SERVER['REQUEST_URI'],'/')){echo 'text-white';}else{echo'text-secondary';} ?>" href="<?php echo URL ?>logout">Logout</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if(str_ends_with($_SERVER['REQUEST_URI'],'/')){echo 'text-white';}else{echo'text-secondary';} ?>" href="<?php echo URL ?>home/main">Home <span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-none d-lg-block" href="<?php echo URL ?>home/user">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                </a>
                <a class="nav-link d-block d-lg-none" href="<?php echo URL ?>home/user">
                    Profilo
                </a>
            </li>
        </ul>
    </div>
</nav>
