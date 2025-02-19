<title>FAQ</title>

<br>

    <div class="card card-bordered p-1 m-1">
        <div class="card-body m-1" style="background-color: white">
            <br>

            <div class="page-header">
                <h2 class="text-center" style="color:red;font-weight: bold">FAQ</h2>
            </div>

            <!--Section: FAQ-->
            <section>
                <p class="text-center mb-5">
                    Trova qui sotto le risposte alle domande più frequenti
                </p>

                <div class="row">
                    <?php foreach ($allFaq as $single): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <h6 class="mb-3" style="color: #145eba"><i
                                        class="far fa-paper-plane pe-2"></i> <?php echo $single->getQuestion(); ?></h6>

                            <?php if (!$single->getLink()): ?>
                                <p class="mb-2 pb-2" style="border-bottom: #145eba solid thin; border-width: thin">
                                    <?php echo $single->getAnswer(); ?>
                                </p>
                            <?php else: ?>
                                <p class="mb-2 pb-2" style="border-bottom: #145eba solid thin; border-width: thin">
                                    <a href="<?php echo $single->getLink(); ?>"
                                       style="color: red; text-decoration: underline"><?php echo $single->getAnswer(); ?></a>
                                </p>
                            <?php endif ?>
                        </div>
                    <?php endforeach ?>
                </div>

            </section>
            <!--Section: FAQ-->
        </div>
    </div>


