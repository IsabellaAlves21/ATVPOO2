<?php
 
require_once 'Contato.php';
require_once 'GerenciadorDeContatos.php';
 
session_start();
 
// Inicializar o gerenciador de contatos
if (!isset($_SESSION['gerenciadorDeContatos'])) {
    $_SESSION['gerenciadorDeContatos'] = serialize(new GerenciadorDeContatos());
}
 
$gerenciadorDeContatos = unserialize($_SESSION['gerenciadorDeContatos']);
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adicionar contato
    if (isset($_POST['nome'], $_POST['email'], $_POST['telefone'])) {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);
 
        try {
            $gerenciadorDeContatos->adicionarContato($nome, $email, $telefone);
            $mensagem = "Contato adicionado com sucesso.";
        } catch (InvalidArgumentException $e) {
            $erro = $e->getMessage();
        }
    }
 
    // Atualizar contato
    if (isset($_POST['atualizar'], $_POST['indice_atualizar'], $_POST['nome_atualizar'], $_POST['email_atualizar'], $_POST['telefone_atualizar'])) {
        $indice = (int)$_POST['indice_atualizar'];
        $nome = trim($_POST['nome_atualizar']);
        $email = trim($_POST['email_atualizar']);
        $telefone = trim($_POST['telefone_atualizar']);
 
        if ($gerenciadorDeContatos->atualizarContato($indice, $nome, $email, $telefone)) {
            $mensagem = "Contato atualizado com sucesso.";
        } else {
            $mensagem = "Erro: Contato não encontrado.";
        }
    }
 
    // Deletar contato
    if (isset($_POST['deletar']) && is_numeric($_POST['deletar'])) {
        $indice = (int)$_POST['deletar'];
        if ($gerenciadorDeContatos->deletarContato($indice)) {
            $mensagem = "Contato excluído com sucesso.";
        } else {
            $mensagem = "Erro: Contato não encontrado.";
        }
    }
 
    // Buscar contatos
    if (isset($_POST['buscar'])) {
        $nome = trim($_POST['nome_buscar']);
        $contatosEncontrados = $gerenciadorDeContatos->buscarContatos($nome);
    }
 
    // Atualizar sessão
    $_SESSION['gerenciadorDeContatos'] = serialize($gerenciadorDeContatos);
}
 
$contatos = $gerenciadorDeContatos->getContatos();
$totalContatos = $gerenciadorDeContatos->contarContatos();
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gerenciador de Contatos</title>
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Contatos</h1>
 
        <!-- Formulário para adicionar contato -->
        <form method="POST" action="">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="telefone" placeholder="Telefone" required><br>
            <button type="submit">Adicionar Contato</button>
        </form>
 
        <!-- Formulário para atualizar contato -->
        <form method="POST" action="">
            <h2>Atualizar Contato</h2>
            <input type="number" name="indice_atualizar" placeholder="Índice do Contato" required>
            <input type="text" name="nome_atualizar" placeholder="Nome" required>
            <input type="email" name="email_atualizar" placeholder="Email" required>
            <input type="tel" name="telefone_atualizar" placeholder="Telefone" required>
            <button type="submit" name="atualizar">Atualizar Contato</button>
        </form>
 
        <!-- Formulário para buscar contatos -->
        <form method="POST" action="">
            <h2>Buscar Contatos</h2>
            <input type="text" name="nome_buscar" placeholder="Nome" required>
            <button type="submit" name="buscar">Buscar</button>
        </form>
 
        <!-- Lista de contatos -->
        <h2>Contatos</h2>
        <ul>
            <?php foreach ($contatos as $indice => $contato): ?>
                <li>
                    <strong>Nome:</strong> <?= htmlspecialchars($contato->getNome()) ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($contato->getEmail()) ?> <br>
                    <strong>Telefone:</strong> <?= htmlspecialchars($contato->getTelefone()) ?>
                    <form method="POST" action="" style="display:inline;">
                        <button type="submit" name="deletar" value="<?= $indice ?>">Excluir</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
 
        <!-- Resultados de busca -->
        <?php if (isset($contatosEncontrados)): ?>
            <h2>Contatos Encontrados</h2>
            <ul>
                <?php foreach ($contatosEncontrados as $contato): ?>
                    <li>
                        <strong>Nome:</strong> <?= htmlspecialchars($contato->getNome()) ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($contato->getEmail()) ?> <br>
                        <strong>Telefone:</strong> <?= htmlspecialchars($contato->getTelefone()) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
 
        <!-- Mensagens de feedback -->
        <?php if (isset($mensagem)): ?>
            <p><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>
 
        <p>Total de Contatos: <?= $totalContatos ?></p>
    </div>
</body>
</html>