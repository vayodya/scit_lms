<?php
App::uses('MailUtil', 'Lib');

class UsersController extends AppController {
    var $name = 'Users';
    var  $uses = array('User','leave_record','Holiday','emp_project','Project','Temp_user','Event');
    var $helpers = array('Html', 'Form');
    var $components = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('email_notify','index','login','forget_password',
                'login2','change_pwd','encrypt_decrypt','Join_leave_balance',
                'checkIsUnique', 'getUsersByProjects', 'updateLeaveCount');
    }

    public function index() {
        
        $this->redirect(array('action' => 'login'));
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function home() {
        $userId = $this->Auth->user('EmpId');    
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));

        if($role['User']['role']=='pm'){$this->set('title_for_layout', 'Manager');}
        if($role['User']['role']=='admin'){$this->set('title_for_layout', 'Admin');}
        if($role['User']['role']=='CEO'){$this->set('title_for_layout', 'CEO');}
        if($role['User']['role']=='normal'){$this->set('title_for_layout', 'User');}
        if($role['User']['role']=='tl'){$this->set('title_for_layout', 'Lead');}
        
        //print personal details
        $details = $this->User->find('all', array('conditions' => array('User.EmpId' => $userId)));
        $today = date("Y-m-d");
        $line  = 'true';
        $projects  = $this->emp_project->find('all', array('conditions' => array('emp_project.Eid' => $userId)));
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        $this->set('Role',$role['User']['role']);
        
        //---leave assing---//
        $leave_no = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        $this->set('no_sick_lv',$leave_no['User']['nof_sick_lv']);
        $this->set('no_ann_lv',$leave_no['User']['nof_ann_lv']);
        $this->set('no_cas_lv',$leave_no['User']['nof_cas_lv']);
        $this->set('no_liv_lv',$leave_no['User']['nof_liv_lv']);        

        //------New leave request for CEO ---------//
        $notff=$this->leave_record->find('all', array('conditions' =>array('leave_record.Leave_states'=>'pending', array('OR'=>array(array('leave_record.From_date >' => $today,'leave_record.Leave_Type ' => 'annual','leave_record.accept_id !=' => 6 ),array('leave_record.From_date >' => $today,'leave_record.Leave_Type ' => 'casual','leave_record.accept_id !=' => 6),array('leave_record.Leave_Type ' => 'sick','leave_record.accept_id !=' => 6))))));

        if(count($notff)==0){
            $this->set('notff','No new');
            $this->set('line','true');
        }else{
            $this->set('notff',count($notff));
            $this->set('line','true');
        }
        
        //-----------New leave request for !CEO ------------//
        for($i = 0; $i <count($projects); $i++){
            $pro[$i] = $projects[$i]['emp_project']['Pid'];
        }

    if(!empty($projects)){
    //for($i = 0; $i<count($projects); $i++){
        if(count($projects)>1) {  
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
                    'AND' => array(
                    
//                    array(
                        'leave_record.accept_id >=' => $role['User']['group_id'],'leave_record.accept_id !=' => 6
//                        )
                        ,        
                    'emp.Pid in' => $pro,
                        'leave_record.From_date <=' => date("Y").'01-01',
                        'leave_record.From_date >' => $today
                    )
                ),
                'fields' => array('leave_record.*', 'emp.*')
            ));

            if(!empty($x)){
                $pids = array();
                $y=array();
                foreach ($x as $h) {
                    $pids[] = $h['leave_record']['id'];
                }
                $uniquePids = array_unique($pids);

                $count = 0;
                
                foreach($uniquePids as $un){
                    $x = $this->leave_record->find('first', array('conditions' => array('leave_record.id' => $un))); 

                    $y[$count] = $x;
                    $count++;
                } 
            }
        } else {
            
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
        'leave_record.accept_id >=' => $role['User']['group_id'],
        
        'emp.Pid' => $pro[0],
        'leave_record.From_date >' => $today
        
        
    ),
    'fields' => array('leave_record.*', 'emp.*')
   
        ));
            if(!empty($x)){
            $pids = array();
        $y=array();
            foreach ($x as $h) {
                $pids[] = $h['leave_record']['id'];
            }
            $uniquePids = array_unique($pids);
            
          $count = 0;
            foreach($uniquePids as $un){
                
            
       $x = $this->leave_record->find('first', array('conditions' => array('leave_record.id' => $un))); 
       //debug($x);
       
       $y[$count] = $x;
       $count++;
       
            }  
            }else{$y=0;}  
        }
    //$this->set('leave_request',$y);
     //$this->set('line','true');
    //}
     
        
     if(count($y)==0){ 
            $this->set('notff2','No new');
            $this->set('line','true');
        }else{
            $this->set('notff2',count($y));
            $this->set('line','true');
        }
     
     
     
    }else{
        $this->set('notff2','No new');
        $this->set('line','false');
    }
        //----------------  
             
        $this->set('detail',$details);
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        ////////////-------------leave balance------------////////// 
        $sick_real_days=0;
        $annual_real_days=0;
        $casual_real_days=0;
        $live_real_days=0;
        $sick_pending_real_days=0;
        $annual_pending_real_days=0;
        $casual_pending_real_days=0;
        $nopay_pending_real_days=0;
        $live_pending_real_days=0;
        $today = date("Y-m-d");
        //--------- find leave balance ----------------//
        
        $sick_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'sick','leave_record.Leave_states'=>'accepted')));
        $sick_panding_blance=$this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'sick','leave_record.Leave_states'=>'pending','leave_record.From_date >' => $today)));
        foreach ($sick_panding_blance as $zz1){
            $sick_pending_real_days=($sick_pending_real_days + $zz1['leave_record']['real_days']);               
        }//debug(7-$sick_real_days);
        $this->set('aa1',$sick_pending_real_days);
        
        foreach ($sick_blance as $xx1){
            $sick_real_days=($sick_real_days + $xx1['leave_record']['real_days']);               
        }//debug(7-$sick_real_days);
        $this->set('a1',$sick_real_days);
        
        $annual_panding_blance = $this->leave_record->find('all',array(
            'conditions' => array(
                'leave_record.Eid'=>$userId,                    
                'leave_record.Leave_Type'=>'annual',
                'leave_record.Leave_states'=>'pending',         
                'leave_record.From_date >=' => date("Y").'-01-01',
                'leave_record.From_date <=' => date("Y").'-12-31'                
            )));
        $annual_blance = $this->leave_record->find('all',array(
            'conditions' => array(
                'leave_record.Eid'=>$userId,
                'leave_record.Leave_Type'=>'annual',
                'leave_record.Leave_states'=>'accepted',
                'leave_record.From_date >=' => date("Y").'-01-01',
                'leave_record.From_date <=' => date("Y").'-12-31'                
            )));
        foreach ($annual_panding_blance as $zz2){
            $annual_pending_real_days=($annual_pending_real_days + $zz2['leave_record']['real_days']);
        }//debug(7-$annual_real_days);
        $this->set('aa2',$annual_pending_real_days);
        
        foreach ($annual_blance as $xx2){
            $annual_real_days=($annual_real_days + $xx2['leave_record']['real_days']);               
        }//debug(7-$annual_real_days);
        $this->set('a2',$annual_real_days);

        $casual_pending_blance=$this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'casual','leave_record.Leave_states'=>'pending',
                'leave_record.From_date >=' => date("Y").'-01-01',
                'leave_record.From_date <=' => date("Y").'-12-31')));
        $casual_blance = $this->leave_record->find('all',array(
            'conditions' => array(
                'leave_record.Eid'=>$userId,                    
                'leave_record.Leave_Type'=>'casual',
                'leave_record.Leave_states'=>'accepted',        
                'leave_record.From_date >=' => date("Y").'-01-01',
                'leave_record.From_date <=' => date("Y").'-12-31'                
             )));
        foreach ($casual_pending_blance as $zz3){
            $casual_pending_real_days=($casual_pending_real_days + $zz3['leave_record']['real_days']);               
        }//debug(7-$casual_real_days);
        $this->set('aa3',$casual_pending_real_days);
        
        foreach ($casual_blance as $xx3){
            $casual_real_days=($casual_real_days + $xx3['leave_record']['real_days']);               
        }//debug(7-$casual_real_days);
        $this->set('a3',$casual_real_days);
        
        $live_pending_blance=$this->leave_record->find('all',array(
            'conditions' => array(
                'leave_record.Eid'=>$userId,
                'leave_record.Leave_Type'=>'live',
                'leave_record.Leave_states'=>'pending',
                'leave_record.From_date >=' => date("Y").'-01-01',
                'leave_record.From_date <=' => date("Y").'-12-31')));
        $live_blance = $this->leave_record->find('all',array(
            'conditions' => array(
                'leave_record.Eid'=>$userId,
                'leave_record.Leave_Type'=>'live',
                'leave_record.Leave_states'=>'accepted',
                'leave_record.From_date >=' => date("Y").'-01-01',
                'leave_record.From_date <=' => date("Y").'-12-31'                
            )));
        foreach ($live_pending_blance as $pp3){
            $live_pending_real_days=($live_pending_real_days + $pp3['leave_record']['real_days']);               
        }//debug(7-$casual_real_days);
        $this->set('aa5',$live_pending_real_days);
        
        foreach ($live_blance as $xx5){
            $live_real_days=($live_real_days + $xx5['leave_record']['real_days']);               
        }//debug(7-live_real_days);
        $this->set('a5',$live_real_days);
        
        
        
       ///////projects
       $projects = $this->emp_project->find('all', array('conditions' => array('emp_project.Eid' => $userId)));
       //$this->set('project',$projects);
       //debug($projects);
       
       $count = 0;
       $mark = true;
       for($i = 0; $i<count($projects); $i++){
           //debug($emp_projects['emp_project']['Pid']); 
           
           $rec[$i] = $this->Project->find('all',array('conditions' => array('Project.Pid'=>$projects[$i]['emp_project']['Pid'])));
           if(!empty($rec[$i])) 
               $count = 1;
        }
        
        if($count> 0){
        foreach($rec as $receiver){
            
        $info[] = $receiver[0]['Project'];
        //$pro_de[]=$receiver[0]['Project']['pro_description'];
        }
        
       //$this->set('pro_info',$pro_de);
       $this->set('information',$info);
       $this->set('marks',$mark);
        }
        
        else {
            $mark = false;
            $this->set('marks',$mark);
        }
        $this->set('pro_cunt',count($projects));
    }
    
    public function login(){
//        $this->Join_leave_balance();

        if($this->Auth->user('id')) {
            $this->redirect(array('controller' => 'users', 'action' => 'home'));
        }
        
        if ($this->request->is('post')){
            if($this->Auth->login()){
                $this->redirect(array('controller' => 'users', 'action' => 'home'));
            }else {
                $this->Session->setFlash(__('Invalid username or password, try again'));
            }            
        }
    }
    
    function captcha()	{
	$this->autoRender = false;
	$this->layout='ajax';
	if(!isset($this->Captcha))	{ //if Component was not loaded throug $components array()
            $this->Captcha = $this->Components->load('Captcha', array(
		'width' => 150,
		'height' => 50,
		'theme' => 'default', //possible values : default, random ; No value means 'default'
            )); //load it
	}
	$this->Captcha->create();
    }
    
   public function profileedit($id = null){
       $normal_plag1=0;
       $pass_plag=0;
        $userId = $this->Auth->user('EmpId');
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);          
        ///////human validation-----------////////
        
        $prv_image=$this->User->find('all',array('conditions' => array('User.EmpId' => $userId)));
        $xx=$prv_image[0]['User']['pro_picture'];
        
        //------- current password checker----------//
        $passwd=$prv_image[0]['User']['password'];        
        if($this->request->is('post')){
            $od = AuthComponent::password($this->request->data['users']['Current Password']);
            if($od==$passwd){$pass_plag=1;}else{$pass_plag=0;}
        }
        //----end--- current password checker----------//
        
        if(!empty($this->request->data)){
            if(!isset($this->Captcha)){ //if Component was not loaded throug $components array()
                $this->Captcha = $this->Components->load('Captcha'); //load it
            }
            $this->User->setCaptcha($this->Captcha->getVerCode()); //getting from component and passing to model to make proper validation check
            $this->User->set($this->request->data);
            //----check new password & re-password---------//
            
            //if($this->User->validates()){ //as usual data save call            
            if($this->User->cptr($this->request->data['cptr'])){ //------my new captcha cheak --------//                
                $this->User->create();
                if($this->request->is('post') || $this->request->is('put')){                  
                    if($this->request->data['users']['New Password']!=""){ //////---------want edit password------//
                        if($pass_plag==1){
                            if(strlen($this->request->data['users']['New Password'])>= 8){
                                if(($this->request->data['users']['New Password']==$this->request->data['users']['Re-Password'])){                    
                            /////////////////////////////////////////////////
                                    if(empty($this->data['users']['pro_picture']['name'])){
                                        $con_id = $this->User->find('all', array('conditions' => array('User.EmpId' =>$userId)));
                                        $this->User->id = $con_id[0]['User']['id'];    
                                        /**if($this->User->save($this->request->data)){
                                            $this->User->saveField('pro_picture',$xx);
                                            $this->Session->setFlash(__('Your Conformation has been saved.'));
                                            $this->redirect(array('action' => 'home'));
                                        }else{
                                            $this->Session->setFlash(__('Unable to save your Conformation.'));
                                        }**/                        
                                        if($this->request->data['users']['New Password'] !="" /*&& $pwd_plag==1*/){
                                            $this->User->saveField('password',$this->request->data['users']['New Password']);
                                        }//else{$this->Session->setFlash(__('Password confirmation is wrong.'));break;}
                                        if($this->request->data['users']['Email'] !="")
                                            $this->User->saveField('email',$this->request->data['users']['Email']);
                                        $this->Session->setFlash(__('Your Changes has been saved.'));

                                    }           
                                    if(!empty($this->data['users']['pro_picture']['name'])){
                                        $file=$this->request->data['users']['pro_picture'];
                                        $ary_ext=array('jpg','jpeg','gif','png'); //array of allowed extensions
                                        $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                                        if(in_array($ext, $ary_ext)){
                                            move_uploaded_file($file['tmp_name'], WWW_ROOT . 'profile_pictures/' . $file['name'] );
                                            $this->request->data['users']['pro_picture'] = $file['name'];
                                        }                       
                                        /////////////////////////////////////////////////
                                        $con_id = $this->User->find('all', array('conditions' => array('User.EmpId' =>$userId)));
                                        $this->User->id = $con_id[0]['User']['id'];    
                                        //if($this->User->save($this->request->data)){

                                        if($this->request->data['users']['New Password'] !="" /*&& $pwd_plag==1*/){
                                            $this->User->saveField('password',$this->request->data['users']['New Password']);
                                        }//else{$this->Session->setFlash(__('Password confirmation is wrong.'));break;} 
                                        if($this->request->data['users']['Email'] !=""){
                                            $this->User->saveField('email',$this->request->data['users']['Email']);
                                        }
                                        $x = '../profile_pictures/'.$file['name'];
                                        $this->User->saveField('pro_picture',$x);
                                        $this->Session->setFlash(__('Your Changes has been saved.'));
                                        $this->redirect(array('action' => 'home'));
                                        //}else{
                                        // $this->Session->setFlash(__('Unable to save your Conformation.'));
                                        //}                    
                                    }
                                    //}  else {
                                      //  $this->Session->setFlash(__('Password confirmation is wrong'));
                                    //}
                                }else{$this->Session->setFlash(__('Password confirmation is wrong'));}
                            }else{$this->Session->setFlash(__('Password size must be more than 8 characters'));}
                        }else{$this->Session->setFlash(__("Your Entered Current Password not matched with Existing Password.Pleace try again."));}
                    }else{
                        if(empty($this->data['users']['pro_picture']['name'])){
                            $con_id = $this->User->find('all', array('conditions' => array('User.EmpId' =>$userId)));
                            $this->User->id = $con_id[0]['User']['id'];      

                            if($this->request->data['users']['New Password'] !="" /*&& $pwd_plag==1*/){
                                $normal_plag1=1;
                                $this->User->saveField('password',$this->request->data['users']['New Password']);
                            }//else{$this->Session->setFlash(__('Password confirmation is wrong.'));break;}
                            if($this->request->data['users']['Email'] !=""){
                                $normal_plag1=1;
                                $this->User->saveField('email',$this->request->data['users']['Email']);
                            }
                            if($normal_plag1==1){
                                $this->Session->setFlash(__('Your Changes has been saved.'));
                            }else{$this->Session->setFlash(__('Your have not any Changes.'));}
                        }           
                        if(!empty($this->data['users']['pro_picture']['name'])){
                            $file=$this->request->data['users']['pro_picture'];
                            $ary_ext=array('jpg','jpeg','gif','png'); //array of allowed extensions
                            $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                            if(in_array($ext, $ary_ext)){
                                move_uploaded_file($file['tmp_name'], WWW_ROOT . 'profile_pictures/' . $file['name'] );
                                $this->request->data['users']['pro_picture'] = $file['name'];
                            }
                            /////////////////////////////////////////////////
                            $con_id = $this->User->find('all', array('conditions' => array('User.EmpId' =>$userId)));
                            $this->User->id = $con_id[0]['User']['id'];

                            if($this->request->data['users']['New Password'] !="" /*&& $pwd_plag==1*/){
                                $this->User->saveField('password',$this->request->data['users']['New Password']);
                            }//else{$this->Session->setFlash(__('Password confirmation is wrong.'));break;} 
                            if($this->request->data['users']['Email'] !=""){
                                $this->User->saveField('email',$this->request->data['users']['Email']);
                            }
                            $x = '../profile_pictures/'.$file['name'];
                            $this->User->saveField('pro_picture',$x);
                            $this->Session->setFlash(__('Your Changes has been saved.'));
                            $this->redirect(array('action' => 'home'));
                        }
                    }
                }
            }else{ 
                $this->Session->setFlash('Human Data Validation Failure');
            }
        }    
    }


