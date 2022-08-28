<?php 
class Payment_Pointer{
  public $pointer;
  public $probability;

  function __construct($pointer, $probability) {
    $this->pointer = $pointer;
    $this->probability = $probability;
  }
}
?>