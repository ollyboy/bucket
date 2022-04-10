<?php
//
// take a date range and a value 
// work out the split of the value into weekly buckets
// Passs: Start-date, End-date, Values Array, End-of-Week-date
// Always use d-m-Y as / means m/d/Y
// returns array[end-of-week] with apportioned values
//

// calc days appart given datetime values
function daysgap( $s , $e ) {
	return ( round( ( $s - $e )  / (60 * 60 * 24) ) );
}

function bucketSplit ( $startEuroDate , $endEuroDate , $values, $endOfWeek , $debug ) {
	
$startDate=strtotime ( str_replace('/', '-', $startEuroDate ) ) ;
$endDate=strtotime ( str_replace('/', '-', $endEuroDate ) ) ;
$rangeDays = daysgap ( $endDate , $startDate );

// Debug info
if ( $debug != "none" ) echo "Start is a " . date('l', $startDate  ) . " End is a " . date('l', $endDate ) . " Using ". $endOfWeek . " as end of week\n";
if ( $debug != "none" ) echo "Range Days are " . $rangeDays . "\n";

// Find all the end of week dates and save
$i=0; $loop = true;
$date = new DateTime( $startEuroDate );
while ( $loop && $i < 100 ) {
   $date->modify('next ' . $endOfWeek );
   $next[$i] = $date->format('d-m-Y');
   $thisDate = strtotime($next[$i]);
   if ( $thisDate > $endDate ) {
		$loop=false;
   }
   if ( $debug == "full" ) echo  $next[$i] . ">" . strtotime($next[$i]) .">" . $endDate . "\n";
   if ( $i == 0 ) $startGap = daysgap ( $thisDate, $startDate );
   $i++;
}
$endGap = daysgap ( $thisDate , $endDate );
if ( $endGap == 7 ) {
   $endGap=0; // dont need this bucket
}

// Debug info
if ( $debug != "none" ) echo "StartGap " . $startGap . " EndGap " . $endGap . " Chunks " , ( $i ) . "\n";

// Work out the portions of the value for the buckets
$total = 0;
for ( $j=0 ; $j < $i ; $j++ ) {
   if ( $j==0 ) $allocate[$j] = $startGap;
   elseif ( $j == $i-1 ) $allocate[$j] = $endGap;
   else $allocate[$j] = 7;
   $total = $total + $allocate[$j]; 
}

// Do chunking of values
$output = array();
for ( $j=0 ; $j < $i ; $j++ ) {
   $k=0;
   foreach ( $values as $value ) {
        $valBit = $allocate[$j] / $total * $value;
	if ( $debug !== "none" ) {
	   echo "Bucket " . $next[$j] . " gets " . round ( $valBit , 2 ) . " from " . $value . "\n";
	}
	$output[ $next[$j]] [$k] = $valBit;
	$k++;
   }
}

// Debug Output
if ( $debug != "none" ) {
   //print_r( $next );
   //print_r( $allocate );
   echo "Total allocate is " . $total . "\n";
}
return ( $output );
}

?>
