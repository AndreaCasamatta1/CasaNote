<nav class="navbar navbar-expand-lg bg-dark m-0" >
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
            <img src="application/libs/img/logo.png" width="30" height="30" alt="">
        </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?php if(str_ends_with($_SERVER['REQUEST_URI'],'/')){echo 'text-white';}else{echo'text-secondary';} ?>"  href="<?php echo URL ?>register">Register <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if(str_ends_with($_SERVER['REQUEST_URI'],'/')){echo 'text-white';}else{echo'text-secondary';} ?>" href="<?php echo URL ?>login">Login</a>
            </li>
        </ul>

    </div>
</nav>