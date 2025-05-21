<!DOCTYPE html>
<html lang="it">
<head>
    <base href="<?php echo URL ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CasaNote</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css -->
    <link href="<?php echo URL; ?>public/css/style.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <link rel="stylesheet" href= "application/libs/bootstrap/css/bootstrap.min.css">
    <script src="application/libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/vnd.icon" href="/application/libs/img/favLogo.ico ">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Include Font Awesome per le icone -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364); /* sfondo blu-grigio tech */
            min-height: 100vh;
            padding: 20px 15px;
            color: #f1f1f1;
        }

        .main-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            padding: 20px 30px;  /* padding più generoso */
            max-width: 1400px;    /* riquadro più largo */
            margin: auto;
            color: #333;
        }


        h1.header-title {
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }

        .custom-navbar {
            margin-bottom: 20px;
        }

    </style>

</head>

<body>
<h1 class="header-title">CasaNote</h1>
<div class="main-container">
    <!-- Tutto il tuo contenuto HTML/PHP va qui -->