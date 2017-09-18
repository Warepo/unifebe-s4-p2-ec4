<?php
function __autoload($class){
  $class = __DIR__.DIRECTORY_SEPARATOR.str_replace('\\',DIRECTORY_SEPARATOR, $class). '.php';

if(include( $class )){
  return true;
}else{
  return false;
}

}
