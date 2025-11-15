<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Criar pastas necessárias
if (!is_dir('../certificados')) {
    mkdir('../certificados', 0777, true);
}
if (!is_dir('../assets/qrcodes')) {
    mkdir('../assets/qrcodes', 0777, true);
}

$sucesso = '';
$erro = '';

if ($_POST) {
    include('../includes/database.php');
    include('../includes/funcoes.php');
    
    // Validar dados
    $erros_validacao = validarDadosCertificado($_POST);
    
    if (empty($erros_validacao)) {
        $database = new Database();
        $db = $database->getConnection();
        
        $nome = trim($_POST['nome_participante']);
        $email = trim($_POST['email']);
        $curso = trim($_POST['curso_evento']);
        $carga_horaria = intval($_POST['carga_horaria']);
        $codigo = gerarCodigoVerificacao();
        
        try {
            // Preparar dados
            $dados_certificado = [
                'nome_participante' => $nome,
                'curso_evento' => $curso,
                'carga_horaria' => $carga_horaria,
                'codigo_verificacao' => $codigo
            ];
            
            // Gerar certificado HTML
            $arquivo = gerarCertificadoHTML($dados_certificado);
            
            // Inserir no banco
            $query = "INSERT INTO certificados 
                     (nome_participante, email, curso_evento, data_emissao, carga_horaria, codigo_verificacao, arquivo_pdf) 
                     VALUES (?, ?, ?, CURDATE(), ?, ?, ?)";
            
            $stmt = $db->prepare($query);
            
            if ($stmt->execute([$nome, $email, $curso, $carga_horaria, $codigo, $arquivo])) {
                $sucesso = "
                <div class='alert alert-success'>
                    <h5>✅ Certificado gerado com sucesso!</h5>
                    <p><strong>📋 Código do Certificado:</strong> <code>$codigo</code></p>
                    <p><strong>👤 Participante:</strong> $nome</p>
                    <p><strong>🎓 Curso:</strong> $curso</p>
                    <div class='mt-3'>
                        <a href='../verificar.php?codigo=$codigo' target='_blank' class='btn btn-success me-2'>
                            👁️ Ver Certificado
                        </a>
                        <a href='listar_certificados.php' class='btn btn-primary'>
                            📋 Ver Todos os Certificados
                        </a>
                    </div>
                </div>";
                
                // Limpar formulário
                $_POST = array();
            } else {
                $erro = "❌ Erro ao salvar no banco de dados";
            }
            
        } catch (Exception $e) {
            $erro = "❌ Erro: " . $e->getMessage();
        }
    } else {
        $erro = "❌ " . implode("<br>❌ ", $erros_validacao);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerar Certificado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🎓 Admin - Certificados</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">📊 Dashboard</a>
                <a class="nav-link" href="listar_certificados.php">📋 Listar</a>
                <a class="nav-link" href="logout.php">🚪 Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">📄 Gerar Novo Certificado</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $sucesso; ?>
                        
                        <?php if ($erro): ?>
                            <div class="alert alert-danger"><?php echo $erro; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">👤 Nome do Participante *</label>
                                        <input type="text" name="nome_participante" class="form-control" 
                                               value="<?php echo $_POST['nome_participante'] ?? ''; ?>" 
                                               required minlength="3">
                                        <div class="form-text">Mínimo 3 caracteres</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">📧 E-mail *</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="<?php echo $_POST['email'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">🎓 Curso/Evento *</label>
                                <input type="text" name="curso_evento" class="form-control" 
                                       value="<?php echo $_POST['curso_evento'] ?? ''; ?>" 
                                       required minlength="5">
                                <div class="form-text">Ex: Curso de PHP Avançado, Workshop de Marketing Digital</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">⏰ Carga Horária (horas) *</label>
                                <input type="number" name="carga_horaria" class="form-control" 
                                       value="<?php echo $_POST['carga_horaria'] ?? '40'; ?>" 
                                       min="1" max="1000" required>
                                <div class="form-text">Carga horária total em horas</div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="dashboard.php" class="btn btn-secondary me-md-2">← Voltar</a>
                                <button type="submit" class="btn btn-primary btn-lg">🎓 Gerar Certificado</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>