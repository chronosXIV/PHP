<?php

$handle = popen('ls -l', "r") ; // open a pipe to the output of the ls command

fgets($handle); // clean the first summary line of the ls –l output

$largeNumber = false;
// read lines from the pipe
while ($line = fgets($handle)) {

    echo $line ; // print the line to the terminal

    // split each line into fields using spaces
    $fields = preg_split("/\s+/", $line) ; // fields is an array

    // print each field
    $i = 0 ;
    while ($i < count($fields)) {
        echo "  field $i is ", $fields[$i], "\n";

        if ($i == 4 && $fields[$i] > 10000){
            $largeNumber = true;
        }

        $i++ ;
    }
}

if (!$largeNumber){
      echo "\nNo large files found.\n";
}   else{
      echo "\nLarge files found.\n";
}

?>
