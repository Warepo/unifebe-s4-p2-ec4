<h2>Insira os dados na tabela alunos</h2><br>
<form action="Ponte.php" method="post">

<?php
foreach($formulario as $coluna)
{
  ?>
  <input type="text" placeholder="<?=$coluna['Field']?>" name="<?=$coluna['Field']?>">
  <?php
}
?>
<input type="hidden" name="tabela" value="alunos">
<input type="submit" value="Enviar">
</form>
