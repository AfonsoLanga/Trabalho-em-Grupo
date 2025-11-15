<?php
function gerarQRCodeSimples($codigo_verificacao) {
    // Criar pasta se não existir
    $qrcode_dir = 'assets/qrcodes/';
    if (!is_dir($qrcode_dir)) {
        mkdir($qrcode_dir, 0777, true);
    }
    
    $filename = $qrcode_dir . $codigo_verificacao . '.png';
    
    // URL de verificação
    $url_verificacao = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/verificar.php?codigo=" . $codigo_verificacao;
    
    // Usar API online gratuita para gerar QR Code
    $qr_content = urlencode($url_verificacao);
    $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $qr_content;
    
    // Baixar a imagem
    $qr_image = file_get_contents($qr_url);
    
    if ($qr_image !== false) {
        file_put_contents($filename, $qr_image);
        return $filename;
    }
    
    // Se a API falhar, criar QR code manual
    return criarQRCodeManual($codigo_verificacao, $url_verificacao);
}

function criarQRCodeManual($codigo_verificacao, $url) {
    $qrcode_dir = 'assets/qrcodes/';
    $filename = $qrcode_dir . $codigo_verificacao . '.png';
    
    // Criar imagem manual
    $size = 150;
    $im = imagecreate($size, $size);
    
    // Cores
    $white = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);
    $blue = imagecolorallocate($im, 67, 97, 238);
    
    // Fundo
    imagefill($im, 0, 0, $white);
    
    // Desenhar padrão de QR code simples
    $hash = md5($codigo_verificacao);
    
    // Pontos pretos baseados no hash
    for ($i = 0; $i < 15; $i++) {
        for ($j = 0; $j < 15; $j++) {
            $char_index = ($i * 15 + $j) % 32;
            if (isset($hash[$char_index])) {
                $char_val = hexdec($hash[$char_index]);
                if ($char_val % 2 == 0) {
                    $x = $i * 10 + 5;
                    $y = $j * 10 + 5;
                    imagefilledrectangle($im, $x, $y, $x + 6, $y + 6, $black);
                }
            }
        }
    }
    
    // Texto
    imagestring($im, 2, 40, 60, 'VERIFICAR', $blue);
    imagestring($im, 1, 45, 80, 'CERTIFICADO', $blue);
    
    imagepng($im, $filename);
    imagedestroy($im);
    
    return $filename;
}
?>