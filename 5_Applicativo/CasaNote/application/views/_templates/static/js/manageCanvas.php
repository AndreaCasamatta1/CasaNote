<script>
    let isDrawing = false;
    let currentColor = '#000000';
    let isErasing = false;
    let lastX = 0;
    let lastY = 0;


    //funzione fatta con l'aiuto di chatgpt
    function initDrawing() {
        const canvas = document.getElementById('draw-canvas');
        const ctx = canvas.getContext('2d');
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.lineWidth = 2;
        ctx.strokeStyle = currentColor;

        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            [lastX, lastY] = [e.offsetX, e.offsetY];
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!isDrawing) return;
            ctx.strokeStyle = isErasing ? '#ffffff' : currentColor;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            [lastX, lastY] = [e.offsetX, e.offsetY];
        });

        canvas.addEventListener('mouseup', () => isDrawing = false);
        canvas.addEventListener('mouseout', () => isDrawing = false);
    }

    function setColor(color) {
        currentColor = color;
        isErasing = false;
    }

    function enableEraser() {
        isErasing = true;
    }

    //funzione fatta con l'aiuto di chatgpt
    function clearCanvas() {
        const canvas = document.getElementById('draw-canvas');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }


</script>