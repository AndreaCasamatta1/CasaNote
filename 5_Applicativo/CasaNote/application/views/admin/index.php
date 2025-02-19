<div class="container">
    <div class="m-2">
        <h2>GESTIONE FAQ</h2>
        <div>
            <table class="table">
                <thead style="font-weight: bold;">
                <tr>
                    <td>id</td>
                    <td>domanda</td>
                    <td>risposta</td>
                    <td>link</td>
                    <td>posizione</td>
                    <td>Modifica</td>
                    <td>Elimina</td>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($allFaq as $faq): ?>
                    <tr>
                        <?php foreach ((array)$faq as $field): ?>
                            <td><?php echo $field; ?></td>
                        <?php endforeach; ?>
                        <td><a href="<?php echo URL . 'admin/deleteFaq/' . $faq->getId(); ?>" class="btn btn-danger"
                               onclick="return confirm('Sicuro?')">x</a></td>
                        <td><a href="<?php echo URL . 'admin/manageFaq/' . $faq->getId(); ?>" class="btn btn-primary">Modifica</a>
                        </td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
            </table>
            <a href="<?php echo URL ?>admin/manageFaq" class="btn btn-dark btn-block">Nuova FAQ</a>
            <br>
            <form action="<?php echo URL ?>admin/setColor" method="POST">
                <input type="color" name="color" class="btn-block">
                <input type="submit" class="btn btn-block" name="colore-button" value="Imposta Colore">
            </form>
            <br>
            <a href="<?php echo URL ?>login/logout" class="btn btn-info btn-block">Logout</a>

        </div>
    </div>
</div>