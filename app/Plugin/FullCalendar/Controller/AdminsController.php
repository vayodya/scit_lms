<?php
// app/Controller/UsersController.php
class AdminsController extends AppController {
    var $name = 'Admin';
    var  $uses = array('User','Admin','leave_record','Work_from_homes','temp_detail','Temp_user','emp_project','Project');
public $components = array('Session','Search.Prg');
   public $helperss = array('Html', 'Form');
	
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('conform','conform_wfh');
    }

    public function view_users(){
         $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
       $this->set('users', $this->User->find('all')); 
    }
    
     ////////---send mail after created new user account---/////
    public function send_mail($user_id,$password ) {
        $encrypted = $user_id ^ 18544332;
        $confirmation_link = "http://" . $_SERVER['HTTP_HOST'] . $this->webroot . "Users/email_profileedit/".$encrypted;
        $temp =  array('Temp_user' => array('confirmation_id' => $encrypted));   
        $this->Temp_user->save($temp);
        
        $records = $this->User->find('all',array('conditions' => array('User.id'=>$user_id)));
        $message = 'Hi ' . $records[0]['User']['EmpName'].",You'r current Username : ". $records[0]['User']['username']." and Password : ".$password." You can edit you'r profile through this link.. Thank You." ;
		
        App::uses('CakeEmail', 'Network/Email');
               
        $email = new CakeEmail('gmail');
        //$email->from('tharangalakma90@gmail.com');
        $email->from('pradeep90lakmal@gmail.com');
        $email->to($records[0]['User']['email']);
        //debug($records[0]['User']['email']);        
        $email->subject('Account Confirmation');
        $email->send($message . " " . $confirmation_link);
       
        $this->redirect(array('action' => 'create_new_user'));
    }
    /////////-------------////////////////////

   
    
    public function create_new_user(){
         $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
         $this->User->create();
        if ($this->request->is('post')){
           
            if(empty($this->data['users']['pro_picture']['name'])){
						unset($this->request->data['users']['pro_picture']);
					}
					
            
            if(!empty($this->data['users']['pro_picture']['name']))
					{
						$file=$this->data['users']['pro_picture'];
						$ary_ext=array('jpg','jpeg','gif','png'); //array of allowed extensions
						$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
						if(in_array($ext, $ary_ext))
						{
							move_uploaded_file($file['tmp_name'], WWW_ROOT . 'profile_pictures/' . $file['name']	);
							$this->request->data['users']['pro_picture'] = $file['name'];
                                                        
						}
					
	
                                        }
                 // $this->request->data['User']['pro_picture'] = $file['name'];
             if ($this->User->save($this->request->data)) 
 			{  
                  if (!empty($file)){
                 $x = '../profile_pictures/'.$file['name'];
                  $this->User->saveField('pro_picture',$x);
                  }
                  else{
                  
                      $this->User->saveField('pro_picture','../profile_pictures/Profile.jpg');
                  }
                ////////////////////////
                $this->Session->setFlash(__('The user has been saved'));
                $s = $this->User->getLastInsertID();
                
                //$this->redirect(array('action' => 'send_mail',$s,$pwd));
            } else {
                $this->set('errors', $this->User->validationErrors);
               //debug($this->User->validationErrors);
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
                                        }
        
        
    }
    
    
    
     public function delete($id= null) {
         
         $u1 = $this->User->find('all', array(
                                          'conditions' => array('User.id' => $id)));
         //debug($u1);
        if ($this->request->is('get')) {
        throw new MethodNotAllowedException();
    }

    if ($this->User->delete($id)) {
        
        $eid = $u1[0]['User']['EmpId'];
        //debug($u1);
        $this->Session->setFlash(__('The Employee with employee id: %s has been removed.', h($eid)));
        return $this->redirect(array('action' => 'view_users'));
        
    }
 
    
    }
    
    public function edit($id = null) {
      $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);   
   $us = $this->User->find('all', array(
                                          'conditions' => array('User.id' => $id)));
   $this->set('users', $us); 
             
    
    if (!$id) {
        throw new NotFoundException(__('Invalid User'));
    }

    $user = $this->User->findById($id);
    if (!$user) {
        throw new NotFoundException(__('Invalid User'));
    }

    if ($this->request->is('post') || $this->request->is('put')) {
        $this->User->id = $id;
        if ($this->User->save($this->request->data)) {
            $this->Session->setFlash(__('The user has been updated.'));
            return $this->redirect(array('action' => 'view_users'));
        }
        $this->Session->setFlash(__('Unable to update the user.'));
    }

    if (!$this->request->data) {
        $this->request->data = $user;
    }
}

    public function view_leave_report(){
      
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        /**$today = date("Y"."-"."m"."-"."d");
        
         $reports = $this->leave_record->find('all',array('fields' => 'leave_record.Eid,leave_record.Leave_Type,leave_record.Leave_Time,leave_record.Leave_comment',
                                         'conditions' =>  array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today,  'leave_record.Leave_states' => 'accepted')));
        
          $this->set('leaves', $reports); 
    
    
        $wfh  = $this->Work_from_homes->find('all',array('fields' => 'Work_from_homes.Eid,Work_from_homes.wfh_Time,Work_from_homes.wfh_comment',
                                         'conditions' =>  array('Work_from_homes.From_Date <=' => $today, 'Work_from_homes.To_Date >=' => $today,  'Work_from_homes.wfh_states' => 'accepted')));
        
          $this->set('wfhs', $wfh); **/
        
        $this->Prg->commonProcess();
		$this->paginate = array( 
			//'conditions' => $this->leave_record->parseCriteria($this->passedArgs));
                    'conditions' =>  array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today,  'leave_record.Leave_states' => 'accepted'));
		$this->set('leaves', $this->paginate());
    
    }
    
    /////////////// -- conformation update -- //////////
