<?php
//per non far dare errore all'IDE
/* @var $allNote \models\' .,*-' */
?>
<title>Notes</title>
<br>
<div class="">
    <div class="">Note personali:</div>

    <div class="card card-bordered p-1 m-1">
        <a href="<?php echo URL; ?>/manage/goToCreateNotePage" class="btn btn-outline-primary position-fixed top-0 m-3">
            +
        </a>
        <div class="card-body m-1" style="background-color: white">
            <br>
    <br>
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
                    <input style="text-align: right; background-color: #dc3545" value="ELIMINA" name=“submitButton" type="submit">
                </div>

            </div>
        <?php endforeach; ?>
    </table>
</div>



