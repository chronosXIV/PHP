#!/usr/bin/php

<?php
/*
 * ECS518U January 2018
 * Lab Exercise Week 3
 *
 * Tester3.php - combine tester1 and tester2
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

           checkResultPCNTLEXEC($status,$testVal) ; // prints result based on status

      } else{
           // child
           runOneTestPCNTLEXEC($testVal) ;
      }

      $testVal++ ;
 }

// runs testProgram using pcntl_exe()
function runOneTestPCNTLEXEC($testVal){
      $path = rtrim(`pwd`) ; // executable, with path but no \n
      $args = array("$testVal") ; //   create an array of arguments
      pcntl_exec("{$path}/testProgram", $args);
}

/* checks results of pcntl_exec()
   if the status was set to 1 by the signal handler, meaning
   pcntl_exec() went over the time limit, prints out error message.
   else, runs exec() to get the result/return value
   before the while loop ends and a new pcntl_fork() call is made */
function checkResultPCNTLEXEC($statusVal,$testVal){
      if($statusVal == 1){
            echo "Test failed to terminate for test value=$testVal", "\n" ;
      } else{
            list ($return, $testResult) = runOneTestEXEC($testVal);
            checkResultEXEC($return, $testVal, $testResult) ;
      }
}

// runs testProgram using exe()
function runOneTestEXEC($testInput) {
      $result = 0;
      $cmd = "./testProgram" ;
      exec("$cmd $testInput", $ans, $retr) ; // added $ans and $retr

      foreach ($ans as $ansLine) {
            $result = $ansLine ;
      }

      return(array($retr, $result)) ;
}

// checks result of exec() function
function checkResultEXEC($retVal, $testInput, $testOutput){
      if ($retVal == 0){
            if ($testOutput == $testInput+1) {
                  echo "Test passed for test value=$testInput", "\n" ;
            } else{
                  echo "Test failed for test value=$testInput", "; result=$testOutput", "\n" ;
            }
      } else {
            echo "Test failed for test value=$testInput", "; return value=$retVal", "\n" ;
      }
}

//sets the $status to 1 to show child failed to terminate within time limit
function sig_handler($signum){
      global $status, $pid;
      posix_kill($pid,SIGKILL);
      $status = 1;
}

?>
