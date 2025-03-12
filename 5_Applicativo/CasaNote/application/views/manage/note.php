<?php
//per non far dare errore all'IDE
/* @var $note \models\Note */
?>

<div class="m-2 p-2">
    <h3>Note</h3>
    <form action="<?php echo URL; ?>admin/saveOrUpdateFaq" method="POST">
        <input type="hidden" name="id" value="<?php if (isset($faq)): echo $faq->getId(); endif; ?>">
        <div class="form-group">
            <label for="questionInput">Domanda</label>
            <input type="text" name="question" class="form-control" id="questionInput" placeholder="Domanda"
                   value="<?php if (isset($faq)): echo $faq->getQuestion(); endif; ?>" required/>
        </div>
        <div class="form-group">
            <label for="ris">Risposta</label>
            <input type="text" name="answer" class="form-control" id="ris" placeholder="Risposta"
                   value="<?php if (isset($faq)): echo $faq->getAnswer(); endif; ?>"
                   required/>
        </div>
        <div class="form-group">
            <label for="link">Link</label>
            <input type="text" name="link" class="form-control" id="link" placeholder="Link"
                   value="<?php if (isset($faq)): echo $faq->getLink(); endif; ?>"/>
        </div>
        <div class="form-group">

            <label for="pos">Posizione</label>
            <input type="number" name="position" class="form-control" id="pos" placeholder="Posizione"
                   value="<?php if (isset($faq)): echo $faq->getPosition(); endif; ?>"/>
        </div>
        <input type="submit" class="btn btn-primary" name="add_modify" value="Submit"/>
    </form>
</div>

