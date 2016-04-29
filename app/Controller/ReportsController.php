<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReportsController extends AppController {
    
    var $helpers = array('Html', 'Form');
    public $components = array('Paginator','Session','Search.Prg');
    var $uses = array('User');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('LeaveReportTable','index','exportLeaveRecord', 'updateLeaveCount');
    }
    
    public $paginate = array(
        'limit' => 6,
//        'order' => array(
//            'Post.title' => 'asc'
//        )
    );
    
    public function LeaveReportTable() {
	$this->layout='default';
        
        $this->set('title_for_layout', 'Leave Report');
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
        $this->loadModel('User');
        $this->loadModel('leave_record');
        $this->loadModel('Work_from_homes');
        
        $allUsers = $this->User->find('all', array(
            'conditions' => array(
                'User.role !=' => 'CEO'             //exclude CEO
            ),
            'group'  => array('User.EmpId'),
        ));                                   
        
        $allEmployeeLeavesInfo = array();
        
        foreach ($allUsers as $key => $value1) {
        	if (isset($value1)) {
	            $empId = $value1['User']['EmpId'];
	            $allEmployeeLeavesInfo[$empId] = array(
	                'Eid'           => $empId,
	                'EmployeeName'  => $value1['User']['EmpName'],
	                'casual'        => array(
	                    'entitled'      => $value1['User']['nof_cas_lv'],
	                    'consumed'      => 0,
	                    'remaining'     => $value1['User']['nof_cas_lv'],  //at the initial stage remainin number = entitled
	                ),
	                'annual'        => array(
	                    'entitled'      => $value1['User']['nof_ann_lv'],
	                    'consumed'      => 0,
	                    'remaining'     => $value1['User']['nof_ann_lv'], //at the initial stage remainin number = entitled
	                ),
	                'sick'        => array(
	                    'entitled'      => (isset($value1['User']['nof_sick_lv'])? $value1['User']['nof_sick_lv'] : 0),
	                    'consumed'      => 0,
	                    'remaining'     => (isset($value1['User']['nof_sick_lv'])? $value1['User']['nof_sick_lv'] : 0), //at the initial stage remainin number = entitled
	                ),
	                'nopay'       => array(
	                    'entitled'      => 0,
	                    'consumed'      => 0,
	                    'remaining'     => 0,
	                ),
					'live'      => array(
	            		'entitled'      => (isset($value1['User']['nof_liv_lv'])? $value1['User']['nof_liv_lv'] : 0),
	            		'consumed'      => 0,
	            		'remaining'     => (isset($value1['User']['nof_liv_lv'])? $value1['User']['nof_liv_lv'] : 0) //at the initial stage remainin number = entitled
					),
	            	'wofk_from_home' => 0,
	            );
        	}
        }
              
        $startingDate = date("Y-01-01");
        $endingDate   = date("Y-12-31");  
        
        $allLeaves = $this->leave_record->find('all', array(
            'fields' => array('SUM(real_days) As totalRealLeaves', 'leave_record.Leave_Type', 'leave_record.Eid'),
            'conditions' => array(
                'From_Date >='  => $startingDate,
                'To_Date <='    => $endingDate,
                'Leave_states'  => 'accepted',
                'leave_record.Leave_Type' => array('casual', 'annual', 'sick', 'nopay', 'live'),
            ),
            'group'  => array('leave_record.Eid', 'leave_record.Leave_Type'),
        ));
        
        $allWorkFromHomes = $this->Work_from_homes->find('all', array(
            'fields' => array('Work_from_homes.Eid', 'SUM(real_days) as totalRealWorkFromHomes'),
            'conditions' => array(
                'From_Date >='     => $startingDate,
                'To_Date <='       => $endingDate,
                'wfh_states'    => 'accepted',
            ),
            'group'  => array('Work_from_homes.Eid'),
        ));
        
        foreach ($allLeaves as $key => $value) {
            $empId = $value['leave_record']['Eid'];
            $leaveType = $value['leave_record']['Leave_Type'];
            $totalRealLeaves = $value['0']['totalRealLeaves'];
            if ($leaveType == NULL || $empId == NULL) {
                continue;
            }
            $totalValidLeaveCount = NULL;
            if (isset($allEmployeeLeavesInfo[$empId])) {
            	$totalValidLeaveCount = $allEmployeeLeavesInfo[$empId][$leaveType]['entitled'];
            }
            
            $totalRemaining = 0;
            if ($totalValidLeaveCount != NULL) {
                $totalRemaining = $totalValidLeaveCount - $totalRealLeaves;
            } 
            
            $allEmployeeLeavesInfo[$empId][$leaveType]['consumed'] = $totalRealLeaves;
            $allEmployeeLeavesInfo[$empId][$leaveType]['remaining'] = $totalRemaining;
        }
        
        foreach ($allWorkFromHomes as $key => $value) {
            $empId = $value['Work_from_homes']['Eid'];
            $totalRealWorkFromHomes = $value['0']['totalRealWorkFromHomes'];
            if ($empId == NULL) {
                continue;
            }
            $allEmployeeLeavesInfo[$empId]['wofk_from_home'] = $totalRealWorkFromHomes ; 
        }
        
        $this->set('allEmployeeLeavesInfo', $allEmployeeLeavesInfo);
        
        return $allEmployeeLeavesInfo;
    }
    
    public function exportLeaveRecord() {
        
        $this->layout = null;
        $this->autoLayout = false;
       
        $toSaveArray = $this->LeaveReportTable();
        
        $toViewArray = array();
        
        foreach ($toSaveArray as $key => $value) {
            $toViewArray[$key]['Eid']         = $value['Eid'];
            $toViewArray[$key]['EmployeeName']= $value['EmployeeName'];
            $toViewArray[$key]['cas_enti']    = $value['casual']['entitled'];
            $toViewArray[$key]['cas_con']     = $value['casual']['consumed'];
            $toViewArray[$key]['cas_remain']  = $value['casual']['remaining'];
            $toViewArray[$key]['ann_enti']    = $value['annual']['entitled'];
            $toViewArray[$key]['ann_con']     = $value['annual']['consumed'];
            $toViewArray[$key]['ann_remain']  = $value['annual']['remaining'];
            $toViewArray[$key]['sick_enti']   = $value['sick']['entitled'];
            $toViewArray[$key]['sick_con']    = $value['sick']['consumed'];
            $toViewArray[$key]['sick_remain'] = $value['sick']['remaining'];
            $toViewArray[$key]['no_pay']      = $value['nopay']['consumed'];
            $toViewArray[$key]['wfh']         = $value['wofk_from_home'];
            
        }
        $this->set('orders', $toViewArray);
        
 		return;
    }

    
    public function updateLeaveCount() {
    	$this->autoRender = FALSE;
    	$this->layout = FALSE;
    	$response = array();
    	
    	if (!$this->request->query) {
    		$response['success'] = FALSE;
    		$response['msg'] = 'Could get the data';
    		echo json_encode($response);
    		exit();
    	}    	
    	
    	$empid = $this->request->query['empid'];
    	$leaveType = $this->request->query['leaveType'];
    	$leaveCount = $this->request->query['leaveCount'];
    	if (is_numeric($leaveCount) && $leaveCount >= 0) {
    		$this->loadModel('User');
			$person = $this->User->find('first',array('conditions' =>array('User.EmpId' => $empid )));
			$this->User->id = $person['User']['id'];
			$this->User->saveField('nof_liv_lv', $leaveCount);
    		
			$response['success'] = TRUE;
    		echo json_encode($response);
    	} else {
    		$response['success'] = FALSE;
    		$response['msg'] = 'Leave count is invalid.';
    		echo json_encode($response);
   		}
    	exit();
    }    
    
}