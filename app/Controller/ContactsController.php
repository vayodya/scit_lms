<?php

class ContactsController extends AppController {

    public $helperss = array('Html', 'Form', 'Csv');
    var $uses = array('leave_record', 'User', 'emp_project', 'Project', 'temp_detail', 'events', 'Work_from_homes');
    var $components = array('RequestHandler');
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('download_adminview_leave_filter','download_adminview_wfh_filter');
    }
    
    public function contact() {
        
    }

    public function download() {
        $userId = $this->Auth->user('EmpId');
        $this->set('orders', $this->leave_record->find('all',array('conditions' => array('leave_record.Eid' => $userId),'fields'=>array('Eid','EmpName','From_Date','To_Date','Leave_Type','Leave_comment','Leave_Time','Leave_states','real_days','accept_id')))); 
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    }
    public function download_wfh() {
        $userId = $this->Auth->user('EmpId');
        $this->set('orders2', $this->Work_from_homes->find('all', array('conditions' => array('Work_from_homes.Eid' => $userId),'fields'=>array('Eid','EmpName','From_Date','To_Date','wfh_comment','wfh_Time','wfh_states','real_days','accept_id'))));
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    }
    public function download_view_user() {
        $this->set('orders3', $this->User->find('all',array('fields' => array('EmpId','EmpName','username','email','role','notify','nof_sick_lv','nof_ann_lv','nof_cas_lv','nof_liv_lv'))));
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    
        $headers = array('Employee ID',
                    'Employee Name',
                    'Email',
                    'Role',
                    'Email notification',
                    'Nof Sick',
                    'Nof Casual',
                    'Nof Annual',
                    'Nof Lieu'
                 
                    /*'EmpId' => 'Employee ID',
                    'EmpName' => 'Employee Name',
                    'email' => 'Email',
                    'role' => 'Role',
                    'notify' => 'Email notification',
                    'nof_sick_lv' => 'Nof Sick',
                    'nof_cas_lv' => 'Nof Casual',
                    'nof_ann_lv' => 'Nof Annual',
                    'nof_liv_lv' => 'Nof Lieu'*/
                
            ); 
        //debug($headers[0]);exit;
         $this->set('or',$headers);
    }
    /*public function download_view_user() {
        $this->set('orders3', $this->User->find('all'));
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    }*/

    public function download_adminview_leave() {
        $as=$this->set('orders4', $this->leave_record->find('all',array('fields' => array('Eid','EmpName','Leave_Type','From_Date','To_Date','Leave_comment','Leave_Time','Leave_states','real_days','accept_id'))));
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    }
    public function download_adminview_wfh() {
        $this->set('orders5', $this->Work_from_homes->find('all',array('fields' => array('Eid','EmpName','From_Date','To_Date','wfh_comment','wfh_Time','wfh_states','real_days','accept_id'))));
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    }
    public function download_adminview_leave_filter($f_date,$t_date,$status){
        //debug($f_date);debug($t_date);exit;
        if($status == 'accepted'){
                $search = $this->leave_record->find('all',array('fields' => array('Eid','EmpName','Leave_Type','From_Date','To_Date','Leave_comment','Leave_Time','Leave_states','real_days','accept_id'),'conditions' => array(
                         'AND' => array('leave_record.Leave_states LIKE' => "accepted",
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $t_date,
				'leave_record.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $f_date,
				'leave_record.To_Date <=' => $t_date))
			)))));
                }else if($status == 'rejected'){
                    $search = $this->leave_record->find('all',array('fields' => array('Eid','EmpName','Leave_Type','From_Date','To_Date','Leave_comment','Leave_Time','Leave_states','real_days','accept_id'),'conditions' => array(
                         'AND' => array('leave_record.Leave_states LIKE' => "rejected",
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $t_date,
				'leave_record.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $f_date,
				'leave_record.To_Date <=' => $t_date))
			)))));
                    
                }else if($status == 'pending'){
                    $search = $this->leave_record->find('all',array('fields' => array('Eid','EmpName','Leave_Type','From_Date','To_Date','Leave_comment','Leave_Time','Leave_states','real_days','accept_id'),'conditions' => array(
                         'AND' => array('leave_record.Leave_states LIKE' => "pending",
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $t_date,
				'leave_record.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $f_date,
				'leave_record.To_Date <=' => $t_date))
			)))));
                    
                }else{
                    $search = $this->leave_record->find('all',array('fields' => array('Eid','EmpName','Leave_Type','From_Date','To_Date','Leave_comment','Leave_Time','Leave_states','real_days','accept_id'),'conditions' => array(
                         
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $t_date,
				'leave_record.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $f_date,
				'leave_record.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $f_date,
				'leave_record.To_Date <=' => $t_date))
			))));
                    
                }
        
        //$q=$this->leave_record->find('all',array('fields' => array('Eid','EmpName','Leave_Type','From_Date','To_Date','Leave_comment','Leave_Time','Leave_states','real_days','accept_id'),'conditions' => array('AND'=>array('leave_record.From_Date >='=>$f_date,'leave_record.To_Date <='=>$t_date))));        
        $as=$this->set('orders6',$search);
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    }
    public function download_adminview_wfh_filter($f_date,$t_date,$status){
        if($status == 'accepted'){
                $search = $this->Work_from_homes->find('all',array('conditions' => array(
                         'AND' => array('Work_from_homes.wfh_states LIKE' => "accepted",
			'OR'  => array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $t_date,
				'Work_from_homes.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'Work_from_homes.From_Date >=' => $f_date,
				'Work_from_homes.To_Date <=' => $t_date))
			)))));
                }else if($status == 'rejected'){
                    $search = $this->Work_from_homes->find('all',array('conditions' => array(
                         'AND' => array('Work_from_homes.wfh_states LIKE' => "rejected",
			'OR'  => array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $t_date,
				'Work_from_homes.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'Work_from_homes.From_Date >=' => $f_date,
				'Work_from_homes.To_Date <=' => $t_date))
			)))));
                    
                }else if($status == 'pending'){
                    $search = $this->Work_from_homes->find('all',array('conditions' => array(
                         'AND' => array('Work_from_homes.wfh_states LIKE' => "pending",
			'OR'  => array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $t_date,
				'Work_from_homes.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'Work_from_homes.From_Date >=' => $f_date,
				'Work_from_homes.To_Date <=' => $t_date))
			)))));
                    
                }else{
                    $search = $this->Work_from_homes->find('all',array('conditions' => array(
                         
			'OR'  => array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $t_date,
				'Work_from_homes.To_Date >=' => $t_date),
                        
                         array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $f_date)),
                             
                        array( 'AND' => array(
				'Work_from_homes.From_Date <=' => $f_date,
				'Work_from_homes.To_Date >=' => $t_date)),
                             
                         array( 'AND' => array(
				'Work_from_homes.From_Date >=' => $f_date,
				'Work_from_homes.To_Date <=' => $t_date))
			))));
                    
                }
        
        //$q=$this->Work_from_homes->find('all',array('fields' => array('Eid','EmpName','From_Date','To_Date','wfh_comment','wfh_Time','wfh_states','real_days','accept_id'),'conditions' => array('AND'=>array('Work_from_homes.From_Date >='=>$f_date,'Work_from_homes.To_Date <='=>$t_date))));        
        $as=$this->set('orders7',$search);
        $this->layout = null;
        $this->autoLayout = false;
        Configure::write('debug', 2);
    }
    
}

?>

