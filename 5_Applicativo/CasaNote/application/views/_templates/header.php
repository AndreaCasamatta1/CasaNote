<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?php echo URL ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FAQ</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css -->
    <link href="<?php echo URL; ?>public/css/style.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <link rel="stylesheet" href= "application/libs/bootstrap/css/bootstrap.min.css">
    <script src="application/libs/bootstrap/js/bootstrap.min.js"></script>

</head>
<?php
$color = isset($_COOKIE['color']) ? '#' . $_COOKIE['color'] : '#FFFFFF'; // Default: bianco
?>
<body style="background-color: <?php echo htmlspecialchars($color); ?>;">


<nav class="navbar navbar-expand-lg bg-dark" >
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?php if(str_ends_with($_SERVER['REQUEST_URI'],'/')){echo 'text-white';}else{echo'text-secondary';} ?>"  href="<?php echo URL ?>register">Register <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if(str_ends_with($_SERVER['REQUEST_URI'],'admin')){echo 'text-white';}else{echo'text-secondary';} ?>" href="<?php echo URL ?>login">Login</a>
            </li>
        </ul>
    </div>
</nav>
