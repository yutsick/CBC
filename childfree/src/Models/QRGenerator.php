<?php

namespace QRGenerator;

use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\ImageRenderer\Png;

class QRCodeGenerator {

    public static function generateQRCode($data, $size = 400) {
        $writer = new Writer(new ImageRenderer(new Png(), $size));
        $qrCode = $writer->writeString($data);
        return 'data:image/png;base64,' . base64_encode($qrCode);
    }
}

add_shortcode('qr_code', 'generate_qr_code_shortcode');

function generate_qr_code_shortcode($atts) {
    // Usage [qr_code data="text for qr" size="400"]
    $data = isset($atts['data']) ? esc_attr($atts['data']) : 'https://example.com';
    $size = isset($atts['size']) ? intval($atts['size']) : 400;

    $qr_code = QRCodeGenerator::generateQRCode($data, $size);

    return '<img src="' . $qr_code . '" alt="QR Code">';
}
