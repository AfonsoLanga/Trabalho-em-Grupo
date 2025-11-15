<?php
// criar_banco.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🗄️ CRIANDO BANCO DE DADOS AUTOMATICAMENTE</h1>";

try {
    // Conectar sem especificar o banco
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Conectado ao MySQL</p>";
    
    // Criar banco de dados
    $pdo->exec("CREATE DATABASE IF NOT EXISTS sistema_certificados");
    echo "<p style='color: green;'>✅ Banco 'sistema_certificados' criado</p>";
    
    // Usar o banco
    $pdo->exec("USE sistema_certificados");
    
    // Criar tabela
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS certificados (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome_participante VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            curso_evento VARCHAR(255) NOT NULL,
            data_emissao DATE NOT NULL,
            carga_horaria INT NOT NULL,
            codigo_verificacao VARCHAR(100) UNIQUE NOT NULL,
            arquivo_pdf VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "<p style='color: green;'>✅ Tabela 'certificados' criada</p>";
    
    // Testar inserção
    $test_code = 'TEST_' . uniqid();
    $stmt = $pdo->prepare("INSERT INTO certificados 
        (nome_participante, email, curso_evento, data_emissao, carga_horaria, codigo_verificacao) 
        VALUES (?, ?, ?, CURDATE(), ?, ?)");
    
    $stmt->execute([
        'João Silva Teste', 
        'teste@email.com', 
        'Curso de Teste', 
        40, 
        $test_code
    ]);
    
    echo "<p style='color: green;'>✅ Dados de teste inseridos</p>";
    echo "<p><strong>Código de teste:</strong> $test_code</p>";
    
    echo "<h2 style='color: green;'>🎉 BANCO CRIADO COM SUCESSO!</h2>";
    echo "<p><a href='admin/login.php' class='btn btn-success'>Ir para Área Administrativa</a></p>";
    echo "<p><a href='index.php' class='btn btn-primary'>Ir para Página Principal</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>❌ ERRO:</strong> " . $e->getMessage() . "</p>";
    
    if ($e->getCode() == 1045) {
        echo "<p>Problema de acesso ao MySQL. Verifique:</p>";
        echo "<ul>";
        echo "<li>Usuário: root</li>";
        echo "<li>Senha: (vazia)</li>";
        echo "<li>MySQL está rodando no XAMPP?</li>";
        echo "</ul>";
    }
}
?>