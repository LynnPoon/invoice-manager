<?php 
  require "data.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="date" content="2024-05-13">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .number{
        font-weight: bold;
      }

      .status {
        display: inline-block;
        padding: 0.2em 0.5em;
        border-radius: 0.6em;
        width: 7em;
        height: 2em; 
        text-align: center;             
        }

        .paid {
          background-color: #d5ffb1; /* Green */
          color: #155724;
        }

        .pending {
          background-color: #fff4d5; /* Yellow */     
          color: #856404;     
        }
        
        .draft {
          background-color: #ebebeb; /* Gray */
          color: #6c757d;
        }
    </style>
    <title>Invoice Manager</title>
</head>
<body class="container px-4 py-4">   
    <h1>Invoice Manager</h1>    
    <div class="d-flex justify-content-between">
      <p><?php echo $msg; ?></p>
      <a class="text-decoration-none" href="add.php">Add ></a>
    </div>
    
    <nav>
    <ul class="nav nav-tabs">
    <?php foreach ($statuses as $status): ?>
      <li class="nav-item">
          <a class="nav-link <?php echo ($status === $activePage) ? 'active' : ''; ?>" aria-current="page" href="<?php echo ($status === 'all') ? 'index.php' : 'index.php?status='.$status; ?>"><?php echo ucfirst($status); ?></a>
      </li>
    <?php endforeach; ?>
    </ul>  
    </nav>

    <table class="table">
      <tbody>
        <?php foreach ($filteredInvoices as $invoice): ?>
          <?php 
              $statusClass = '';
              switch ($invoice['status']) {
                case 'draft':
                case 'paid':
                case 'pending':
                  $statusClass = $invoice['status'];
                  break;
              }
            ?>
          <tr>
            <td class="number">#<?php echo $invoice['number'] ?></td>
            <td style="color: #1F78FD;"><?php echo $invoice['client'] ?></td>
            <td><?php echo '$&nbsp;' . $invoice['amount'] ?></td>        
            <td><span class="badge <?php echo $statusClass; ?>"><?php echo $invoice['status'] ?></span></td>          
            <td>   
              <div class="d-flex justify-content-end align-items-center">
                <?php 
                  $file = $invoice['number'].".pdf";
                  $dest = 'documents/'.$file;
                if(file_exists($dest)): ?>
                
                  <a class="btn btn-outline-primary text-decoration-none me-2 btn-sm" href="<?php echo $dest; ?>" target="_blank">View</a>
              
                <?php endif; ?>

                <a class="btn btn-outline-primary text-decoration-none me-2 btn-sm" href="update.php?number=<?php echo $invoice['number'] ?>">Edit
                </a>
                <form method='post' action='delete.php' class="d-inline">
                  <input type='hidden' name='number' value='<?php echo $invoice['number']; ?>'> 
                  <button type='submit'class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
              </div>              
            </td>   
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>   
</body>