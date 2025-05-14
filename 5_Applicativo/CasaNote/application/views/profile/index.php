<div class="container mt-4">

    <!-- Dati utente -->
    <div class="row justify-content-center mb-4">
        <div class="col-auto">
            <h4>Nome attuale:
                <strong><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Nome non disponibile'; ?></strong>
            </h4>
        </div>
        <div class="col-auto">
            <h4>Email:
                <strong><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Email non disponibile'; ?></strong>
            </h4>
        </div>
    </div>

    <div class="row justify-content-center gap-4">

        <!-- Modifica Nome -->
        <div class="col-md-3 d-flex justify-content-center">
            <div class="card card-bordered p-3" style="width: 100%; min-height: 28rem;">
                <form method="POST" action="<?php echo URL; ?>user/updateName" class="d-flex flex-column h-100 justify-content-between">
                    <div>
                        <h2 class="h5">Modifica Nome</h2>
                        <div class="form-group mt-4">
                            <label for="newName">Nuovo Nome</label>
                            <input type="text" name="newName" class="form-control" id="newName" placeholder="Nuovo Nome" required />
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary mt-3" name="updateName" value="Cambia Nome" />
                </form>
            </div>
        </div>

        <!-- Modifica Password + Requisiti -->
        <div class="col-md-6 d-flex justify-content-center">
            <div class="card card-bordered p-3" style="width: 100%; min-height: 28rem;">
                <div class="row h-100">
                    <!-- Requisiti -->
                    <div class="col-md-6 border-end">
                        <h5><strong>Requisiti Password:</strong></h5>
                        <ul class="mt-3">
                            <li>Minimo 6 caratteri</li>
                            <li>Almeno 1 maiuscola, 1 minuscola e 1 numero</li>
                            <li>Almeno 1 carattere speciale (@, $, à, è, (, &...)</li>
                        </ul>
                    </div>

                    <!-- Modifica Password -->
                    <div class="col-md-6">
                        <form method="POST" action="<?php echo URL; ?>user/updatePassword" class="d-flex flex-column h-100 justify-content-between">
                            <div>
                                <h2 class="h5">Modifica Password</h2>
                                <div class="form-group mt-4">
                                    <label for="oldPassword">Password Attuale</label>
                                    <input type="password" name="oldPassword" class="form-control" id="oldPassword" placeholder="Password Attuale" required />
                                </div>
                                <div class="form-group mt-3">
                                    <label for="newPassword">Nuova Password</label>
                                    <input type="password" name="newPassword" class="form-control" id="newPassword" placeholder="Nuova Password" required />
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary mt-3" name="updatePassword" value="Cambia Password" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Elimina Account -->
        <div class="col-md-3 d-flex justify-content-center">
            <div class="card card-bordered p-3" style="width: 100%; min-height: 28rem;">
                <form method="POST" action="<?php echo URL; ?>user/deleteAccount" onsubmit="return confirm('Sei sicuro di voler eliminare il tuo account?');" class="d-flex flex-column h-100 justify-content-between">
                    <div>
                        <h2 class="h5">Elimina Account</h2>
                    </div>
                    <input type="submit" class="btn btn-danger mt-3" name="deleteAccount" value="Delete Account" />
                </form>
            </div>
        </div>

    </div>
</div>
