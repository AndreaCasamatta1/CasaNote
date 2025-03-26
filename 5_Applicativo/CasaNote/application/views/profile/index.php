<div class="col d-flex justify-content-center">
    <div class="card card-bordered mt-4 p-2" style="width: 18rem;">
        <form method="POST" action="<?php echo URL; ?>user/updateName">
            <h2>Modifica Nome</h2>
            <br>
            <div class="form-group">
                <label for="newName">Nuovo Nome</label>
                <input type="text" name="newName" class="form-control" id="newName" placeholder="Nuovo Nome" required/>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" name="updateName" value="Cambia Nome"/>
        </form>
    </div>
</div>

<div class="col d-flex justify-content-center">
    <div class="card card-bordered mt-4 p-2" style="width: 18rem;">
        <form method="POST" action="<?php echo URL; ?>user/updatePassword">
            <h2>Modifica Password</h2>
            <br>
            <div class="form-group">
                <label for="oldPassword">Password Attuale</label>
                <input type="password" name="oldPassword" class="form-control" id="oldPassword" placeholder="Password Attuale" required/>
            </div>
            <div class="form-group">
                <label for="newPassword">Nuova Password</label>
                <input type="password" name="newPassword" class="form-control" id="newPassword" placeholder="Nuova Password" required/>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" name="updatePassword" value="Cambia Password"/>
        </form>
    </div>
</div>

<div class="col d-flex justify-content-center">
    <div class="card card-bordered mt-4 p-2" style="width: 18rem;">
        <form method="POST" action="<?php echo URL; ?>user/deleteAccount" onsubmit="return confirm('Sei sicuro di voler eliminare il tuo account?');">
            <h2>Elimina Account</h2>
            <br>
            <input type="submit" class="btn btn-danger" name="deleteAccount" value="Delete Account"/>
        </form>
    </div>
</div>
