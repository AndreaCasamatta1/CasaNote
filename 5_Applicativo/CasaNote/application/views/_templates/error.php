<?php
session_start();

$errors = $_SESSION['errors'] ?? [];

if (!is_array($errors)) {
    $errors = [$errors];
}
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Errore</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.style.display='none'">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
    $_SESSION['errors'] = [];
endif;
?>
