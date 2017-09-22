<h2>Insira os dados na tabela alunos</h2><br>

<form id="form">

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
<script type="text/javascript">

var form = document.querySelector('#form');

form.addEventListener('submit', enviar);
function enviar(){
   var xmlhttp = new XMLHttpRequest();   // new HttpRequest instance
   var data = new FormData(form);
   xmlhttp.open("POST", "Ponte.php");
   xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
   xmlhttp.send(JSON.stringify(data));
}
</script>
