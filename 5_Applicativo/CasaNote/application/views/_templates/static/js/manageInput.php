<script>
function addInput(type) {
    const dynamicFields = document.getElementById('dynamic-fields');
    let newInput = document.createElement('div');
    newInput.classList.add('mb-3', 'dynamic-input');

    let content = '';

    if (type === 'text') {
        content = `
            <label>Testo</label>
            <textarea name="attachments_text[]" class="form-control" rows="3" placeholder="Scrivi qua..."></textarea>
            <button type="button" class="btn btn-primary mt-2" onclick="saveTextAttachment(this)">Salva Testo</button>
        `;
    } else if (type === 'attachment') {
        content = `
            <label>Allegato</label>
            <input type="file" name="attachments_file[]" class="form-control">
            <button type="button" class="btn btn-primary mt-2" onclick="saveFileAttachment(this)">Salva Allegato</button>
        `;
    } else if (type === 'draw') {
        content = `
            <canvas id="draw-canvas" class="canvas-container" width="500" height="300" style="border:1px solid #000000;"></canvas>
            <button type="button" class="btn btn-primary mt-2" onclick="initDrawing()">disegna</button>
            <button type="button" class="btn btn-primary mt-2" onclick="saveDrawing(this)">Salva Disegno</button>
            <button type="button" class="btn btn-secondary mt-2" onclick="clearCanvas()">Clear Drawing</button>
        `;
    } else {
        return;
    }
    
    newInput.innerHTML = `
        <div>
            <div>
                ${content}
            </div>
            <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeInputFromDOM(this)" title="Rimuovi">✖</button>
        </div>
    `;

    dynamicFields.appendChild(newInput);
}

function removeInputFromDOM(button) {
    /*Il metodo .closest() in JavaScript 
    /*è usato per trovare l'antenato (parent) più vicino che corrisponde a un selettore CSS, 
    /*partendo da un elemento specifico.
    il .closest('.dynamic-input') parte dall'elemento button (cioè il pulsante "✖") 
    e risale il DOM fino a trovare il primo elemento antenato (genitore, nonno, ecc.) 
    che ha la classe dynamic-input.
    */
    const parent = button.closest('.dynamic-input');
    if (parent) {
    /*
    Elimino l'elemento solo dal DOM in caso l'id è nullo, e quindi l'attachment non
    è ancora stato salvato
    */
        parent.remove();
    }
}

function removeAttachmentFROMdb(attachmentId) {
    if (confirm('Sei sicuro di procedere?')) {
        const formData = new FormData();
    /*
    aggiunge l'ID dell'allegato (attachmentId) all'oggetto FormData con la chiave 'attachment_id', 
    in modo che questo dato possa essere inviato al server tramite una richiesta AJAX.
    */
        formData.append('attachment_id', attachmentId);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo URL; ?>manage/deleteAttachment', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    const attachmentItem = document.querySelector(`li[data-id="${attachmentId}"]`);
                    if (attachmentItem) {
                        attachmentItem.remove();
                    }
                } else {
                    alert('Errore nell\'eliminazione dell\'allegato');
                }
            } else {
                alert('Errore nella richiesta.');
            }
        };

        xhr.onerror = function() {
            alert('Errore nella richiesta.');
        };

        xhr.send(formData);
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
</script>