<?php 
  require "data.php"; 
  require "function.php";

  if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $invoice = sanitize($_POST);
    $errors = validate($invoice);
    
    if (count($errors) === 0) {
      addInvoice($invoice);
    }
  } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="date" content="2024-05-13">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
     
    </style>
    <title>Lab 3</title>
</head>
<body class="container px-4 py-4">
  <h1>Invoice Manager</h1>
  <div class="d-flex justify-content-between">
    <p>Create a new invoice.</p>  
    <a class="text-decoration-none" href="index.php">< Back</a>
  </div>
  <div class="bg-body-tertiary p-5">
    <form class="form" method="post" enctype="multipart/form-data" novalidate>
      <div class="mb-3">
          <label for="client" class="form-label">Client Name</label>
          <input type="text" class="form-control" name="client" value="<?php echo $invoice['client'] ?? ""; ?>" placeholder="Client Name">
          <div class="error text-danger ps-2"><?php echo $errors['client'] ?? ''; ?></div>
      </div>
      <div class="mb-3">
          <label for="email" class="form-label">Client Email</label>
          <input type="email" class="form-control" name="email" value="<?php echo $invoice['email'] ?? ""; ?>" placeholder="Client Email">
          <div class="error text-danger ps-2"><?php echo $errors['email'] ?? ''; ?></div>
      </div>
      <div class="mb-3">
          <label class="form-check-label" for="amount">Invoice Amount</label>
          <input type="number" class="form-control" name="amount" value="<?php echo $invoice['amount'] ?? ""; ?>" placeholder="Invoice Amount">
          <div class="error text-danger ps-2"><?php echo $errors['amount'] ?? ''; ?></div>
      </div>
      <div class="mb-3">
          <label class="form-check-label" for="status">Invoice Status</label>
          <select class="form-select" name="status">
            <option value="">Select a Status</option>
            <?php foreach($statuses as $status): ?>
              <?php if ($status != "all"): ?>                
                <option value="<?php echo $status ?>" <?php if (isset($invoice['status']) && $status === $invoice['status']) : ?> selected <?php endif; ?>><?php echo ucfirst($status) ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
          <div class="error text-danger ps-2"><?php echo $errors['status'] ?? ''; ?></div>
      </div>
      <div class="mb-3">
        <label class="form-check-label" for="amount">Invoice File</label>
        <input type="file" class="form-control" name="pdf" accept=".pdf">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>