<?php 


	echo $this->Form->create('Work_from_homes');
        $arrCategory=array('accepted'=>"Accept",'rejected'=>"Reject");
	echo $this->Form->input('Conformation',array('options'=>$arrCategory, 'name'=>'wfh_states','required'=>'required','placeholder'=>'Leave Time','title'=>'WFH Conformation is required.','empty'=>'-- Select --','selected'=>'Your Value'));
        //echo $this->Form->submit('Accept',array('div'=>false,'name'=>'accepted','name'=>'Leave_states','value'=>'accepted'));
        //echo $this->Form->submit('Reject',array('name'=>'rejected','name'=>'Leave_states','value'=>'rejected'));
        echo $this->Form->end('Send');
        //echo $this->Form->end();
?>