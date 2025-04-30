<script>
function addInput(type) {
    const dynamicFields = document.getElementById('dynamic-fields');
    let newInput;

    if (type === 'text') {
        newInput = document.createElement('div');
        newInput.classList.add('mb-3');
        newInput.innerHTML = `
            <label>Testo</label>
            <textarea name="attachments_text[]" class="form-control" rows="3" placeholder="Scrivi qua..."></textarea>
            <button type="button" class="btn btn-primary mt-2" onclick="saveTextAttachment(this)">Salva Testo</button>
        `;
    } else if (type === 'attachment') {
        newInput = document.createElement('div');
        newInput.classList.add('mb-3');
        newInput.innerHTML = `
            <label>Allegato</label>
            <input type="file" name="attachments_file[]" class="form-control">
            <button type="button" class="btn btn-primary mt-2" onclick="saveFileAttachment(this)">Salva Allegato</button>
        `;
    } else if (type === 'draw') {
        newInput = document.createElement('div');
        newInput.classList.add('mb-3');
        newInput.innerHTML = `
            <label>Disegno</label>
            <canvas id="draw-canvas" class="canvas-container" width="500" height="300" style="border:1px solid #000000;"></canvas>
            <button type="button" class="btn btn-primary mt-2" onclick="saveDrawing(this)">Salva Disegno</button>
            <button type="button" class="btn btn-secondary mt-2" onclick="clearCanvas()">Clear Drawing</button>
        `;
    } else {
        return;
    }

    dynamicFields.appendChild(newInput);
    if (type === 'draw') {
        initDrawing();
    }
}

function saveTextAttachment(button) {
    const textInput = button.previousElementSibling;
    const form = document.querySelector('form');
    const formData = new FormData(form);
    formData.append('attachment_type', 'text');
    formData.append('attachment_content', textInput.value);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo URL; ?>manage/saveAttachment', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                alert('Testo salvato con successo');
            } else {
                alert('Errore nel salvataggio del testo');
            }
        } else {
            alert('Errore nella richiesta.');
        }
    };
    xhr.onerror = function() {
        console.error('Errore:', xhr.statusText);
        alert('Errore nella richiesta.');
    };
    xhr.send(formData);
}
function saveFileAttachment(button) {
    const fileInput = button.previousElementSibling;
    const form = document.querySelector('form');
    const formData = new FormData(form);
    formData.append('attachment_type', 'file');
    formData.append('attachment_file', fileInput.files[0]);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo URL; ?>manage/saveAttachment', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                alert('Allegato salvato con successo');
            } else {
                alert('Errore nel salvataggio dell\'allegato');
            }
        } else {
            alert('Errore nella richiesta.');
        }
    };
    xhr.onerror = function() {
        console.error('Errore:', xhr.statusText);
        alert('Errore nella richiesta.');
    };
    xhr.send(formData);
}
function saveDrawing(button) {
    const canvas = document.getElementById('draw-canvas');
    const dataURL = canvas.toDataURL();
    const form = document.querySelector('form');
    const formData = new FormData(form);
    formData.append('attachment_type', 'draw');
    formData.append('attachment_content', dataURL);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo URL; ?>manage/saveAttachment', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                alert('Disegno salvato con successo');
            } else {
                alert('Errore nel salvataggio del disegno');
            }
        } else {
            alert('Errore nella richiesta.');
        }
    };
    xhr.onerror = function() {
        console.error('Errore:', xhr.statusText);
        alert('Errore nella richiesta.');
    };
    xhr.send(formData);
}

function clearCanvas() {
    const canvas = document.getElementById('draw-canvas');
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}
</script>