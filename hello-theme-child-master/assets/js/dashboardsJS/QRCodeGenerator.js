document.addEventListener("DOMContentLoaded", function() {
    const textInput = document.getElementById("accLink");
    const downloadButton = document.getElementById("downloadQR");

    updateQRCode();

    function updateQRCode() {
        const textValue = textInput.value;
        const qrCodeElement = document.getElementById("qr_code");

        if(qrCodeElement) {
            qrCodeElement.innerHTML = "";

            const qrcode = new QRCode(qrCodeElement, {
                text: textValue,
                width: 160,
                height: 160,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        };
    }

    function downloadQRCode() {
        const qrCodeCanvas = document.querySelector('#qr_code canvas');

        if (qrCodeCanvas) {
            const imageUri = qrCodeCanvas.toDataURL("image/png");
            const link = document.createElement("a");
            link.href = imageUri;
            link.download = "qrcode.png";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    if(textInput && downloadButton) {
        ['change', 'keydown', 'paste', 'input'].forEach(eventType =>
            textInput.addEventListener(eventType, updateQRCode)
        );
    
        downloadButton.addEventListener('click', downloadQRCode);
    };
});
