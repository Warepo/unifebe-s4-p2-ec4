<?php
require (__DIR__.DIRECTORY_SEPARATOR.'autoload.php');
use classes\Banco;

$ambiente = new Banco('localhost','root','');
$ambiente->create_banco();
$ambiente->conectar();
$ambiente->criar_tabela();
$formulario = $ambiente->varre_tabela('alunos');
?>
<h2>Insira os dados na tabela alunos</h2><br>
<form action="Ponte.php\inserir" method="post">

<?php
foreach($formulario as $objeto)
{
  ?>
  <input type="text" placeholder="<?=$objeto['Field']?>" name="<?=$objeto['Field']?>">
  <?php
}
?>
<input type="submit" value="Enviar">
</form>
