
<?php
//per non far dare errore all'IDE
/* @var $note \models\' .,*-' */
?>
<title>Notes</title>

<br>

<div class="">
    <div class="">Note personali:</div>

    <form action="<?php echo URL; ?>home/filter" method="POST">
        <input type='text' name='field'>
        <input type="submit">
    </form>
<br>

    <table class="">
        <?php foreach ($allNote as $single): ?>
            <div class="col-md-6 col-lg-6 mb-6">
                <h6 class="mb-6" style="color: #145eba">
                    <i class="far fa-paper-plane pe-2"></i>
                    <?php echo $single->getTitle(); ?></h6>
                <div class="mb-3 pb-3" style="border-bottom: #145eba solid thin; border-width: thin;">
                    <?php echo $single->getDateCreation(); ?>
                    <input style="text-align: right; background-color: #dc3545" value="ELIMINA" name=“submitButton" type="submit"></input>
                </div>

            </div>
        <?php endforeach; ?>
    </table>
</div>



