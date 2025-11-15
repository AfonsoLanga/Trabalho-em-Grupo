<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/database.php');

$codigo = $_GET['codigo'] ?? '';
$certificado = null;

if ($codigo) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM certificados WHERE codigo_verificacao = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$codigo]);
        $certificado = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $erro = "Erro ao consultar banco de dados: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Verificar Certificado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .certificado-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .qr-code {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            background: white;
            text-align: center;
        }
        .codigo-verificacao {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-light mb-3">← Voltar para Página Inicial</a>
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php if ($certificado): ?>
            <div class="card certificado-card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">✅ Certificado Válido e Autêntico</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Dados do Certificado -->
                        <div class="col-md-8">
                            <h5 class="mb-4">📋 Dados do Certificado</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Participante:</th>
                                        <td><?php echo htmlspecialchars($certificado['nome_participante']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>E-mail:</th>
                                        <td><?php echo htmlspecialchars($certificado['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Curso/Evento:</th>
                                        <td><?php echo htmlspecialchars($certificado['curso_evento']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Carga Horária:</th>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?php echo $certificado['carga_horaria']; ?> horas
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Data de Emissão:</th>
                                        <td><?php echo date('d/m/Y', strtotime($certificado['data_emissao'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Código de Verificação:</th>
                                        <td>
                                            <div class="codigo-verificacao">
                                                <?php echo $certificado['codigo_verificacao']; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <!-- QR Code e Ações -->
                        <div class="col-md-4">
                            <div class="alert alert-success mb-3">
                                <h6>🔒 Certificado Verificado</h6>
                                <p class="mb-0">Este certificado é válido e está registrado em nosso sistema</p>
                            </div>
                            
                            <!-- QR Code -->
                            <div class="qr-code mb-4">
                                <?php
                                $qrcode_file = 'assets/qrcodes/' . $certificado['codigo_verificacao'] . '.png';
                                if (file_exists($qrcode_file)): 
                                ?>
                                    <img src="<?php echo $qrcode_file; ?>" alt="QR Code" class="img-fluid mb-2" style="max-width: 150px;">
                                    <p class="small text-muted mb-2">Escaneie para verificar este certificado</p>
                                    <a href="<?php echo $qrcode_file; ?>" 
                                       download="qrcode-<?php echo $certificado['codigo_verificacao']; ?>.png" 
                                       class="btn btn-sm btn-outline-primary">
                                        📥 Baixar QR Code
                                    </a>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <small>QR Code não disponível</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Ações do Certificado -->
                            <div class="d-grid gap-2">
                                <?php
                                $arquivo_certificado = 'certificados/certificado_' . $certificado['codigo_verificacao'] . '.html';
                                if (file_exists($arquivo_certificado)): 
                                ?>
                                    <a href="<?php echo $arquivo_certificado; ?>" 
                                       class="btn btn-primary" target="_blank">
                                        📄 Visualizar Certificado
                                    </a>
                                    <a href="<?php echo $arquivo_certificado; ?>" 
                                       download="certificado-<?php echo $certificado['codigo_verificacao']; ?>.html" 
                                       class="btn btn-success">
                                        💾 Baixar Certificado
                                    </a>
                                    <button onclick="imprimirCertificado()" class="btn btn-info">
                                        🖨️ Imprimir
                                    </button>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <small>Arquivo do certificado não encontrado</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Link de Compartilhamento -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6>🔗 Compartilhar Verificação</h6>
                                <p class="mb-2">Link para verificar este certificado:</p>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="linkVerificacao" 
                                           value="<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?codigo=" . $certificado['codigo_verificacao']; ?>" 
                                           readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copiarLink()">
                                        📋 Copiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php elseif ($codigo): ?>
            <!-- Certificado não encontrado -->
            <div class="card certificado-card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">❌ Certificado Não Encontrado</h4>
                </div>
                <div class="card-body text-center">
                    <p>O código de verificação <strong>"<?php echo htmlspecialchars($codigo); ?>"</strong> não foi encontrado em nosso sistema.</p>
                    <p>Verifique se o código está correto ou entre em contato conosco.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="index.php" class="btn btn-primary me-md-2">🔍 Nova Verificação</a>
                        <a href="admin/login.php" class="btn btn-outline-secondary">🔧 Área Administrativa</a>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Página inicial da verificação -->
            <div class="card certificado-card">
                <div class="card-body text-center py-5">
                    <h4 class="mb-3">🔍 Verificar Certificado</h4>
                    <p class="mb-4">Digite o código do certificado para verificar sua autenticidade.</p>
                    <a href="index.php" class="btn btn-primary btn-lg">Fazer Verificação</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function imprimirCertificado() {
        const url = '<?php echo $arquivo_certificado; ?>';
        const novaJanela = window.open(url, '_blank');
        novaJanela.onload = function() {
            novaJanela.print();
        };
    }
    
    function copiarLink() {
        const linkInput = document.getElementById('linkVerificacao');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(linkInput.value).then(function() {
            // Mostrar feedback
            const btn = document.querySelector('button[onclick="copiarLink()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '✅ Copiado!';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function() {
            alert('Erro ao copiar link.');
        });
    }
    </script>
</body>
</html>