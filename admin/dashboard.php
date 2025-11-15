<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../includes/database.php');
$database = new Database();
$db = $database->getConnection();

// Estatísticas
$query_total = "SELECT COUNT(*) as total FROM certificados";
$stmt_total = $db->prepare($query_total);
$stmt_total->execute();
$total_certificados = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];

$query_hoje = "SELECT COUNT(*) as hoje FROM certificados WHERE DATE(created_at) = CURDATE()";
$stmt_hoje = $db->prepare($query_hoje);
$stmt_hoje->execute();
$hoje_certificados = $stmt_hoje->fetch(PDO::FETCH_ASSOC)['hoje'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema Certificados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-stat {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                🎓 Admin - Sistema de Certificados
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    👋 Olá, <?php echo $_SESSION['admin_usuario']; ?>
                </span>
                <a class="nav-link" href="gerar_certificado.php">📄 Novo Certificado</a>
                <a class="nav-link" href="listar_certificados.php">📋 Listar Certificados</a>
                <a class="nav-link" href="logout.php">🚪 Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>📊 Dashboard</h2>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card card-stat bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total de Certificados</h5>
                        <h2 class="display-4"><?php echo $total_certificados; ?></h2>
                        <p class="card-text">Certificados emitidos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-stat bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Emitidos Hoje</h5>
                        <h2 class="display-4"><?php echo $hoje_certificados; ?></h2>
                        <p class="card-text">Certificados hoje</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-stat bg-info text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Ações Rápidas</h5>
                        <div class="d-grid gap-2">
                            <a href="gerar_certificado.php" class="btn btn-light">📄 Novo Certificado</a>
                            <a href="listar_certificados.php" class="btn btn-light">📋 Ver Todos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>🚀 Comece Agora</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3">
                                    <h3>1</h3>
                                    <p>Clique em "Novo Certificado" para gerar seu primeiro certificado</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <h3>2</h3>
                                    <p>Preencha os dados do participante e do curso</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <h3>3</h3>
                                    <p>Use o código gerado para verificar o certificado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>