public function conform($id = null){
    
    
    
    if ($this->request->is('post') || $this->request->is('put')) {
        
        $post = $this->leave_record->findById($id);
    //debug($post);
    
     $ss = $this->params['pass'];
     
      if($ss[0]<= 0){
        $this->Session->setFlash(__('Can not Update'));
            
        $this->redirect(array('action' => 'conform'));
    } else{
    $decrypted = $ss[0] ^  18544332;
    
    
    
   
    
        $this->leave_record->id = $decrypted ;
       
        
        
       $con_id = $this->temp_detail->find('all', array(
                                          'conditions' => array('temp_detail.confirmation_id' => $ss[0])));
        
       
        if( !empty($con_id) && $this->leave_record->save($this->request->data)){
            $this->Session->setFlash(__('Your Conformation has been saved.'));
            
            $this->temp_detail->delete($con_id[0]['temp_detail']['id']);
            ///////////////////////////////////////////////////
            
            
           
            
            
            
            
            
            /////////////////////////////////////////////////
            $this->redirect(array('action' => 'conform'));
        }else{
            $this->Session->setFlash(__('Unable to save your Conformation.'));
        }
    }
    }
    /**if(!$this->request->data) {
        $this->request->data = $post;
    }**/ 
   
}

public function conform_wfh($id = null) {
    if ($this->request->is('post') || $this->request->is('put')){        
        $post = $this->Work_from_homes->findById($id);
        //debug($post);    
        $ss = $this->params['pass'];     
        if($ss[0]<= 0){
            $this->Session->setFlash(__('Can not Update'));            
            $this->redirect(array('action' => 'conform'));
        }else{
            $decrypted = $ss[0] ^  18544332;

            $this->Work_from_homes->id = $decrypted;        
            $con_id = $this->temp_detail->find('all', array('conditions' => array('temp_detail.confirmation_id' => $ss[0])));  

            if(!empty($con_id) && $this->Work_from_homes->save($this->request->data)){
                $this->Session->setFlash(__('Your Conformation has been saved.'));            
                $this->temp_detail->delete($con_id[0]['temp_detail']['id']);

                /////////////////////////////////////////////////
                $this->redirect(array('action' => 'conform'));
            }else{
                    $this->Session->setFlash(__('Unable to save your Conformation.'));
            }
        }
    }
}
   
/////////////////////////////////////////
public function leave_request(){
    
    $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
    $userId = $this->Auth->user('EmpId');    
    
    $line  = 'true';
    $projects  = $this->Project->find('all', array(
                                          'conditions' => array('Project.PM_EmpId' => $userId)));
    if(!empty($projects)){
    $pid = $projects[0]['Project']['Pid'];
    //$pid = $projects['Project']['PM_EmpId'];
   $x = $this->leave_record->find('all', array(
    'joins' => array(
        array(
            'table' => 'emp_projects',
            'alias' => 'emp',
            'type' => 'INNER',
            'conditions' => array(
                'leave_record.Eid = emp.Eid'
            )
        )
    ),
    'conditions' => array(
        'leave_record.Leave_states' => 'pending',
        'emp.pid' => $pid
    ),
    'fields' => array('leave_record.*', 'emp.*')
   
));
    $this->set('leave_request',$x);
     $this->set('line','true');
    } else{
        $this->set('line','false');
    }
}
  
////////////////////////////////////////////
public function wfh_request(){
    $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        $line  = 'true';
    
    $userId = $this->Auth->user('EmpId');    
    
    $projects  = $this->Project->find('all', array(
                                          'conditions' => array('Project.PM_EmpId' => $userId)));
    if(!empty($projects)){
    $pid = $projects[0]['Project']['Pid'];
    //$pid = $projects['Project']['PM_EmpId'];
   $x = $this->Work_from_homes->find('all', array(
    'joins' => array(
        array(
            'table' => 'emp_projects',
            'alias' => 'emp',
            'type' => 'INNER',
            'conditions' => array(
                'Work_from_homes.Eid = emp.Eid'
            )
        )
    ),
    'conditions' => array(
        'Work_from_homes.wfh_states' => 'pending',
        'emp.pid' => $pid
    ),
    'fields' => array('Work_from_homes.*', 'emp.*')
   
));
    $this->set('wfh_request',$x);
     $this->set('line','true');
     } else{
        $this->set('line','false');
    }
}


