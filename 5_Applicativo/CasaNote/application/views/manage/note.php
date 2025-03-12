<?php
//per non far dare errore all'IDE
/* @var $allNote \models\' .,*-' */
?>
<title>Notes</title>
<div class="">

    <div class="card card-bordered p-1 m-1">
        <a href="<?php echo URL; ?>/manage/goToCreateNotePage" class="btn btn-outline-primary position-fixed top-0 m-3">
            + Aggiungi Nota
        </a>
        <div class="card-body m-1" style="background-color: white">
            <br>
            <br>
            <h1 class="">Note personali:</h1>
            <br>
            <form action="<?php echo URL; ?>home/main" method="POST">
                <input type='text' name='field' placeholder="Cerca ">
                <input type="submit" value="🔍" style="border: none;background: none" >
            </form>
            <br>
            <table class="">
                <?php foreach ($allNote as $single): ?>
                    <div class="col-md-6 col-lg-6 mb-6">
                        <h6 class="mb-6" style="color: #145eba">
                            <i class="far fa-newspaper pe-2"></i>
                            <?php echo $single->getTitle(); ?>

                        </h6>
                        <div class="mb-3 pb-3" style="border-bottom: #145eba solid thin; border-width: thin;">
                            <?php echo $single->getDateCreation(); ?>
                            <input style="text-align: right; background-color: #ff1e21;" value="X"
                                   name="submitButton" type="submit">
                        </div>
                    </div>
                <?php endforeach; ?>
            </table>
        </div>



