<?php
include "config.php";

class Produto {
    private $produto;
    private $preco;
    private $quantidade;
    private $categoria;
    private $tags;
    private $publico_alvo;
    private $favorito;
    private $conn;
    private $descricao;
    private $publico;

    public function __construct($produto = "", $preco = "", $quantidade = "", $categoria = "") {
        global $conn;
        $this->conn = $conn;
        $this->produto = $produto;
        $this->preco = $preco;
        $this->quantidade = $quantidade;
        $this->categoria = $categoria;
    }

    public function setProduto($produto) { $this->produto = htmlspecialchars($produto); }
    public function setPreco($preco) { $this->preco = floatval($preco); }
    public function setQuantidade($quantidade) { $this->quantidade = intval($quantidade); }
    public function setCategoria($categoria) { $this->categoria = htmlspecialchars($categoria); }
    public function setTags($tags) { $this->tags = htmlspecialchars($tags); }
    public function setPublicoAlvo($publico) { $this->publico_alvo = htmlspecialchars($publico); }
    public function setFavorito($favorito) { $this->favorito = intval($favorito); }
    public function setDescricao($descricao) { $this->descricao = htmlspecialchars($descricao); }
    public function setPublico($publico) { $this->publico = htmlspecialchars($publico); }
    
    public function consultaProduto($id = null) {
        try {
            if ($id) {
                $sql = $this->conn->prepare("SELECT * FROM produto WHERE id = ?");
                $sql->bind_param("i", $id);
            } else {
                $sql = $this->conn->prepare("SELECT id, produto, preco, quantidade, categoria, caminhoImagem, tags, publico_alvo, favorito FROM produto");
            }

            $sql->execute();
            $result = $sql->get_result();
            $produtos = [];

            while ($linha = $result->fetch_assoc()) {
                $produtos[] = $linha;
            }

            $sql->close();
            return $produtos;
        } catch (mysqli_sql_exception $e) {
            echo "Erro ao consultar produtos: " . $e->getMessage();
            return [];
        }
    }

    public function atualizaProduto($id) {
        try {
            $sql = $this->conn->prepare("UPDATE produto SET produto = ?, preco = ?, quantidade = ?, categoria = ? WHERE id = ?");
            $sql->bind_param('sdisi', $this->produto, $this->preco, $this->quantidade, $this->categoria, $id);
            $sql->execute();
            echo "<script>alert('Cadastro atualizado com sucesso!');</script>";
        } catch (mysqli_sql_exception $e) {
            echo "Erro ao processar a atualização: " . $e->getMessage();
        }
        if ($sql) $sql->close();
    }

    public function apagaProduto($id) {
        try {
            $sql = $this->conn->prepare("DELETE FROM produto WHERE id = ?");
            $sql->bind_param('i', $id);
            $sql->execute();
            echo "<script>alert('Produto apagado com sucesso!');</script>";
        } catch (mysqli_sql_exception $e) {
            echo "Erro ao apagar produto: " . $e->getMessage();
        }
        if ($sql) $sql->close();
    }

    public function insereProduto() {
    try {
        $sql = $this->conn->prepare("INSERT INTO produto (produto, preco, quantidade, categoria, descricao, tags, publico) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param('sdissss', $this->produto, $this->preco, $this->quantidade, $this->categoria, $this->descricao, $this->tags, $this->publico);
$sql->execute();
        echo "<script>alert('Cadastro realizado com sucesso!');</script>";
    } catch (mysqli_sql_exception $e) {
        echo "Erro ao processar o cadastro: " . $e->getMessage();
    }
    if ($sql) $sql->close();
}

    public function cadastraImagem($id, $produto, $foto) {
        if (isset($foto) && $foto['error'] === UPLOAD_ERR_OK) {
            $nomeTemporario = $foto['tmp_name'];
            $nomeOriginal = basename($foto['name']);
            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

            $novoNome = "img_{$id}_" . preg_replace('/\s+/', '_', $produto) . ".{$extensao}";
            $diretorio = "imagens/";

            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0777, true);
            }

            $caminhoFinal = $diretorio . $novoNome;

            if (move_uploaded_file($nomeTemporario, $caminhoFinal)) {
                $sql = $this->conn->prepare("UPDATE produto SET caminhoImagem = ? WHERE id = ?");
                $sql->bind_param('si', $caminhoFinal, $id);
                $sql->execute();
                echo "<script>alert('Arquivo enviado com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao mover o arquivo.');</script>";
            }
        } else {
            echo "<script>alert('Nenhum arquivo selecionado ou erro no upload.');</script>";
        }
        if (isset($sql)) $sql->close();
    }

    public function vendeProduto($id, $quantidade) {
        try {
            $sql = $this->conn->prepare("UPDATE produto SET quantidade = quantidade - ? WHERE id = ?");
            $sql->bind_param('ii', $quantidade, $id);
            $sql->execute();
        } catch (mysqli_sql_exception $e) {
            echo "Erro ao processar a venda: " . $e->getMessage();
        }
        if ($sql) $sql->close();
    }
}
?>
