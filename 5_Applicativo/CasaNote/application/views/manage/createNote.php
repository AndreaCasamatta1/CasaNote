<?php
// per non far dare errore all'IDE
/* @var $note \models\note */
include 'application/views/_templates/static/js/manageInput.php';
include 'application/views/_templates/static/js/manageCanvas.php';

?>

<form method="POST" action="<?php echo URL; ?>manage/saveOrUpdateNote<?php if (isset($note)): echo '/' . $note->getId(); endif; ?>">
    <nav class="navbar navbar-expand-lg m-0 mb-4">
        <!-- ID univoco -->
        <div class="navbar-collapse" id="navbarNoteToolbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button type="submit" class="btn btn-primary">
                        <?php echo isset($note) ? 'Aggiorna' : 'Salva'; ?>
                    </button>
                </li>
                <li class="nav-item">
                    <div class="form-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                data-bs-toggle="dropdown" <?php if (!isset($note)) echo 'disabled'; ?>>
                            +
                        </button>
                        <ul class="dropdown-menu" id="add-option">
                            <li><a class="dropdown-item" onclick="addInput('text')">Text</a></li>
                            <li><a class="dropdown-item" onclick="addInput('attachment')">Attachment</a></li>
                            <li><a class="dropdown-item" onclick="addInput('draw')">Draw</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>



    <div style="padding: 15px" class="form-group">
        <label for="title" >Titolo</label>
        <input  name="title" type="text" name="title" class="form-control" id="title" placeholder="Inserisci il titolo" value="<?php if (isset($note)): echo $note->getTitle(); endif; ?>" required>
    </div>
    <div id="attachments-list">
    <?php if (isset($attachments) && count($attachments) > 0): ?>
        <ul>
            <?php foreach ($attachments as $attachment): ?>
                <li data-id="<?php echo $attachment->getId(); ?>">
                <?php
                    $mimeType = $attachment->getMimeType();
                    $filePath = $attachment->getFilePath();

                    if (strpos($mimeType, 'image/') === 0): ?>
                        <img src="<?php echo $filePath; ?>" alt="Immagine" style="max-width: 200px; max-height: 200px;">
                    <?php elseif (strpos($mimeType, 'text/') === 0):
                        $fileContent = file_get_contents($filePath);
                        echo '<pre>' . nl2br(htmlspecialchars($fileContent)) . '</pre>';
                    else: ?>
                        <p><strong>Percorso:</strong> <?php echo htmlspecialchars($filePath); ?></p>
                    <?php endif; ?>

                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAttachmentFROMdb(<?php echo $attachment->getId(); ?>)">X</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="padding: 15px">Inizia a creare!</p>
    <?php endif; ?>
</div>


    <br><br>
    <div id="dynamic-fields"></div>
</form>
<form method="POST" action="<?php echo URL; ?>manage/zip<?php if (isset($note)): echo '/' . $note->getId(); endif; ?>">
    <button class="btn btn-info" id="zipForm" type="submit"
        <?php if (!isset($note)) echo 'disabled'; ?>
        title="<?php echo isset($note) ? 'Esporta la nota in formato ZIP' : 'Salva prima la nota per esportarla'; ?>">
        Esporta ZIP
    </button>
</form>