/**public function email_profileedit() {
    
     debug($this->request->data);
               exit();
                
    
    if(!empty($this->request->data)){
        if(!isset($this->Captcha)){ //if Component was not loaded throug $components array()
            $this->Captcha = $this->Components->load('Captcha'); //load it
	}
	$this->User->setCaptcha($this->Captcha->getVerCode()); //getting from component and passing to model to make proper validation check
	$this->User->set($this->request->data);
	if($this->User->validates()){ //as usual data save call
				
            $userId = $this->Auth->user('EmpId');       
        
            if($this->request->is('post') || $this->request->is('put')){
               debug($this->request->data);
               exit();
                
                $ss = $this->params['pass'];     
                if($ss[0]<= 0){
                    $this->Session->setFlash(__('Can not Update'));            
                    $this->redirect(array('action' => 'email_profileedit'));
                }else{
                    $decrypted = $ss[0] ^  18544332;    
                    $this->User->id = $decrypted ;
                     $con_id = $this->Temp_user->find('all', array('conditions' => array('Temp_user.confirmation_id' => $ss[0])));
                 
                if(!empty($con_id) && $this->User->save($this->request->data)){
                    $this->Temp_user->delete($con_id[0]['Temp_user']['id']);
                    $this->Session->setFlash(__('Your Conformation has been saved.'));
                    $this->redirect(array('action' => 'email_profileedit'));
                }else{
                    $this->Session->setFlash(__('Unable to save your Conformation.'));
                }
                
                }//else
            }
                           
            //$this->Session->setFlash('Data Validation Success');
	}else{ //or
        	$this->Session->setFlash('Data Validation Failure');
	}
    }
}**/

    public function logout() {
        $this->Session->destroy('User');
        $this->redirect($this->Auth->logout());
        //$this->Session->destroy();
    }
    
        function read()  
    {  
        App::import("Vendor","parsecsv");  
      
        $csv = new parseCSV();  
        $csv->auto('C:\Users\HPpc1\Desktop\LeaveRecord.csv');  
      
            $x = 1;
        foreach ($csv->data  as $row)  
        {  
             $this->Holiday->id = $x;
            $dte  = $row['date'];
$dt   = new DateTime();
$date = $dt->createFromFormat('d/m/Y', $dte);
$ukDate = $date->format('Y-m-d');
           $this->Holiday->saveField('date',$ukDate);
           $this->Holiday->saveField('time',$row['time']);
           $this->Holiday->saveField('name',$row['name']);
           $x = $x + 1;
        } 
        
        //debug($csv->data);
    }  
    public function email_notify() {
        $userId = $this->Auth->user('EmpId');
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        $u_id = $this->User->find('all', array('conditions' => array('User.EmpId' =>$userId)));
         
        if($this->request->is('post')){
            if($this->User->id = $u_id[0]['User']['id']){                
                $x=($this->request->data['notify']);
                $this->User->saveField('notify',$x);
                $this->Session->setFlash(__('Your Configuration has been saved.'));
            }else
                $this->Session->setFlash(__("Your Configuration can't be saved."));
        }
        
        $notify_de= $this->User->find('all',array('conditions' => array('User.EmpId'=>$userId)));
        $notify_states=$notify_de[0]['User']['notify'];
        $this->set('noti',$notify_states);
    }   
    
    public function  email_profileedit(){
       $this->autoLayout = false;
                $this->autoRender = false;
    $fd = $this->request->data('fd');
    $td = $this->request->data('td');
   

$diff = abs(strtotime($td) - strtotime($fd));

//$years = floor($diff / (365*60*60*24));
//$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)) + 1;

$days = floor($diff/(60*60*24))+1;

    //$events = $this->events->find('all');
    
    
    if($fd == $td){
        $events = $this->Event->find('first',array('conditions' =>array('Event.start' => $fd) ));
        if(!empty($events))
            $days--;
        
    }
    else{
        
        $events = $this->Event->find('all',array('conditions' =>array('Event.start >=' => $fd, 'Event.start <=' => $td) ));
        $holidays = count($events);
        $days = $days - $holidays;
        
    }
    
    $fromDateObject = new DateTime($fd);
    $toDateObject   = new DateTime($td);
    $numberOfWeekendDays = 0;
    for ($date = clone $fromDateObject; $date <= $toDateObject; $date){
        if($date->format('l') === 'Saturday' || $date->format('l') === 'Sunday')
            $numberOfWeekendDays++;
        
        $date->add(new DateInterval('P1D'));
    }
    $days -= $numberOfWeekendDays;
    
return $days;
        
}

