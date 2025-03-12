function addInput(type) {
    const dynamicFields = document.getElementById('dynamic-fields');
    let newInput;

    if (type === 'text') {
        newInput = document.createElement('div');
        newInput.classList.add('mb-3');
        newInput.innerHTML = `
            <label>Testo</label>
            <textarea class="form-control" rows="3" placeholder="Scrivi qua..."></textarea>
        `;

    } else if (type === 'attachment') {
        newInput = document.createElement('div');
        newInput.classList.add('mb-3');
        newInput.innerHTML = `
            <label>Allegato</label>
            <input type="file" class="form-control">
        `;

    } else if (type === 'draw') {
        newInput = document.createElement('div');
        newInput.classList.add('mb-3');
        newInput.innerHTML = `
            <label>Disegno</label>
            <canvas id="draw-canvas" class="canvas-container"></canvas>
            <button type="button" class="btn btn-secondary mt-2" onclick="clearCanvas()">Clear Drawing</button>
        `;
    } else {
        return;
    }

    dynamicFields.appendChild(newInput);
}