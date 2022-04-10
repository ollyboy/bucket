<?php
//
// command line test for split fuction
//
include 'split.php';

if ( !isset ( $argv[1]) || !$startDate=strtotime ( str_replace('/', '-', $argv[1] ) ) ) {
        echo "ERROR: Must pass start date arg1\n";
        exit (0);
}
if ( !isset ( $argv[2]) || !$endDate=strtotime ( str_replace('/', '-', $argv[2] ) ) ) {
        echo "ERROR: Must pass end date arg2\n";
        exit (0);
}
if ( $startDate >= $endDate ) {
        echo "ERROR: There is no positive range between dates\n";
        exit (0);
}
if ( !isset ( $argv[3]) || !$values  = array_map('trim', explode(',', $argv[3] ) ) ) {
        echo "ERROR: Must pass a value or csv list of values to apportion\n";
        exit (0);
}
$endOfWeek="sunday"; // default end of week
if ( isset ( $argv[4]) ) {
        $d= strtolower( trim ( $argv[4] ) );
        if ( $d == "sunday" || $d == "friday" || $d == "saturday" ) {
                $endOfWeek = $d;
        } else {
                echo "WARN: Bad week end day, using sunday\n";
        }
}
$debug="none";
if ( isset ( $argv[5]) ) {
        $d= strtolower( trim ( $argv[5] ) );
        if ( $d == "full" || $d == "light" ) {
                $debug = $d;
        } else {
                echo "WARN: Debug not full or light, using none\n";
        }
}

$res = bucketSplit ( $argv[1] , $argv[2] , $values, $endOfWeek , $debug );
print_r ( $res );
exit (1);
// end of code
