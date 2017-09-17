<?php
function __autoload($class){
  $class = dirname(__FILE__).DIRECTORY_SEPARATOR.str_replace('\\',DIRECTORY_SEPARATOR, $class). '.php';

  if (!file_exists($class)) {
    throw new Exception("Arquivo não encontrado '{".$class."}'");
  }

  require_once( $class );

}
