<?php 
  require "data.php";
  require "function.php";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    deleteInvoice ($_POST['number']);
    
  }

  header("Location: index.php");