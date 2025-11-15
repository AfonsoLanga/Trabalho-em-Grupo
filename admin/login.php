<?php
session_start();

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['admin_logado'])) {
    header('Location: dashboard.php');
    exit();
}

$usuario_valido = 'admin';
$senha_valida = '123456';

if ($_POST) {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if ($usuario === $usuario_valido && $senha === $senha_valida) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_usuario'] = $usuario;
        header('Location: dashboard.php');
        exit();
    } else {
        $erro = 'Usuário ou senha inválidos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">🔐 Área Administrativa</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo $erro; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Usuário:</label>
                                <input type="text" name="usuario" class="form-control" value="admin" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Senha:</label>
                                <input type="password" name="senha" class="form-control" value="123456" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">🚀 Entrar no Sistema</button>
                        </form>
                        
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6>📋 Credenciais de Teste:</h6>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Usuário:</strong><br>
                                    <code>admin</code>
                                </div>
                                <div class="col-6">
                                    <strong>Senha:</strong><br>
                                    <code>123456</code>
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