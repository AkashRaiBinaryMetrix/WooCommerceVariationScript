<?php
error_reporting(E_ALL);
require_once('wp-load.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if(isset($_POST['btnSubmit'])){
        //$spreadsheet = new Spreadsheet();
    
        //Allowed mime types 
        $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
         
        // Validate whether selected file is a Excel file 
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)){ 
             
            // If the file is uploaded 
            if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
                
                $reader = new Xlsx(); 
                $spreadsheet = $reader->load($_FILES['file']['tmp_name']); 
                $worksheet = $spreadsheet->getActiveSheet();  
                $worksheet_arr = $worksheet->toArray(); 
     
                // Remove header row 
                unset($worksheet_arr[0]); 
                
                foreach($worksheet_arr as $row){       
                   try{
                        $variation = new WC_Product_Variation();
                        //product id
                        $variation->set_parent_id(831);
                        $variation->set_attributes( array( 
                            'attribute_shaft' => $row[0],
                            'attribute_customize' => $row[1],
                            'attribute_weight-kit' => $row[2],
                            'attribute_grip' => $row[3],
                            'attribute_ferrule' => $row[4],
                        ) );
                        $variation->set_regular_price($row[5]);
                        $variation->save();    
                    }catch(Exception $ex){
                        echo $ex->getMessage();
                    }
                }
             }
         }
}
?>

<!--Import form-->
<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'];?>">
            <div class="form-group">
              <label for="email">Import excel file:</label>
              <input type="file" name="file" class="form-control" id="email" >
            </div>
            <button type="submit" style="border-color: #f2c103 !important;background:#f2c103 !important" name="btnSubmit" class="btn btn-default">Submit</button>
            <div class="form-group">
            </div>
</form>
