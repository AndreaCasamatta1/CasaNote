<script src="application/views/_templates/static/js/manageInput.js"></script>
<form method="POST" action="manage/saveOrUpdateNote">
    <nav class="navbar navbar-expand-lg m-0">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><img src="application/libs/img/logo.png" width="30" height="30" alt=""></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button type="submit" class="btn btn-primary">Salva</button>
                </li>
                <li class="nav-item">
                    <div class="form-group">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                            +
                        </button>
                        <ul class="dropdown-menu" id="add-option">
                            <li><a class="dropdown-item" onclick="addInput('text')">Text</a></li>
                            <li><a class="dropdown-item" onclick="addInput('attachment')">Attachment</a></li>
                            <li><a class="dropdown-item" onclick="addInput('draw')">Draw</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="form-group">
        <label for="title">Titolo</label>
        <input type="text" name="title" class="form-control" id="title" placeholder="Inserisci il titolo" required>
    </div>
    <br>
    <br>
    <div id="dynamic-fields"></div>
</form>