/////////////////////////////////////////////

public function accept($id){
    
    $this->leave_record->id = $id;
    $this->leave_record->saveField('Leave_states',"accepted"); 
    $this->Session->setFlash(__('Leave request is accepted'));
     $this->redirect(array('action' => 'leave_request')); 
}



//////////////////////////////////////////
public function reject($id){
    
    $this->leave_record->id = $id;
    $this->leave_record->saveField('Leave_states',"rejected"); 
    $this->Session->setFlash(__('Leave request is rejected'));
     $this->redirect(array('action' => 'leave_request')); 
}



/////////////////////////////////////////////

public function wfh_accept($id){
    
    $this->Work_from_homes->id = $id;
    $this->Work_from_homes->saveField('wfh_states',"accepted"); 
    $this->Session->setFlash(__('Work from home request is accepted'));
     $this->redirect(array('action' => 'wfh_request')); 
}



//////////////////////////////////////////
public function wfh_reject($id){
    
    $this->Work_from_homes->id = $id;
    $this->Work_from_homes->saveField('wfh_states',"rejected"); 
    $this->Session->setFlash(__('Work from home request is rejected'));
     $this->redirect(array('action' => 'wfh_request')); 
}


////////////////////////////////////////////

public function view_leave_record($id){
      $userId = $id; 
      
      $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
        $sick_real_days=0;
        $annual_real_days=0;
        $casual_real_days=0;
        
        //--------- find leave balance ----------------//
        

        $sick_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'sick','leave_record.Leave_states'=>'accepted')));
        foreach ($sick_blance as $xx1){
            $sick_real_days=($sick_real_days + $xx1['leave_record']['real_days']);               
        }//debug(7-$sick_real_days);
        $this->set('a1',7-$sick_real_days);

        $annual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'annual','leave_record.Leave_states'=>'accepted')));
        foreach ($annual_blance as $xx2){
            $annual_real_days=($annual_real_days + $xx2['leave_record']['real_days']);               
        }//debug(7-$annual_real_days);
        $this->set('a2',7-$annual_real_days);

        $casual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'casual','leave_record.Leave_states'=>'accepted')));
        foreach ($casual_blance as $xx3){
            $casual_real_days=($casual_real_days + $xx3['leave_record']['real_days']);               
        }//debug(7-$casual_real_days);
        $this->set('a3',7-$casual_real_days);
        
        /////leave usage///////////////////
          $leave_usage = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_states'=>'accepted')));
          $this->set('used_leave',$leave_usage);
        ///////////////////////////employee name////////////////
          $emp_name = $this->User->find('all',array('conditions' => array('User.EmpId'=>$userId)));
          $this->set('name',$emp_name[0]['User']['EmpName']);
           $this->set('eid',$userId);
          
}


/////////////////////////////////////
    public function logout() {
        $this->redirect($this->Auth->logout());
    }
    
    public function error() {
    
} 

////////////////////////////////////////////
public function add_project() {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        if($this->Project->save($this->request->data)){
            $this->Session->setFlash(__('Project details has been saved.'));              
       }
    }
    public function add_employee() {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        if($this->emp_project->save($this->request->data)){
            $this->Session->setFlash(__('Project details has been saved.'));              
       }
    }
    
    ////////////////////////////
    public function leave_record($id){
      $userId = $id; 
      
      $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
        $sick_real_days=0;
        $annual_real_days=0;
        $casual_real_days=0;
        
        //--------- find leave balance ----------------//
        

        $sick_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'sick','leave_record.Leave_states'=>'accepted')));
        foreach ($sick_blance as $xx1){
            $sick_real_days=($sick_real_days + $xx1['leave_record']['real_days']);               
        }//debug(7-$sick_real_days);
        $this->set('a1',7-$sick_real_days);

        $annual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'annual','leave_record.Leave_states'=>'accepted')));
        foreach ($annual_blance as $xx2){
            $annual_real_days=($annual_real_days + $xx2['leave_record']['real_days']);               
        }//debug(7-$annual_real_days);
        $this->set('a2',7-$annual_real_days);

        $casual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'casual','leave_record.Leave_states'=>'accepted')));
        foreach ($casual_blance as $xx3){
            $casual_real_days=($casual_real_days + $xx3['leave_record']['real_days']);               
        }//debug(7-$casual_real_days);
        $this->set('a3',7-$casual_real_days);
        
        /////leave usage///////////////////
          $leave_usage = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_states'=>'accepted')));
          $this->set('used_leave',$leave_usage);
        ///////////////////////////employee name////////////////
          $emp_name = $this->User->find('all',array('conditions' => array('User.EmpId'=>$userId)));
          $this->set('name',$emp_name[0]['User']['EmpName']);
           $this->set('eid',$userId);
          
}


}

?>
