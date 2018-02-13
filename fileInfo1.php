#!/bin/php
<?php
/*
 * ECS518U Lab 5
 *
 * fileInfo version 1
 *
 * This script prints information about the files in a directory.
 *
 * Update the file_info function and the headers, width arrays to
 * print more file information.
 *
 * The script uses the printf function for formated output (which is
 * modelled on the C function). You can read about printf if you wish
 * to change the layout, but this is not necessary.
 */


// The arrays header and width control the width of columns and the header.
// The arrays should be the same length. Extra columns can be added to the
// arrays.
$headers = array("File Name", "Size", "Type", "Modified") ;	// column headers
$width = array(25, 10, 10, 22) ;                 		// max width of data in each column

// The info array contains the information about the file
// This array is updated in the file_info function
$info ; // array of file information fields

// The glob function returns an array of file names matching the pattern
// in the current directory. Here the pattern is to match everything.
print_header() ;
foreach (glob("*") as $filename) {
  file_info($filename) ;
  print_file_info() ;
}
exit(0) ;

/*
 * Get information about one file
 *
 * This function updates the $info array
 *
 */
function file_info($name) {
  global $info ;                // this ensures we are referring to the $info declared on line 27
    $info[0] = $name ;          // the file name
    $info[1] = filesize($name) ;
    $info[2] = filetype($name) ;
    $info[3] = date("M j Y H:i",filemtime($name));
}


/* ------------------------------------------------
 *
 * There should be no need to modify this part of the script, provided
 * that the width and info arrays are updated correctly.
 *
 * Printing is controlled by
 *    1. the headers array, which has column headers (see print_header)
 *    2. the info array, which has fields for one file (see print_file_info)
 *    3. the width array, which has column widths (see both)
 * All three arrays should have the same length.
 */

/*
 * Print the file information.
 *
 * Print the info entries, using the corresponding width entries.
 */
function print_file_info() {
    global $width, $info ;
    $fldNum = 0 ;
    while ($fldNum < count($info)) {
        printf("%-{$width[$fldNum]}.{$width[$fldNum]}s", $info[$fldNum]) ;
        $fldNum++ ;
    }
    print "\n" ;
}

/*
 * Print a header.
 *
 * Print the header entries, using the corresponding width entries.
 */
function print_header() {
    global $width, $headers ;
    $fldNum = 0 ;
    while ($fldNum < count($headers)) {
        printf("%-{$width[$fldNum]}.{$width[$fldNum]}s", $headers[$fldNum] ) ;
        $fldNum++ ;
    }
    print "\n" ;
    // print a line
    $len = 0 ;
    foreach ($width as $w) { $len += $w ; }
    printf("%'-{$len}s\n", "") ;
}
?>
