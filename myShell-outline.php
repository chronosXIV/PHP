#!/usr/bin/php
<?php
/*
 * Outline Simple Shell - ECS518U Lab 4
 *
 *    COMMANDS          ERRORS CHECKED
 *    1. info XX         - check file/dir exists
 *    2. files
 *    3. delete  XX      - check file exists and delete succeeds
 *    4. copy XX YY      - XX exists, YY does not exist; copy succeeds
 *    5. where
 *    6. down DD         - check directory exists and change succeeds
 *    7. up              - check not at top
 */

date_default_timezone_set('Europe/London') ;

$prompt = "PShell>" ;
fwrite(STDOUT, "$prompt ") ; // prints PShell for the first time
while (1) {
    $line = trim(fgets(STDIN)); // reads one line from STDIN
    $fields = preg_split("/\s+/", $line) ;

    switch ($fields[0]) {
        case "files":
           filesCmd($fields);
           break ;
        case "info":
           infoCmd($fields);
           break ;
        case "copy":
           copyCmd($fields);
           break ;
        case "delete":
           deleteCmd($fields);
           break ;
        case "where":
           whereCmd($fields);
           break ;
        case "up":
           upCmd($fields);
           break ;
        case "down":
           downCmd($fields);
           break ;
        default:
	  echo("Unknown command $fields[0]\n") ;
    }


    fwrite(STDOUT, "$prompt "); // prints PShell for every loop
}

// ========================
//   files command
//      List file and directory names
//      No arguments
// ========================
function filesCmd($fields) {
  foreach (glob("*") as $filename) {
    echo "$filename", "\n" ;
  }
}

// ========================
//   info command
//      List file information
//      1 argument: file or directory name
// ========================
function infoCmd($fields) {
  if (count($fields) == 2){
  if(file_exists($fields[1])){
  echo "Type: ".filetype($fields[1])."\n" ;
  $owner = posix_getpwuid(fileowner($fields[1]));
  echo "Owner: ".$owner['name']."\n" ;
  echo "Date Last Accessed: ".date("F d Y H:i:s",fileatime($fields[1]))."\n" ;
    if(is_file($fields[1])){
      echo "File Size: ".filesize($fields[1])."\n" ;
      if(is_executable($fields[1])){
        echo "Executable: Yes\n" ;
      }else{
        echo "Executable: No\n" ;
      }

    }
  }else{
      echo "File ".$fields[1]." doesn't exist."."\n" ;
  }
}else{
  if(count($fields) < 2)
    echo "Not enough arguments passed.\n";
  if (count($fields) > 2)
    echo "Too many arguments passed.\n";
}
}

// ========================
//   copy command
//      Copy the ‘from’ file to the ‘to’ file
//      2 arguments: the ‘from’ file and the ‘to’ file
// ========================
function copyCmd($fields) {
  if (count($fields) == 3 && is_file($fields[1])){
  if(file_exists($fields[1]) && !file_exists($fields[2])){
    copy($fields[1],$fields[2]);
    echo "File copied.\n" ;
  } else{
    echo "Failed to copy ".$fields[1].".\n";
  }
}else if(is_dir($fields[1])){
    echo "Shell cannot copy a directory.\n" ;
}
  else{
  if(count($fields) < 3)
    echo "Not enough arguments passed.\n";
  if(count($fields) > 3)
    echo "Too many arguments passed.\n";
}
}

// ========================
//   delete command
//      Delete the file
//      1 argument: file name
// ========================
function deleteCmd($fields) {
  if (count($fields) == 2){
  if(file_exists($fields[1]) && is_file($fields[1])){
    unlink($fields[1]);
    echo "File deleted."."\n" ;
  }else if(file_exists($fields[1]) && is_dir($fields[1])){
    rmdir($fields[1]);
    echo "Folder deleted."."\n" ;
  }else{
    echo "File cannot be deleted as it doesn't exist."."\n" ;
  }
}else{
  if(count($fields) < 2)
    echo "Not enough arguments passed.\n";
  if (count($fields) > 2)
    echo "Too many arguments passed.\n";
}
}

// ========================
//   where command
//      Show the current directory
//      No arguments
// ========================
function whereCmd($fields) {
  echo "Current directory: ".getcwd()."\n";
}

// ========================
//   up command
//      Change to the parent of the current directory
//      no arguments
// ========================
function upCmd($fields) {

  if(getcwd() != "/"){

    echo "Current directory: ".getcwd()."\n";

    $upDirectory = dirname(getcwd());
    chdir($upDirectory);

    echo "New directory: ".getcwd()."\n";
  } else{
    echo "Already at the root directory.\n";
  }
}

// ========================
//   down command
//      Change to the specified directory, inside the current directory
//      1 arguments: directory name
// ========================
function downCmd($fields) {

if (count($fields) == 2){
  if(file_exists($fields[1])){

    echo "Current directory: ".getcwd()."\n";
    chdir($fields[1]);
    echo "New directory: ".getcwd()."\n";

  } else {
    echo "Directory doesn't exist.\n" ;
  }
}else{
  if(count($fields) < 2)
    echo "Not enough arguments passed.\n";
  if (count($fields) > 2)
    echo "Too many arguments passed.\n";
}

}

?>
