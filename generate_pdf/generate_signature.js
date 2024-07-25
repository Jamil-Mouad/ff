let canvases = [];
let contexts = [];

// Initialiser tous les canvas
for (let i = 0; i < jury_num; i++) {
    let canvas = document.getElementById('signatureCanvas' + i);
            let context = canvas.getContext('2d');
            canvases.push(canvas);
            contexts.push(context);
    
            canvas.addEventListener('mousedown', startDrawing.bind(null, i));
            canvas.addEventListener('mousemove', draw.bind(null, i));
            canvas.addEventListener('mouseup', stopDrawing.bind(null, i));
            canvas.addEventListener('mouseout', stopDrawing.bind(null, i));
        }

            let isDrawing = false;
            let lastX = 0;
            let lastY = 0;

            function startDrawing(index, e) {
            isDrawing = true;
            [lastX, lastY] = [e.offsetX, e.offsetY];
    }

        function draw(index, e) {
            if (!isDrawing) return;
                contexts[index].beginPath();
                contexts[index].moveTo(lastX, lastY);
                contexts[index].lineTo(e.offsetX, e.offsetY);
                contexts[index].stroke();
                [lastX, lastY] = [e.offsetX, e.offsetY];
            }

        function stopDrawing() {
            isDrawing = false;
            }

        function clearSignature(index) {
            contexts[index].clearRect(0, 0, canvases[index].width, canvases[index].height);
            }

// Ajoutez ceci après les déclarations existantes
let coordinatorCanvas = document.getElementById('coordinatorSignatureCanvas');
let coordinatorContext = coordinatorCanvas.getContext('2d');

// Fonction pour commencer à dessiner
function startCoordinatorDrawing(e) {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

// Fonction pour dessiner
function drawCoordinator(e) {
    if (!isDrawing) return;
    coordinatorContext.beginPath();
    coordinatorContext.moveTo(lastX, lastY);
    coordinatorContext.lineTo(e.offsetX, e.offsetY);
    coordinatorContext.stroke();
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

// Fonction pour arrêter de dessiner
function stopCoordinatorDrawing() {
    isDrawing = false;
}

// Fonction pour effacer la signature du coordinateur
function clearCoordinatorSignature() {
    coordinatorContext.clearRect(0, 0, coordinatorCanvas.width, coordinatorCanvas.height);
}

// Ajoutez ces écouteurs d'événements
coordinatorCanvas.addEventListener('mousedown', startCoordinatorDrawing);
coordinatorCanvas.addEventListener('mousemove', drawCoordinator);
coordinatorCanvas.addEventListener('mouseup', stopCoordinatorDrawing);
coordinatorCanvas.addEventListener('mouseout', stopCoordinatorDrawing);    
;