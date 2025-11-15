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

// Buscar certificados
$query = "SELECT * FROM certificados ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$certificados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Certificados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🎓 Admin - Certificados</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">📊 Dashboard</a>
                <a class="nav-link" href="gerar_certificado.php">📄 Novo</a>
                <a class="nav-link" href="logout.php">🚪 Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📋 Todos os Certificados</h2>
            <span class="badge bg-primary">Total: <?php echo count($certificados); ?></span>
        </div>

        <?php if (empty($certificados)): ?>
            <div class="alert alert-info text-center">
                <h5>📭 Nenhum certificado encontrado</h5>
                <p>Você ainda não gerou nenhum certificado.</p>
                <a href="gerar_certificado.php" class="btn btn-primary">🎓 Gerar Primeiro Certificado</a>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Participante</th>
                                    <th>Curso/Evento</th>
                                    <th>Carga Horária</th>
                                    <th>Data</th>
                                    <th>Código</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($certificados as $cert): ?>
                                <tr>
                                    <td><?php echo $cert['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($cert['nome_participante']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($cert['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($cert['curso_evento']); ?></td>
                                    <td>
                                        <span class="badge bg-info"><?php echo $cert['carga_horaria']; ?>h</span>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($cert['data_emissao'])); ?><br>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($cert['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <code style="font-size: 0.8em;"><?php echo $cert['codigo_verificacao']; ?></code>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="../verificar.php?codigo=<?php echo $cert['codigo_verificacao']; ?>" 
                                               target="_blank" class="btn btn-outline-primary" title="Ver">
                                                👁️
                                            </a>
                                            <?php if ($cert['arquivo_pdf']): ?>
                                            <a href="<?php echo $cert['arquivo_pdf']; ?>" 
                                               target="_blank" class="btn btn-outline-success" title="Abrir Certificado">
                                                📄
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>