<?php
 
require_once 'Contato.php';
 
class GerenciadorDeContatos {
    private $contatos = [];
 
    public function adicionarContato(string $nome, string $email, string $telefone): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("E-mail inválido.");
        }
       
        $contato = new Contato($nome, $email, $telefone);
        $this->contatos[] = $contato;
    }
 
    public function getContatos(): array {
        return $this->contatos;
    }
 
    public function deletarContato(int $indice): bool {
        if (isset($this->contatos[$indice])) {
            array_splice($this->contatos, $indice, 1);
            return true;
        }
        return false;
    }
 
    public function atualizarContato(int $indice, string $nome, string $email, string $telefone): bool {
        if (isset($this->contatos[$indice])) {
            $contato = new Contato($nome, $email, $telefone);
            $this->contatos[$indice] = $contato;
            return true;
        }
        return false;
    }
 
    public function buscarContatos(string $nome): array {
        $resultados = [];
        foreach ($this->contatos as $contato) {
            if (stripos($contato->getNome(), $nome) !== false) {
                $resultados[] = $contato;
            }
        }
        return $resultados;
    }
 
    public function contarContatos(): int {
        return count($this->contatos);
    }
}
?>
 