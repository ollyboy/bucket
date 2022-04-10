<?php
    // Define a date beyond 2038
    $dateString = '2039-01-01';
    $format = 'l d F Y H:i';
    
    // Parse a textual date/datetime into a Unix timestamp
    $date = strtotime($dateString);
    
    // Print it
    echo date($format, $date) ."\n";
?>
