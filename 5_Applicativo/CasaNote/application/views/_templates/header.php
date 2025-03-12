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
    <style>
         body {
             font-family: Arial, sans-serif;
         }
        .header {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<?php
$color = isset($_COOKIE['color']) ? '#' . $_COOKIE['color'] : '#FFFFFF'; // Default: bianco
?>
<body style="background-color: <?php echo htmlspecialchars($color); ?>;">
    <h1 class="d-flex justify-content-center">CasaNote</h1>