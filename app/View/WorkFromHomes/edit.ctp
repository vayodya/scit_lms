<?php echo $this->element("menu"); ?>
</body>
<h1>Edit Your WFH.</h1>
<?php
 
    echo $this->Form->create('work_from_homes');
    //echo $this->Form->input('Employee Name',array('type' => 'text','name'=> 'Ename','required'=>'required','placeholder'=>'Employee Name','title'=>'Your Employee Name is required.'));
    //echo $this->Form->input('Employee ID',array('type' => 'text','name'=> 'Eid','required'=>'required','placeholder'=>'Employee ID','title'=>'Your Employee ID is required.'));
    //$arrCategory=array('sick'=>"Sick",'anual'=>"Anual",'casual'=>"Casual");
    //echo $this->Form->input('Leave Type',array('options'=>$arrCategory, 'name'=>'Leave_Type','required'=>'required','placeholder'=>'Leave Type','title'=>'Leave Type is required.','empty'=>'-- Select --','selected'=>'Your Value'));
			
    //echo $this->Form->input('From Date', array('type' => 'date','name' =>('CONCAT(From_Date[0],' ',From_Date[1])')','required'=>'required','placeholder'=>'From Date','title'=>'From Date is required.','empty' => true, 'minYear' => date('Y')-60,'maxYear' => date('Y')+1));
	
    echo $this->Form->input('From Date', array('name' => 'From_Date','dateFormat' =>'YMD','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.','empty' => true, 'minYear' => date('Y')-60,'maxYear' => date('Y')+1));
    echo $this->Form->input('To Date', array('name' => 'To_Date','dateFormat' =>'YMD','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'To Date is required.','empty' => true, 'minYear' => date('Y')-60,'maxYear' => date('Y')+1));
    //echo $this->Form->input('dob', array('type'=> 'date', 'name' => 'To_Date', 'dateFormat' => 'DMY', 'minYear' => date('Y') - 111, 'maxYear' => date('Y'))); 	
	
    $arrCategory=array('1st Half'=>"1st Half",'2nd Half'=>"2nd Half",'Full Day'=>"Full Day");
    echo $this->Form->input('Leave Time',array('options'=>$arrCategory, 'name'=>'wfh_Time','required'=>'required','placeholder'=>'Leave Time','title'=>'Leave Time is required.','empty'=>'-- Select --','selected'=>'Your Value'));
    echo $this->Form->input('Leave Comment', array('type' => 'textarea','name'=>'wfh_comment'));   
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo $this->Form->submit('Save Changes');
    echo $this->Form->button('Cancel', array('type'=>'reset'));
    echo $this->Form->end();
 
?>