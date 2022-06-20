<?php
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

// Include Spout library 
require_once 'spout-2.4.3/src/Spout/Autoloader/autoload.php';

$user_name = "rahul";
$password  = "Xeam@123";
$database  = "ats";
$server    = "localhost";

$db_handle = mysqli_connect($server, $user_name, $password);
$db_found = mysqli_select_db($db_handle,$database);

$project = mysqli_query($db_handle,"SELECT * FROM `project` ");

  while ($datarow = mysqli_fetch_assoc($project)) {
    $projectName =  $datarow['name'];
    // print_r($projectName);
  }
  
// this is working to import excelsheet data
if ( !empty( $_FILES['file']['name'] ) ) {
  // Get File extension eg. 'xlsx' to check file is excel sheet
  $pathinfo = pathinfo( $_FILES['file']['name'] );

  // check file has extension xlsx, xls and also check 
  // file is not empty
  if ( ( $pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls' ) && $_FILES['file']['size'] > 0 ) {
     
    // Temporary file name
    $inputFileName = $_FILES['file']['tmp_name']; 
    
    // Read excel file by using ReadFactory object.
    $reader = ReaderFactory::create(Type::XLSX);
    
    // Open file
    $reader->open( $inputFileName );
    $count = 1;
    // Number of sheet in excel file
    foreach ( $reader->getSheetIterator() as $sheet ) {
      
      // grab sheet name from existing file
      $existing_file_sheet_name = $sheet->getName();
      if( $existing_file_sheet_name ){
        // Number of Rows in Excel sheet
        foreach ( $sheet->getRowIterator() as $row ) {
          
          // It reads data after header. In the my excel sheet,
          // header is in the first row.
        
          if ( $count > 1 ) {
            //Here, You can insert data into database.
            $kv_data = array(
              'name'     => $row[0],
            );
            print_r($row);
          }
          $count++;
        }
      }
    }
    // Close excel file
    $reader->close();
  } else {
    $erroe_msg = '';
    echo "Please Select Valid Excel File";
  }
}
?>