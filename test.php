<?php
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

// Include Spout library 
require_once 'spout-2.4.3/src/Spout/Autoloader/autoload.php';

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
            global $wpdb;
            $tbl_name = $wpdb->prefix.'student';
            $kv_data = array(
              'name'     => $row[0],
            );
            print_r($kv_data['name']);
            die;
            $new = $wpdb->insert( $tbl_name, $kv_data );
          }
          $count++;
        }
      }
    }
    // Close excel file
    $reader->close();
  } else {
    $erroe_msg = '';
    $erroe_msg = "Please Select Valid Excel File";
  }
}
?>
<div id="excelsucess"><?php echo $msg;?></div>
<div class="upload_error"><?php echo $erroe_msg;?></div>
<form action="#" method="post" name="myForm" enctype="multipart/form-data" class="upload_excel"> 
  <input type="file" name="file" id="upload_file">
  <input type= "submit" value ="uploaded" class="submit excel_btn">
</form>
<script>
jQuery( '.submit' ).click(function(){
  if( jQuery( '#upload_file' ).val().length == 0 ) {
    jQuery( '#excelsucess' ).html( 'Please select file' );      
    return false;
  }
});
</script>