<?php
class FilesController extends AppController {
    public $helperss = array('Html', 'Form','Csv');
    var $uses=array('leave_record','User','emp_project','Project','temp_detail','events','Work_from_homes','File');
   
    
    function add()
    {			
        if (!empty($this->params['form']) && 
             is_uploaded_file($this->params['form']['Fil']['tmp_name']))
        {
            $fileData = fread(fopen($this->params['form']['File']['tmp_name'], "r"), 
                                     $this->params['form']['File']['size']);
            $this->params['form']['File']['data'] = $fileData;
					
            $this->File->save($this->params['form']['File']);

            //$this->redirect('somecontroller/someaction');
        }
    }
}
?>