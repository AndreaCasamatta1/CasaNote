<title>Notes</title>

<br>

    <div class="card card-bordered p-1 m-1">
        <div class="card-body m-1" style="background-color: white">
            <br>

            <div class="page-header">
                <h2 class="text-center" style="color:red;font-weight: bold">NOTES</h2>
            </div>

            <!--Section: NOTES-->
            <section>
                <p class="text-center mb-5">
                    Le tue note
                </p>

                <div class="row">
                    <?php foreach ($allNote as $single): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <h6 class="mb-3" style="color: #145eba"><i
                                        class="far fa-paper-plane pe-2"></i> <?php echo $single->getTitle(); ?></h6>
                                <p class="mb-2 pb-2" style="border-bottom: #145eba solid thin; border-width: thin">
                                    <?php echo $single->getDateCreation(); ?>
                                </p>
                        </div>
                    <?php endforeach ?>
                </div>

            </section>
            <!--Section: NOTES-->
        </div>
    </div>


