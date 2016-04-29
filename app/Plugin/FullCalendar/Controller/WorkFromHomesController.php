<?php
class WorkFromHomesController extends AppController {
    var $uses=array('Work_from_homes','emp_project','Project','temp_detail','events');
     public $components = array('Session','Search.Prg');
      public $helperss = array('Html', 'Form');
     
     public $presetVars = array(
		array('field' => 'From_Date', 'type' => 'value'),
        array('field' => 'To_Date', 'type' => 'value'),
		//array('field' => 'status', 'type' => 'value'),
	);
   
     public function beforeFilter() {
          $records = $this->Work_from_homes->find('all',array('conditions' => array('Work_from_homes.wfh_states'=>"accepted")));
		$this->set('statuses', $records);
		//$this->set('categories', $this->Ticket->categories);
		parent::beforeFilter();
	}
    
    public function send_mail($leave_id ) {
       $encrypted = $leave_id ^ 18544332;       
        $confirmation_link = "http://" . $_SERVER['HTTP_HOST'] . $this->webroot . "Admins/conform_wfh/".$encrypted;
        
        ////////////////////////save dateails into temp table///////////////////
        $temp =  array('temp_detail' => array('confirmation_id' => $encrypted));
        
        $this->temp_detail->save($temp);
        
        ////////////////////////////////////////////////
        
        $records = $this->Work_from_homes->find('all',array('conditions' => array('Work_from_homes.id'=>$leave_id)));
        $message = 'Hiii employee id : ' . $this->Auth->user('EmpId') . ', requests for leave '.'  '.'From Date : '.$records[0]['Work_from_homes']['From_Date']
                    .'  '.'To Date : '.$records[0]['Work_from_homes']['To_Date'].' '.'Leave Time : '.$records[0]['Work_from_homes']['wfh_Time']
                    .'  '.'Leave Comment : '.$records[0]['Work_from_homes']['wfh_comment'];
		//$message = 'Hiiiiiiiiiiii machn rights';
        App::uses('CakeEmail', 'Network/Email');
        
        ///find projects
        $userId = $this->Auth->user('EmpId');
        $x=$this->emp_project->find('all',array('conditions' => array('emp_project.Eid'=>$userId)));
         
        //find project details and emails
        for($i = 0; $i<count($x); $i++){         
           $rec[$i] = $this->Project->find('all',array('fileds' => 'Project.PM_email','conditions' => array('Project.Pid'=>$x[$i]['emp_project']['Pid'])));
        }        
        /*foreach($rec as $receiver){            
            $email = new CakeEmail('gmail');
            $email->from('tharangalakma90@gmail.com');
            $email->to($receiver[0]['Project']['PM_email']);
            $email->subject('WFH Confirmation');
            $email->send($message . " " . $confirmation_link);
        }*/
        foreach($rec as $receiver){            
            $email = new CakeEmail('gmail');
            $email->from('tharangalakma90@gmail.com');
            $email->to($receiver[0]['Project']['PM_email']);
            $email->subject('WFH Confirmation');
            $email->send($message . " " . $confirmation_link);
        }
        $this->redirect(array('action' => 'add'));
    }
   
   public function add(){
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
         $userId = $this->Auth->user('EmpId');       
            
        if($this->request->is('post')){
            if(date("Y-m-d")<=$this->request->data['From_Date']){
                if(date("Y-m-d")<=$this->request->data['To_Date']){
                    
                    $count_holy=0;
                    $realdays=0;
                    $realhafs=0;
                    $total_realdays=0;
                    $start_date = $this->request->data['From_Date']; 
                    $end_date = $this->request->data['To_Date'];
                    $leave_time = $this->request->data['wfh_Time'];
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
                        
                        //--check full or half--//
                        if($j<1){$j=$diff->d-1;
                            if($leave_time!='fullday'){$j=$j/2;}
                            else{$j=$j+1;}
                        }else{$j = $diff->d;
                        if($leave_time!='fullday'){$j=$j/2;}
                            else{$j=$j;}
                        }
                        
                        if((($holy_start >= $start_ts) && ($holy_start <= $end_ts))||(($holy_start <= $start_ts) && ($holy_end >= $start_ts))){
                                //$count_holy++;                            
                            ///}                        
                        //}
                            
                            if($q>=1||$z>6){
                                $realdays=$j-($q)+1;
                                //debug($count_holy);                    
                                //$realdays = + $realdays;  
                                //debug($realdays);
                            }else{
                                $realhafs=$realdays-1/2;
                                //$realhafs = + $realhafs;
                                //debug($realhafs);
                            }
                            $total_realdays=$realdays-$realhafs;
                        }else{
                            $total_realdays=$j;
                        }
                    //$total_realdays=$realdays-$realhafs;                    
                    }                    
                    //echo $total_realdays;
                    //$this->set('a',$total_realdays);
                    //
                        if($this->Work_from_homes->save($this->request->data)){
                             $this->Session->setFlash(__('Your WFH has been saved.'));            
                             $s = $this->Work_from_homes->getLastInsertID();
                             $this->Work_from_homes->id = $s;
                             $this->Work_from_homes->saveField('Eid',$userId);
                            if($total_realdays<0){
                                 $total_realdays=0;                             
                                 $this->Work_from_homes->saveField('real_days',$total_realdays);
                             }else $this->Work_from_homes->saveField('real_days',$total_realdays);
                             //$this->leave_record->saveField('real_days',$total_realdays);
                             $this->redirect(array('action' => 'send_mail',$s));             
                        }else{
                             $this->Session->setFlash(__('Unable to add your WFH.'));
                        }
                        }else{
                    $this->Session->setFlash(__('Invalide To Date.Please Correct it and Try Again..'));
                }
            }else{
                $this->Session->setFlash(__('Invalide From Date.Please Correct it and Try Again..'));
            }     
                        
        }
    }
	
	 //edit page action
    public function edit($id = null){
        if(!$id){
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Work_from_homes->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Work_from_homes->id = $id;
            if ($this->Work_from_homes->save($this->request->data)){
                $this->Session->setFlash(__('Your post has been updated.'));
                $this->redirect(array('action' => 'wfhreport'));
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

         if ($this->Work_from_homes->delete($id)) {
            //$this->Session->setFlash(__('The post with id: %s has been deleted.', $id));
            $this->Session->setFlash(__('Your WFH Successfuly Canceled.'));
            $this->redirect(array('action' => 'wfhreport'));
        }
    }
	
    public function wfhreport(){ 
          $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
       ///window.location.reload();
        $userId = $this->Auth->user('EmpId');
        //debug($userId);
        //$x=$this->leave_record->find('all');
                
        
         $this->Prg->commonProcess();
		$this->paginate = array( 
			'conditions' => array($this->Work_from_homes->parseCriteria($this->passedArgs),'Work_from_homes.Eid ' => $userId));
                    //'conditions' =>  array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today,  'leave_record.Leave_states' => 'accepted'));
		//$this->set('leaves', $this->paginate());
        //debug($this->passedArgs);
        $this->set('Work_from_home',$this->paginate());
	//debug($x);
    }
    
     public function view_wfh_report(){
         $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        $today = date("Y"."-"."m"."-"."d");
        
        
        
        $this->Prg->commonProcess();
		$this->paginate = array( 
			'conditions' => $this->Work_from_homes->parseCriteria($this->passedArgs));
                    //'conditions' =>  array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today,  'leave_record.Leave_states' => 'accepted'));
		$this->set('wfh', $this->paginate());
    
    }
    
}
?>
