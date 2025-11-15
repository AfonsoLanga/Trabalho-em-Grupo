<?php
// Teste simples para validar a geração de QR usando vendor/qrlib.php
require_once __DIR__ . '/../vendor/qrlib.php';

$outdir = __DIR__ . '/../assets/qrcodes';
if (!is_dir($outdir)) {
    mkdir($outdir, 0777, true);
}

$filename = $outdir . '/test_qr_' . time() . '.png';

// Gera o QR (retorna true/false na nossa implementação)
$result = QRcode::png('Teste QR ' . date('c'), $filename);

if ($result) {
    echo "OK: $filename\n";
    exit(0);
} else {
    echo "FAIL\n";
    exit(1);
}
