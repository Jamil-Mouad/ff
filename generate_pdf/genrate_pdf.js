function generatePDF() {
    prepareForPDF();

    const element = document.getElementById('pdfContent');
    const opt = {
           /* margin: 10,*/
            filename: 'evaluation_stage.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 4, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

    html2pdf().from(element).set(opt).save().then(() => {
        restoreAfterPDF();
    });
}

window.onload = function() {
  document.getElementById('generatePDF').addEventListener('click', generatePDF);
}

html2canvas(document.getElementById('pdfContent'), { scale: 4 }).then(canvas => {
  const imgData = canvas.toDataURL('image/jpeg', 1.0);
  const pdf = new jsPDF('p', 'mm', 'a4');
  const pageWidth = pdf.internal.pageSize.getWidth();
  const pageHeight = pdf.internal.pageSize.getHeight();
  pdf.addImage(imgData, 'JPEG', 0, 0, pageWidth, pageHeight);
  pdf.save('evaluation_stage.pdf');
});

/*document.getElementById('generatePDF').addEventListener('click', function() {
            // Hide the buttons
            document.getElementById('generatePDF').style.display = 'none';
            // Add a delay to ensure the elements are hidden
            setTimeout(function() {
                html2canvas(document.getElementById('pdfContent'), {
                    scale: 10, // Increase the resolution for better quality
                    logging: true,
                    useCORS: true // Handle external resources like images
                }).then(function(canvas) {
                    var imgData = canvas.toDataURL('image/png');
                    var doc = new jsPDF('p', 'mm', 'a4');
                    var imgWidth = 210; // A4 width in mm
                    var pageHeight = 297; // A4 height in mm
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;

                    var position = 0;

                    doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        doc.addPage();
                        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    doc.save('pfe_evaluation.pdf');
                    // Show the buttons again
                    document.getElementById('generatePDF').style.display = 'block';
                });
            }, 1000); // Delay for 500 milliseconds to ensure elements are hidden
        });*/