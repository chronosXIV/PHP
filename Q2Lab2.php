<?php

// the glob function returns an array of file names matching the pattern
//    in the current directory

// foreach is a concise form of loop for arrays
// this could be written using count and while, as method 1

$largeNumber = false;

foreach (glob("*") as $filename) {

    echo "File name ".$filename." has size ".filesize($filename)."\n" ;
    // the filesize function returns the size in bytes

    if (filesize($filename) > 10000){
        $largeNumber = true;
    }
}

if (!$largeNumber){
      echo "\nNo large files found.\n";
}   else{
      echo "\nLarge files found.\n";
}

?>
