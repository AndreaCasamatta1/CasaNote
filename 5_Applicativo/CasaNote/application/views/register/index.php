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
                <label for="password">Password</label>
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