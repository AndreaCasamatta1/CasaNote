<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['filtered_notes'])) {
    $allNote = $_SESSION['filtered_notes'];
    $hasFilter = true;
} else {
    $hasFilter = false;
}

if (!isset($allNote)) {
    $allNote = [];
}
?>

<title>Notes</title>
<div class="container-fluid">

    <div class="card card-bordered p-3 m-2">
        <a href="<?php echo URL; ?>manage/goToCreateNotePage" class="btn btn-outline-primary position-absolute top-0 m-3">
            + Aggiungi Nota
        </a>
        <br>
        <br>
        <div class="card-body" style="background-color: white">
            <h1>Note personali:</h1>

            <form action="<?php echo URL; ?>home/filter" method="POST" class="mb-3">
                <input type="text" name="field" class="form-control" placeholder="Cerca"
                       value="<?php echo isset($_POST['field']) ? htmlspecialchars($_POST['field']) : ''; ?>">
                <input type="submit" value="🔍" style="border: none; background: none; margin-top: 5px;">
            </form>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                <form action="<?php echo URL; ?>home/sortByDate" method="POST" class="mb-2 mb-md-0">
                    <button type="submit" class="btn btn-outline-info">
                        <?php echo isset($_SESSION['order_by_date']) && $_SESSION['order_by_date'] === 'desc' ? 'Ordina Meno Recente' : 'Ordina Più Recente'; ?>
                    </button>
                </form>
                <form action="<?php echo URL; ?>home/resetFilter" method="POST">
                    <button type="submit" class="btn btn-secondary">Mostra tutte le note</button>
                </form>
            </div>

            <?php if (!empty($allNote)): ?>
                <div class="row">
                    <?php foreach ($allNote as $single): ?>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title" style="color: #145eba">
                                        <i class="far fa-newspaper pe-2"></i>
                                        <?php echo htmlspecialchars($single->getTitle()); ?>
                                    </h6>
                                    <p class="card-text mb-1">
                                        <?php echo "Data Creazione: " . $single->getDateCreation(); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><?php echo "Ultimo Aggiornamento: " . $single->getDateLastUpdate(); ?></span>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="<?php echo URL . 'manage/deleteNote/' . $single->getId(); ?>" class="btn btn-danger btn-sm me-2" onclick="return confirm('Sicuro di eliminare la nota?')">X</a>
                                    <a href="<?php echo URL . 'manage/goToCreateNotePage/' . $single->getId(); ?>" class="btn btn-primary btn-sm">Aggiorna</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nessuna nota trovata.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
