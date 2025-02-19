<div class="col d-flex justify-content-center">

    <div class="card card-bordered mt-4 p-2" style="width: 18rem;">
        <form method="POST" action="<?php echo URL; ?>login/logIn">

            <h2>Login</h2>
            <br>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Username" required/>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="pass" class="form-control" id="password" placeholder="Password" required/>
            </div>

                <br>
                <input type="submit" class="btn btn-primary" name="login" value="Login"/>
        </form>

    </div>
</div>