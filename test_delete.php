<?php
// test_delete.php - Script de Diagnóstico Rápido
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dir = __DIR__ . '/assets/imagem/portfolio/';

if (isset($_POST['delete'])) {
    $file = basename($_POST['delete']);
    $path = $dir . $file;
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "<h3 style='color:green;'>SUCESSO: O arquivo $file foi deletado!</h3>";
        } else {
            $err = error_get_last();
            echo "<h3 style='color:red;'>FALHA: O PHP tentou mas não conseguiu deletar $file. Erro: " . ($err ? $err['message'] : 'Desconhecido') . "</h3>";
        }
    } else {
        echo "<h3 style='color:orange;'>ERRO: O arquivo $file não existe no caminho $path</h3>";
    }
}

$files = glob($dir . '*.*');
?>
<h2>Teste de Exclusão Direta (Backend)</h2>
<p>Este script testa a capacidade do PHP de deletar os arquivos sem nenhum JavaScript ou CSS interferindo.</p>
<table border="1" cellpadding="10">
    <tr><th>Imagem</th><th>Nome</th><th>Ação</th></tr>
    <?php foreach ($files as $f): $base = basename($f); ?>
    <tr>
        <td><img src="assets/imagem/portfolio/<?= $base ?>" width="100"></td>
        <td><?= $base ?></td>
        <td>
            <form method="POST" style="margin:0;">
                <input type="hidden" name="delete" value="<?= $base ?>">
                <button type="submit" style="padding: 10px; background: red; color: white; cursor: pointer;">DELETAR TESTE</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
