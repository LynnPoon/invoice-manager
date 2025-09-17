<?php

require "data.php";

function getInvoice(){
  global $db;
  $sql = "SELECT * FROM invoices JOIN statuses ON invoices.status_id = statuses.id";
  $result = $db->query($sql);
  $invoices = $result->fetchAll(PDO::FETCH_ASSOC);

  return $invoices;
}


function filterInvoices($status) {
  global $db;
  $sql = "SELECT * FROM invoices JOIN statuses ON invoices.status_id = statuses.id WHERE statuses.status = :status";
  $statement = $db->prepare($sql);
  $statement->execute([':status'=> $status]);
  
  $invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
  
  return $invoices;    
}


function countInvoices($invoices) {
  return count($invoices);
}


function getInvoiceNumber ($length = 5) {
  $letters = range('A', 'Z');
  $number = [];
  
  for ($i = 0; $i < $length; $i++) {
    array_push($number, $letters[rand(0, count($letters) - 1)]);
  }
  
  return implode($number);
}


function getCurrentInvoice($number) {
  global $db;
  $sql = "SELECT * FROM invoices JOIN statuses ON invoices.status_id = statuses.id WHERE invoices.number = :number";
  $statement = $db->prepare($sql);
  $statement->execute([':number' => $number]);
  $invoice = $statement->fetch(PDO::FETCH_ASSOC);

  return $invoice;
}


//process uploaded pdf
function savePDF($number){
  //get file data and save to a variable
  $pdf = $_FILES['pdf'];

  //check for errors
  if($pdf['error'] === UPLOAD_ERR_OK){
    
    $filename = $number.".pdf";

    //check if pdf folder exist, if not create it
    if(!file_exists('documents')){
      mkdir('documents');
    }

    $dest = 'documents/'.$filename;
    
    //if the file exists, delete it
    if(file_exists($dest)){
      unlink($dest);
    }
  }

    //move the pdf from temp place to the dest folder
    return move_uploaded_file($pdf['tmp_name'], $dest);
}


function addInvoice($invoice){
  global $db;

  $number = getInvoiceNumber();

  $status = $invoice['status'];
  $sql = "SELECT id FROM statuses WHERE status = :status";
  $stmt_statusId = $db->prepare($sql);
  $stmt_statusId->execute(['status' => $status]);
  
  $status_id = $stmt_statusId->fetchColumn(0);

  $sql = "INSERT INTO invoices (number, client, email, amount, status_id) VALUES
  (:number, :client, :email, :amount, :status_id)";
  $statement = $db->prepare($sql);
  $statement->execute([    
    "number" => $number,
    "client" => $invoice["client"],
    "email" => $invoice["email"],
    "amount" => $invoice["amount"],
    "status_id" => $status_id
]);

  header("Location: index.php");

  savePDF($number);
  return $number;
}


function updateInvoice($invoice)
{
  global $db;
  
  $status = $invoice['status'];
  $sql = "SELECT id FROM statuses WHERE status = :status";
  $stmt_statusId = $db->prepare($sql);
  $stmt_statusId->execute(['status' => $status]);

  $status_id = $stmt_statusId->fetchColumn(0); //get the status id from the statuses table

  $sql = "UPDATE invoices SET client = :client, email = :email, amount = :amount, status_id = :status_id WHERE number = :number";
  $stmt = $db->prepare($sql);
  $stmt->execute([
      'number' => $invoice['number'],
      'client' => $invoice['client'],
      'email' => $invoice['email'],
      'amount' => $invoice['amount'],
      'status_id' => $status_id,
  ]);

  header("Location: index.php");

  savePDF($invoice['number']);
  return $invoice;  
}


function deleteInvoice ($invoiceNum){
  global $db;

  $sql = "DELETE FROM invoices WHERE number = :invoiceNum";
    $stmt = $db->prepare($sql);
    $stmt->execute(['invoiceNum' => $invoiceNum]);

  return $stmt->rowCount();
}


//$data is an array to be sanitized
function sanitize($data){
  return array_map(function($value){
    return htmlspecialchars(stripslashes(trim($value)));
  }, $data);
}


function validate($invoice){
  
  $fields = ['client', 'email', 'status', 'amount'];
  $errors = [];
  
  global $statuses;

  foreach ($fields as $field){
    switch($field){
      case 'client':
        if(empty($invoice[$field])){
          $errors[$field] = 'Client name is required';
        } else if (strlen($invoice[$field]) > 255) {
          $errors[$field] = 'Client name cannot be more than 255 characters';
        } else if (!preg_match('/^[a-zA-Z\s]+$/', $invoice[$field])) {
          $errors[$field] = 'Client name must contain only letters and spaces';
        }
        break;
      
      case 'email':
        if(empty($invoice[$field])){
          $errors[$field] = 'Email is required';
        } else if (!filter_var($invoice[$field], FILTER_VALIDATE_EMAIL)) {
          $errors[$field] = 'Email must be a properly formatted email address';
        }
        break;
      
      case 'status':
        if(empty($invoice[$field])){
          $errors[$field] = 'Invoice status is required';
        } else if(!in_array($invoice[$field], $statuses)){
          $errors[$field] = 'Invoice status must be either "draft", "pending", or "paid"';
        }
        break;
            
      case 'amount':
        if (empty($invoice[$field])) {
          $errors[$field] = 'Invoice amount is required';
        } else if (filter_var($invoice[$field], FILTER_VALIDATE_INT) === false) {
          $errors[$field] = 'Invoice amount must be an integer';
        }
        break;
    }
  }

  return $errors;
}
?>