public function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

public function forget_password(){
    if ($this->request->is('post')){
        $person = $this->User->find('first',array('conditions' =>array('User.username' => $this->request->data['User']['username']) ));

        if(!$person){
            $this->Session->setFlash('Invalid username'); 
        }else{
            $this->User->id = $person['User']['id'];
            
            $index = substr(md5(microtime()),rand(0,26),6);
            $date = substr(md5(microtime()),rand(0,26),26);
            
            $this->User->saveField('password', $index); 
                        
            $recipientsEmails = array();
            $recipientsEmails[] = $person['User']['email'];
            $emailSubject = "Password Recovery";
            $message = "Please temporaly login with this code ".$index;
            
            MailUtil::sendMail($recipientsEmails, $message, $emailSubject);
            
            $this->Session->setFlash('Temporary password is sent to your email'); 
            
            $this->redirect(array('action' => 'login')); 
        }
    }
}
    
 public function login2($id1){
     
//     $id = $this->encrypt_decrypt('decrypt', $id1);
     if(isset($_SESSION['rand'])){
     if ($this->request->is('post')){
         $this->User->id = $id;
         $code = $this->request->data['User']['pwd'];
         $person = $this->User->find('first',array('conditions' =>array('User.pwd' => $code) ));
         
         if(!$person){
         //$this->User->saveField('pwd',$index); 
          $this->Session->setFlash('Invalid security code'); 
         }else{
            $this->User->saveField('pwd',"1111111"); 
            $date = substr(md5(microtime()),rand(0,26),26);
            $_SESSION['change'] = $date;
            unset($_SESSION['rand']);
            //$this->Session->setFlash('Temporary password is sent to your email'); 
            $id2 = $this->encrypt_decrypt('encrypt', $person['User']['id']);
        $this->redirect(array('action' => 'change_pwd',$id2)); 
         }   
         
     }    
     }  else {
         $this->redirect(array('action' => 'forget_password'));
     }
 }   
 
 public function change_pwd($id2){
     $id = $this->encrypt_decrypt('decrypt', $id2);
     //debug($this->params['pass'][0]);
     
     if(isset($_SESSION['change'])){
          if ($this->request->is('post')){
         $this->User->id = $id;
         $this->User->saveField('password',$this->request->data['User']['password']); 
         $this->Session->setFlash('Password has been changed'); 
          unset($_SESSION['change']);
           $this->redirect(array('action' => 'login')); 
     }
     }else{
         
            //$this->Session->setFlash('Temporary password is sent to your email'); 
        $this->redirect(array('action' => 'forget_password')); 
     }
     
 }
 
    public function Join_leave_balance(){
        $all_user = $this->User->find('all');

        foreach($all_user as $ui){
            $userId = $ui['User']['id'];
            $join_date=$ui['User']['join_date']; 
            $today = date("Y-m-d");
            $today_year=date("y", strtotime($today));
            $today_month=date("m", strtotime($today));
            $diff = abs(strtotime($today) - strtotime($join_date));
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            $qtr=0;

            $join_month=date("m", strtotime($join_date));
            $join_year=date("y", strtotime($join_date));

            $this->User->id = $userId;

            //-------------annual leave update----------//
            //--defin quotar--//
            if(1<=$join_month && $join_month<=3){
                $qtr = 1;
            }
            elseif (4<=$join_month && $join_month<=6){
                $qtr = 2;
            }
            elseif(7<=$join_month && $join_month<=9){
                $qtr = 3;
            }
            else{
                $qtr = 4;
            }
            //--end---defin quotar--//
            
            /* Calculate annual Leave */
            $noOfAnnualLeaves = 0;
            if ($join_date != null) {
                $temp_joinInMonths = $join_year * 12 + $join_month;
                $temp_todayInMonths = $today_year * 12 + $today_month;
                $temp_diffInMonth = $temp_todayInMonths - $temp_joinInMonths;
                
                if ($temp_diffInMonth < 6) {
                    // Do nothing.
                } else {
                    $temp_endProbationInMonth = $temp_joinInMonths + 6;
                    $temp_endProbationYear =  $temp_endProbationInMonth / 12;

                    if (intval($today_year) === intval($join_year)) {
                        // Do nothing.
                    } else if (intval($today_year) === (intval($join_year)+1)) {
                        switch ($qtr) {
                            case 1:
                                $noOfAnnualLeaves = 14;
                                break;
                            case 2:
                                $noOfAnnualLeaves = 10;
                                break;
                            case 3:
                                $noOfAnnualLeaves = 7;
                                break;
                            case 4:
                                $noOfAnnualLeaves = 4;
                                break;                            
                            default:
                                $noOfAnnualLeaves = 0;
                                break;
                        }
                    } else if (intval($today_year) >= (intval($join_year)+2)) {
                        $noOfAnnualLeaves = 14;
                    } else {
                        // Do nothing.
                    }
                }                 
            } else {
                // Do nothing.
            }
             $this->User->saveField('nof_ann_lv', $noOfAnnualLeaves);


           //-------------casual leave update----------//
            if($join_date != null){
                /* Calculating casual leaves */
                $temp_joinInMonths = $join_year * 12 + $join_month;
                $temp_todayInMonths = $today_year * 12 + $today_month;
                $temp_diffInMonth = $temp_todayInMonths - $temp_joinInMonths;
                $noOfCasualLeaves = 0;

                if ($temp_diffInMonth < 1) {    // 1st Mon
                    $noOfCasualLeaves = 0;  // No leaves for 1st Month from join date. 
                } elseif ($temp_diffInMonth < 7) {  // 2nd, 3rd Month
                    $noOfCasualLeaves = floatval(0.5) * floatval($temp_diffInMonth);
                } else {
                    $temp_endProbationInMonth = $temp_joinInMonths + 6;
                    $temp_endProbationYear =  $temp_endProbationInMonth / 12;

                    if (intval($today_year) === intval($temp_endProbationYear)) {
                        $temp_curYearInMonth = $today_year * 12 + 12;
                        $temp_remainMonthForCurYear = $temp_curYearInMonth - $temp_endProbationInMonth;

                        $ex_cas = (floatval(7)/floatval(12))*(float)$temp_remainMonthForCurYear;
                        // Rouding to half day.
                        $ex_cas = floatval(3) + (float)$ex_cas;
                        $temp_remainder = (floatval($ex_cas) * 10) % floatval(10);

                        if ((float)$temp_remainder < 2.5 || (float)$temp_remainder >= 7.5) {
                            $ex_cas = round($ex_cas, 0);
                        } else {
                            $ex_cas = intval($ex_cas) + floatval(0.5);
                        }

                        if ($ex_cas >= 7) {
                            $ex_cas = 7;
                        } else {
                            // Do nothing.
                        }

                        $noOfCasualLeaves = $ex_cas;
                    } else {
                        $noOfCasualLeaves = 7;
                    }
                }
                $this->User->saveField('nof_cas_lv', $noOfCasualLeaves);
            } else {
            	$this->User->saveField('nof_cas_lv',0);
            }
            
            if ($ui['User']['leaves_last_updated'] && $ui['User']['nof_liv_lv']) {
	            if (date("y", strtotime($ui['User']['leaves_last_updated'])) < $today_year) {
	            	$this->User->saveField('nof_liv_lv',0);
	            }
            } else {
            	$this->User->saveField('nof_liv_lv', 0);
            }
                        
            $this->User->saveField('leaves_last_updated', date('Y-m-d'));
        }//end for each
    }
    
    public function checkIsUnique(){
        $this->autoLayout = false;
                $this->autoRender = false;
    $eid = $this->request->data('eid');
    $un = $this->request->data('un');
       
    
    $exist_id = $this->User->find('first',array('conditions' => array('User.EmpId'=>$eid)));
    $exist_uname = $this->User->find('first',array('conditions' => array('User.username'=>$un)));
    
   
    
    $e_eid = 0;
    $e_un = 0;
    
    if($exist_id)
        
        $e_eid = 1;
    
    if($exist_uname)
        $e_un  = 1;
    
    return $e_eid.','.$e_un;
}

    public function getUsersByProjects() {
        $this->autoRender = false;
        $this->layout = FALSE;
        $this->render(false);
//        debug($this->request);
        $projectArr = null; 
        if ($this->request->query) {
            $projectArr = $this->request->query['projectSelected'];
        }
        $employeeList = array();
//        debug($projectArr);
        if ($projectArr) {
            $employeeList = $this->User->find('all', 
                    array(
                        'fields'    => array('User.*'),
//                        'fields'    => array('Surname_RestName'),
                        'joins'     => array(
                            array(
                                'table'      => 'emp_projects',
                                'alias'      => 'emp',
                                'type'       => 'INNER',
                                'conditions' => array(
                                    'emp.Eid = User.EmpId'
                                ) 
                            )
                        ),
                        'conditions' => array(
                            'emp.Pid' => $projectArr
                        ),
                        'order'     => array('Surname_RestName'),  //newly added   
                    ));
        } else {
            $employeeList = $this->User->find('all', array('User.role NOT IN ' => array('CEO')));            
        }
        echo json_encode($employeeList);
        exit();
    }
    
    public function updateLeaveCount() {
        $this->autoLayout = false;
        $this->autoRender = false;
        $this->Join_leave_balance();
    }
}
?> 
