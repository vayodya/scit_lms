<?php
class LeaveRecordsController extends AppController{
    public $helperss = array('Html', 'Form');
    public $components = array('Session','Search.Prg');
    var $uses=array('leave_record','User','emp_project','Project','temp_detail','events');
    var $helpers = array('Calendar'); 
    
    //var $ENCRYPTION_KEY = "18544";
    
    public $presetVars = array(
		array('field' => 'From_Date', 'type' => 'value'),
        array('field' => 'To_Date', 'type' => 'value'),
		array('field' => 'Eid', 'type' => 'value'),
	);
   
     public function beforeFilter() {
          $records = $this->leave_record->find('all',array('conditions' => array('leave_record.Leave_states'=>"accepted")));
		$this->set('statuses', $records);
		//$this->set('categories', $this->Ticket->categories);
		parent::beforeFilter();
	}
    
    public function send_mail($leave_id ) {
       $encrypted = $leave_id ^ 18544332;
       //debug($encrypted);
       //$i = 18;
        $confirmation_link = "http://" . $_SERVER['HTTP_HOST'] . $this->webroot . "Admins/conform/".$encrypted;
        
        //$leave_details = 
        //debug($leave_id);
        
        ////////////////////////save dateails into temp table///////////////////
        $temp =  array('temp_detail' => array('confirmation_id' => $encrypted));
        
        
        $this->temp_detail->save($temp);
        
        ////////////////////////////////////////////////
        
        $records = $this->leave_record->find('all',array('conditions' => array('leave_record.id'=>$leave_id)));
        $message = 'Hiii employee id : ' . $this->Auth->user('EmpId') . ', requests for leave '.'  '.'From Date : '.$records[0]['leave_record']['From_Date']
                    .'  '.'To Date : '.$records[0]['leave_record']['To_Date'].' '.'Leave Type : '.$records[0]['leave_record']['Leave_Type'].'  '.'Leave Time : '.$records[0]['leave_record']['Leave_Time']
                    .'  '.'Leave Comment : '.$records[0]['leave_record']['Leave_comment'];
		//$message = 'Hiiiiiiiiiiii machn rights';
        App::uses('CakeEmail', 'Network/Email');
        
        ///find projects
         $userId = $this->Auth->user('EmpId');
        $x=$this->emp_project->find('all',array('conditions' => array('emp_project.Eid'=>$userId)));
         
        //find project details and emails
        for($i = 0; $i<count($x); $i++){
           //debug($emp_projects['emp_project']['Pid']); 
           
           $rec[$i] = $this->Project->find('all',array('fileds' => 'Project.PM_email','conditions' => array('Project.Pid'=>$x[$i]['emp_project']['Pid'])));
           
        }
        
        foreach($rec as $receiver){
            //debug($receiver[0]['Project']['PM_email']);
        
        
        
        //debug($x[2]['emp_project']['Pid']);
        $email = new CakeEmail('gmail');
        $email->from('tharangalakma90@gmail.com');
        $email->to($receiver[0]['Project']['PM_email']);
        $email->subject('Leave Confirmation');
        $email->send($message . " " . $confirmation_link);
        }
         $this->redirect(array('action' => 'add'));
    }

    
////////////////////////// Add Leave Record  ///////////////////////////////////////////////////////////
    public function add(){            
        $userId = $this->Auth->user('EmpId'); 
         $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
        
        $sick_real_days=0;
        $annual_real_days=0;
        $casual_real_days=0;
        
        //--------- find leave balance ----------------//
        $userId = $this->Auth->user('EmpId');

        $sick_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'sick')));
        foreach ($sick_blance as $xx1){
            $sick_real_days=($sick_real_days + $xx1['leave_record']['real_days']);               
        }//debug(7-$sick_real_days);
        $this->set('a1',$sick_real_days);

        $annual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'annual')));
        foreach ($annual_blance as $xx2){
            $annual_real_days=($annual_real_days + $xx2['leave_record']['real_days']);               
        }//debug(7-$annual_real_days);
        $this->set('a2',$annual_real_days);

        $casual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'casual')));
        foreach ($casual_blance as $xx3){
            $casual_real_days=($casual_real_days + $xx3['leave_record']['real_days']);               
        }//debug(7-$casual_real_days);
        $this->set('a3',$casual_real_days);
        
        if($this->request->is('post')){
            if(date("Y-m-d")<=$this->request->data['From_Date']){
                if(date("Y-m-d")<=$this->request->data['To_Date']){                                  
                    $realdays=0;
                    $realhafs=0;
                    $total_realdays=0;
                    $start_date = $this->request->data['From_Date']; 
                    $end_date = $this->request->data['To_Date'];
                    $leave_time = $this->request->data['Leave_Time'];
                    //debug($leave_time);
                    $x=$this->events->find('all');
                    for($i = 0; $i<count($x); $i++){
                        $holy_date[$i] = $this->events->find('all');
                        $holy_startdate_v=($holy_date[$i][$i]['events']['start']);
                        $holy_enddate_v=($holy_date[$i][$i]['events']['end']);
                        
                        // Convert to timestamp
                        $start_ts = new DateTime($start_date);
                        $end_ts = new DateTime($end_date);
                        $holy_start = new DateTime($holy_startdate_v);
                        $holy_end = new DateTime($holy_enddate_v);
                        
                        $diff=$holy_start->diff($holy_end);
                        $q=$diff->d;
                        $z=$diff->h;
                        //debug($q); // ori holy day nbr of days 
                        //debug($z); // ori holy day nbr of hurs
                        
                        $now = new DateTime($this->request->data['From_Date']);
                        $ref = new DateTime($this->request->data['To_Date']);
                        $diff = $now->diff($ref);
                        $j = $diff->d;
                        
                        if($j<1){$j=$diff->d-1;
                            if($leave_time!='fullday'){$j=$j/2;}
                            else{$j=$j+1;}
                        }else{$j = $diff->d;
                        if($leave_time!='fullday'){$j=$j/2;}
                            else{$j=$j;}
                        }
                        
                        //-------- check full or 1/2 -----------// 
                        if($leave_time!='fullday'){$j=$j/2;}
                        else{$j=$j;}
                        
                        if((($holy_start >= $start_ts) && ($holy_start <= $end_ts))||(($holy_start <= $start_ts) && ($holy_end >= $start_ts))){
                                //$count_holy++;                            
                            ///}                        
                        //}
                            
                            if(($q>=1||$z>6)){
                                //$realdays++;
                                $realdays=$j-($q)+1;
                                //debug($count_holy);                    
                                //$realdays = + $realdays;  
                                //debug($realdays);
                            }else{
                                //$realhafs++;
                                 //debug($j);
                                $realhafs=$j-1/2;
                                //$realhafs = + $realhafs;
                               
                            }                            
                            $total_realdays=($realdays+$realhafs);
                        }else{
                            $total_realdays=$j;
                        }
                    //$total_realdays=$realdays-$realhafs;                    
                    }
                    
                    if($j<7){                    
                        if($this->leave_record->save($this->request->data)){
                             $this->Session->setFlash(__('Your Leave has been saved.'));            
                             $s = $this->leave_record->getLastInsertID();
                             $this->leave_record->id = $s;
                             $this->leave_record->saveField('Eid',$userId);
                             if($total_realdays<0){
                                 $total_realdays=0;                             
                                 $this->leave_record->saveField('real_days',$total_realdays);
                             }else $this->leave_record->saveField('real_days',$total_realdays);
                             //$this->leave_record->saveField('real_days',$total_realdays);
                             $this->redirect(array('action' => 'send_mail',$s));                
                        }else{
                             $this->Session->setFlash(__('Unable to add your Leave.'));
                        }
                    }else{
                        $this->Session->setFlash(__('Oh!! Your Leave Request is Verry Long... Sorry Try Again.'));
                    }   
                }else{
                    $this->Session->setFlash(__('Invalide To Date.Please Correct it and Try Again..'));
                }
            }else{
                $this->Session->setFlash(__('Invalide From Date.Please Correct it and Try Again..'));
            }      
        }
    }
 ///////////////////////////// End ///////////////////////////////////////////////////////////////////////
    
    //edit page action
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->leave_record->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->leave_record->id = $id;
            if ($this->leave_record->save($this->request->data)){
                $this->Session->setFlash(__('Your post has been updated.'));
                $this->redirect(array('action' => 'leavereport'));
            } else {
                $this->Session->setFlash(__('Unable to update your post.'));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }
 
    
    
    //delete page action
    public function delete($id) {
        if ($this->request->is('get')){
            throw new MethodNotAllowedException();
        }

         if ($this->leave_record->delete($id)) {
            //$this->Session->setFlash(__('The post with id: %s has been deleted.', $id));
            $this->Session->setFlash(__('Your Leave Successfuly Canceled.'));
            $this->redirect(array('action' => 'leavereport'));
        }
    }
    
     public function view_leave_report(){
          $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
        $today = date("Y"."-"."m"."-"."d");
        
        /** $reports = $this->leave_record->find('all',array('fields' => 'leave_record.Eid,leave_record.Leave_Type,leave_record.Leave_Time,leave_record.Leave_comment',
                                         'conditions' =>  array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today,  'leave_record.Leave_states' => 'accepted')));
        
          $this->set('leaves', $reports); 
    
    
        $wfh  = $this->Work_from_homes->find('all',array('fields' => 'Work_from_homes.Eid,Work_from_homes.wfh_Time,Work_from_homes.wfh_comment',
                                         'conditions' =>  array('Work_from_homes.From_Date <=' => $today, 'Work_from_homes.To_Date >=' => $today,  'Work_from_homes.wfh_states' => 'accepted')));
        
          $this->set('wfhs', $wfh); **/
        
        $this->Prg->commonProcess();
		$this->paginate = array( 
			'conditions' => $this->leave_record->parseCriteria($this->passedArgs));
                    //'conditions' =>  array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today,  'leave_record.Leave_states' => 'accepted'));
		$this->set('leaves', $this->paginate());
    
    }
    
   

    public function leavereport(){
         $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
       ///window.location.reload();
        $userId = $this->Auth->user('EmpId');
        //debug($userId);
        //$x=$this->leave_record->find('all');
                
        
         $this->Prg->commonProcess();
		$this->paginate = array( 
			'conditions' => array($this->leave_record->parseCriteria($this->passedArgs),'leave_record.Eid ' => $userId));
                    //'conditions' =>  array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today,  'leave_record.Leave_states' => 'accepted'));
		//$this->set('leaves', $this->paginate());
        //debug($this->passedArgs);
        $this->set('leave_records',$this->paginate());
        //debug($this->paginate());
    }
    
    /**public function wcalender() {
        
    }**/
    
   
    
}
?>
