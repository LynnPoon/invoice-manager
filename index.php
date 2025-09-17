<?php

require "data.php";
require "function.php";

$activePage = isset($_GET['status']) ? $_GET['status'] : 'all';

$AllInvoices = getInvoice(); 

// Filter invoices based on status
if ($activePage !== 'all') {
  $filteredInvoices= filterInvoices($activePage);  
} else {
  $filteredInvoices = $AllInvoices;
}

$count = countInvoices($filteredInvoices);
$msg = "There are $count invoices.";

include 'template.php';