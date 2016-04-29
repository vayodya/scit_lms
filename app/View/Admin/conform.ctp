<?php 


	echo $this->Form->create('leave_records');
        $arrCategory=array('accepted'=>"Accept",'rejected'=>"Reject");
	echo $this->Form->input('Conformation',array('options'=>$arrCategory, 'name'=>'Leave_states','required'=>'required','placeholder'=>'Leave Time','title'=>'Leave Time is required.','empty'=>'-- Select --','selected'=>'Your Value'));
        
        echo $this->Form->end('Send');
        //echo $this->Html->link('leave record', array('action' => 'leave_record', $userID)); 
?>