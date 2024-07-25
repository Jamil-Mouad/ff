//Modifiez  JavaScript pour ajouter cette classe avant de générer le PDF
function prepareForPDF() {
    const elementsToHide = document.querySelectorAll('.block2, #generatePDF, .signature-container button');
    elementsToHide.forEach(el => el.classList.add('hide-for-pdf'));

    const canvases = document.querySelectorAll('canvas');
    canvases.forEach(canvas => canvas.style.border = 'none');
}

function restoreAfterPDF() {
    const elementsToShow = document.querySelectorAll('.hide-for-pdf');
    elementsToShow.forEach(el => el.classList.remove('hide-for-pdf'));

    const canvases = document.querySelectorAll('canvas');
    canvases.forEach(canvas => canvas.style.border = '1px solid #000');
}