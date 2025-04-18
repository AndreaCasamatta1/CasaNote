<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se esistono note filtrate, usa quelle. Altrimenti, usa tutte le note passate da main().
if (isset($_SESSION['filtered_notes'])) {
    $allNote = $_SESSION['filtered_notes'];
    $hasFilter = true;
} else {
    $hasFilter = false;
}

if (!isset($allNote)) {
    $allNote = []; // Prevenire errori se non è stato inizializzato
}
?>

<title>Notes</title>
<div class="">

    <div class="card card-bordered p-1 m-1">
        <a href="<?php echo URL; ?>/manage/goToCreateNotePage" class="btn btn-outline-primary position-fixed top-0 m-3">
            + Aggiungi Nota
        </a>
        <br>
        <div class="card-body m-1" style="background-color: white">
            <br>
            <h1 class="">Note personali:</h1>

            <!-- FORM RICERCA -->
            <form action="<?php echo URL; ?>home/filter" method="POST">
                <input type='text' name='field' placeholder="Cerca" value="<?php echo isset($_POST['field']) ? htmlspecialchars($_POST['field']) : ''; ?>">
                <input type="submit" value="🔍" style="border: none; background: none">
            </form>
            <!-- Bottone resettare filtro (mostra tutte le note) -->
            <?php if ($hasFilter): ?>
                <form action="<?php echo URL; ?>home/resetFilter" method="POST" style="display:inline;">
                    <button type="submit" class="btn btn-secondary mt-2">Mostra tutte le note</button>
                </form>
            <?php endif; ?>
            <br>
            <br>
            <table class="">
                <?php if (!empty($allNote)): ?>
                    <?php foreach ($allNote as $single): ?>
                        <div class="col-md-6 col-lg-6 mb-6">
                            <h6 class="mb-6" style="color: #145eba">
                                <i class="far fa-newspaper pe-2"></i>
                                <?php echo htmlspecialchars($single->getTitle()); ?>
                            </h6>
                            <div class="mb-3 pb-3" style="border-bottom: #145eba solid thin; border-width: thin;">
                                <?php echo $single->getDateCreation(); ?>
                                <a href="<?php echo URL . 'manage/deleteNote/' . $single->getId(); ?>" class="btn btn-danger"  onclick="return confirm('Sicuro?')">x</a>
                                <a href="<?php echo URL . 'manage/goToCreateNotePage/' . $single->getId();?>" class="btn btn-primary">aggiorna</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nessuna nota trovata.</p>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
