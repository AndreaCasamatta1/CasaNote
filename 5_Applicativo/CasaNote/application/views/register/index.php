<div class="col d-flex justify-content-center">
    <div class="card card-bordered mt-4 p-2" style="width: 18rem;">
        <form method="POST" action="<?php echo URL; ?>register/register">

            <h2>Register</h2>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <br>
            <div class="form-group">
                <label for="username">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Name" required/>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" class="form-control" id="email" placeholder="Email" required/>
            </div>
            <div class="form-group">
                Password
                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top"
                   title="• Deve essere lunga almeno 6 caratteri
                 • Deve contenere almeno 1 maiuscola, 1 minuscola e 1 numero
                 • Deve contenere almeno 1 carattere speciale (non lettera , accentata è carattere speciale, e numero, @,$, à,è,(,&)"
                   style="cursor: pointer; margin-left: 8px;">
                </i>
                </label>

                <input type="password" name="pass" class="form-control" id="pass" placeholder="Password" required/>
            </div>

            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" name="pass2" class="form-control" id="pass2" placeholder="Password" required/>
            </div>

            <br>
            <input type="submit" class="btn btn-primary" name="register" value="Sign Up"/>
        </form>

    </div>
</div>

<!-- Inizializza il Tooltip, fonte https://stackoverflow.com/questions/50017486/bootstrap-tooltip-is-not-working-on-my-web-project -->
<script>
    $(document).ready(function() {
        // Attiva il tooltip su tutti gli elementi con data-toggle="tooltip"
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>