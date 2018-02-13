<?php

$maxsize = 10000;

foreach($argv as $value)
{
  if($argv[2]){
    exit("Too many arguments.\n");
  }
  if($argv[1] && filter_var($argv[1], FILTER_VALIDATE_INT) === false){
    exit("Your variable is not an integer.\n");
  }
  if($argv[1] != null){
    $maxsize = $argv[1];
  }
}

foreach (glob("*") as $filename) {

    echo "File name ".$filename." has size ".filesize($filename)."\n" ;
    // the filesize function returns the size in bytes

    if (filesize($filename) > $maxsize){
        $largeNumber = true;
    }
}

if (!$largeNumber){
      echo "\nNo large files found.\n";
}   else{
      echo "\nLarge files found.\n";
}

?>
