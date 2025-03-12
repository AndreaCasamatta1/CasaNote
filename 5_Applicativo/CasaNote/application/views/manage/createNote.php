<script src="application/views/_templates/static/js/manageInput.js"></script>
<div class="m-2 p-2">
    <form id="create-form" method="POST">
        <div class="pt-4"></div>

        <div class="form-group">
            <label for="title">Titolo</label>
            <input type="text" class="form-control" id="title" placeholder="Inserisci il titolo">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-outline-primary position-fixed top-0 end-0 m-3" data-bs-toggle="dropdown">
                +
            </button>
            <ul class="dropdown-menu" id="add-option">
                <li><a class="dropdown-item" href="#" onclick="addInput('text')">Text</a></li>
                <li><a class="dropdown-item" href="#" onclick="addInput('attachment')">Attachment</a></li>
                <li><a class="dropdown-item" href="#" onclick="addInput('draw')">Draw</a></li>
            </ul>
        </div>

        <div id="dynamic-fields"></div>
        <button type="submit" class="btn btn-primary position-fixed bottom-0 end-0 m-3">Salva</button>

    </form>
</div>