<?php
include('includes/funcoes.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Teste Avançado QR Code</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; }
        img { border: 2px solid #333; margin: 10px; }
    </style>
</head>
<body>
    <h1>🧪 TESTE AVANÇADO DO QR CODE</h1>";

// Teste 1: Gerar QR Code
$test_code = 'TEST_' . uniqid() . '_' . rand(1000, 9999);
echo "<div class='info'><strong>Código de teste:</strong> $test_code</div>";

$qr_file = gerarQRCodeGoogle($test_code);

echo "<h2>📊 Resultado do Teste:</h2>";

if ($qr_file && file_exists($qr_file)) {
    $file_size = filesize($qr_file);
    $file_info = getimagesize($qr_file);
    
    echo "<p class='success'>✅ QR CODE GERADO COM SUCESSO!</p>";
    echo "<p><strong>Arquivo:</strong> $qr_file</p>";
    echo "<p><strong>Tamanho:</strong> $file_size bytes</p>";
    
    if ($file_info) {
        echo "<p><strong>Tipo:</strong> " . $file_info['mime'] . "</p>";
        echo "<p><strong>Dimensões:</strong> " . $file_info[0] . "x" . $file_info[1] . " pixels</p>";
    }
    
    echo "<img src='$qr_file' alt='QR Code Test'>";
    echo "<br>";
    echo "<a href='$qr_file' download='qrcode-teste.png' class='success'>📥 Baixar QR Code</a>";
    
    // Testar se o arquivo é uma imagem válida
    echo "<h3>🔍 Verificação do Arquivo:</h3>";
    $image_info = getimagesize($qr_file);
    if ($image_info && $image_info[0] > 0) {
        echo "<p class='success'>✅ Arquivo é uma imagem PNG válida</p>";
    } else {
        echo "<p class='error'>❌ Arquivo não é uma imagem válida</p>";
    }
    
} else {
    echo "<p class='error'>❌ FALHA AO GERAR QR CODE</p>";
    echo "<p>Arquivo não foi criado ou está vazio</p>";
}

// Teste 2: Verificar permissões
echo "<h2>📁 Verificação do Sistema:</h2>";
$qrcode_dir = 'assets/qrcodes/';

if (is_dir($qrcode_dir)) {
    echo "<p class='success'>✅ Pasta assets/qrcodes/ existe</p>";
    
    // Testar permissão de escrita
    $test_file = $qrcode_dir . 'teste_permissao.txt';
    if (file_put_contents($test_file, 'teste de permissão ' . date('Y-m-d H:i:s'))) {
        echo "<p class='success'>✅ Permissão de escrita OK</p>";
        echo "<p><strong>Conteúdo do teste:</strong> " . file_get_contents($test_file) . "</p>";
        unlink($test_file);
    } else {
        echo "<p class='error'>❌ Sem permissão de escrita na pasta</p>";
    }
    
    // Listar arquivos na pasta
    $files = scandir($qrcode_dir);
    $qr_files = array_filter($files, function($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === 'png';
    });
    
    echo "<p><strong>Arquivos PNG na pasta:</strong> " . count($qr_files) . "</p>";
    
} else {
    echo "<p class='error'>❌ Pasta assets/qrcodes/ não existe</p>";
}

// Teste 3: Verificar função GD
echo "<h2>🖼️ Verificação da Biblioteca GD:</h2>";
if (extension_loaded('gd') && function_exists('gd_info')) {
    $gd_info = gd_info();
    echo "<p class='success'>✅ Biblioteca GD está instalada</p>";
    echo "<p><strong>Versão GD:</strong> " . $gd_info['GD Version'] . "</p>";
    echo "<p><strong>Suporte PNG:</strong> " . ($gd_info['PNG Support'] ? '✅ Sim' : '❌ Não') . "</p>";
} else {
    echo "<p class='error'>❌ Biblioteca GD não está instalada</p>";
}

echo "</body></html>";
?>