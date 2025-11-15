<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Certificados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">🎓 Sistema de Certificados</h3>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-center mb-4">🔍 Verificar Certificado</h5>
                        <form action="verificar.php" method="GET">
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código de Verificação:</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" 
                                       placeholder="Digite o código do certificado" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                ✅ Verificar Certificado
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <a href="admin/login.php" class="btn btn-outline-secondary">
                                🔧 Área Administrativa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>