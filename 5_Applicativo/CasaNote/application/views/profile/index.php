<div class="container py-5">

    <!-- Dati utente -->
    <div class="row justify-content-center mb-5 text-center">
        <div class="col-12 col-md-5 mb-2">
            <h4 class="fw-semibold text-primary">
                Nome attuale:
                <strong class="text-dark">
                    <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Nome non disponibile'; ?>
                </strong>
            </h4>
        </div>
        <div class="col-12 col-md-5">
            <h4 class="fw-semibold text-primary">
                Email:
                <strong class="text-dark">
                    <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Email non disponibile'; ?>
                </strong>
            </h4>
        </div>
    </div>

    <!-- Card funzionali -->
    <div class="row g-4">

        <!-- Modifica Nome -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <form method="POST" action="<?php echo URL; ?>user/updateName" class="h-100 d-flex flex-column">
                        <div>
                            <h5 class="card-title text-center text-primary">Modifica Nome</h5>
                            <div class="form-group mt-4">
                                <label for="newName" class="form-label">Nuovo Nome</label>
                                <input type="text" name="newName" id="newName" class="form-control" placeholder="Nuovo Nome" required />
                            </div>
                        </div>
                        <div class="mt-auto pt-3">
                            <input type="submit" name="updateName" value="Cambia Nome" class="btn btn-primary w-100" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modifica Password -->
        <div class="col-12 col-md-4">
            <div class="card shadow-lg h-100 border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <form method="POST" action="<?php echo URL; ?>user/updatePassword" class="d-flex flex-column h-100 justify-content-between">
                        <div>
                            <h5 class="card-title text-center text-primary fw-semibold">Modifica Password</h5>

                            <!-- Campo: Password Attuale -->
                            <div class="form-group mt-4">
                                <label for="oldPassword" class="form-label fw-semibold">Password Attuale</label>
                                <input type="password" name="oldPassword" class="form-control rounded-3 shadow-sm" id="oldPassword" placeholder="Password attuale" required />
                            </div>

                            <!-- Campo: Nuova Password + Tooltip -->
                            <div class="form-group mt-3">
                                <label for="newPassword" class="form-label fw-semibold">
                                    Nuova Password
                                    <span
                                        data-bs-toggle="tooltip"
                                        title="Min. 6 caratteri, 1 maiuscola, 1 minuscola, 1 numero e 1 simbolo (@, $, &, ecc.)"
                                        style="cursor: help; color: #000000; font-size: 0.9rem;">
                                        <i class="fas fa-info-circle"/> </i>
                            </span>
                                </label>
                                <input
                                    type="password"
                                    name="newPassword"
                                    class="form-control rounded-3 shadow-sm"
                                    id="newPassword"
                                    placeholder="Nuova password"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Bottone centrato -->
                        <div class="text-center mt-4">
                            <button
                                type="submit"
                                name="updatePassword"
                                class="btn btn-primary px-4 py-2 w-75">
                                Aggiorna Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Elimina Account -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100 border-danger">
                <div class="card-body d-flex flex-column justify-content-between">
                    <form method="POST" action="<?php echo URL; ?>user/deleteAccount"
                          onsubmit="return confirm('Sei sicuro di voler eliminare il tuo account?');"
                          class="h-100 d-flex flex-column">
                        <div>
                            <h5 class="card-title text-center text-danger">Elimina Account</h5>
                            <p class="text-center small text-muted mt-3">
                                Questa azione è irreversibile. Tutti i tuoi dati saranno eliminati definitivamente.
                            </p>
                        </div>
                        <div class="mt-auto pt-3">
                            <input type="submit" name="deleteAccount" value="Elimina Account" class="btn btn-danger w-100" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

