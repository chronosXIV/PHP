#!/usr/bin/php

<?php
/*
 * ECS518U January 2018
 * Lab Exercise Week 3
 *
 * Tester2.php - uses a timeout to check for
 *               termination, but does not test the output.
 *
 */

declare(ticks = 1); // to catch signals

if ($argc < 3){
    die("Two arguments expected: starting test value and last test value\n") ;
}

$testVal = $argv[1] ;
$lastVal = $argv[2] ;

// looping for every integer between the two command arguments
while($testVal <= $lastVal){

     $pid = pcntl_fork();

     if ($pid < 0){
          die("Could not fork\n");
     } else if ($pid){
          // parent
          pcntl_signal(SIGALRM, "sig_handler");
          pcntl_alarm(4);

          // wait for the child process to exit
          // the 'status' variable is updated with the status
          pcntl_waitpid($pid, $status);
          pcntl_alarm(0);

          // print ("status is: $status \n") ; // error checking

          checkResult($status,$testVal) ; // prints result based on status

     } else{
          // child
          runOneTest($testVal) ;
     }

     $testVal++ ;
}

// runs testProgram once with the current test value
function runOneTest($testVal){
     $path = rtrim(`pwd`) ; // executable, with path but no \n
     $args = array("$testVal") ; //   create an array of arguments
     pcntl_exec("{$path}/testProgram", $args);
}

function checkResult($statusVal,$testVal){
     if($statusVal == 1){
          echo "Test failed to terminate for test value=$testVal", "\n" ;
     } else{
          echo "Test terminated for test value=$testVal", "\n" ;
     }
}

//sets the $status to 1 show it's failed to terminate
function sig_handler($signum){
     global $status, $pid;
     posix_kill($pid,SIGKILL);
     $status = 1;
}

?>
