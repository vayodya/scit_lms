<?php
App::uses('MailUtil', 'Lib');
App::uses('LeaveUtil', 'Lib');

class LeaveRecordsController extends AppController{
    public $helperss = array('Html', 'Form');
    public $components = array('Session','Search.Prg');
    var $uses = array('leave_record', 'User', 'emp_project', 'Project', 
        'temp_detail', 'events', 'structure', 'email_details', 'Event',
        'Work_from_homes');
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
                $this->Auth->allow('view_leave_report', 'leave_balance',
                        'accept_leave_cancel', 'settings', 'applyLeave',
                        'addLeaveForOther');
	}
    
      public function sendMailForApproval($leave_id ) {
        $records = $this->leave_record->find('all',array('conditions' => array('leave_record.id'=>$leave_id)));
        $leaveOwner = $this->User->find('first',array('conditions' => array('User.EmpId'=>$this->Auth->user('EmpId'))));
        
        $leaveType_temp = LeaveUtil::getLeaveTypeName($records[0]['leave_record']['Leave_Type']);
        $leaveType_temp = ($leaveType_temp === NULL) ? '' : $leaveType_temp;
        
        $leaveTime_temp = LeaveUtil::getLeaveTimeName($records[0]['leave_record']['Leave_Time']);
        $leaveTime_temp = ($leaveTime_temp === NULL) ? '' : $leaveTime_temp;
            
        $emailSubject = 'Leave Request' . ' : ' . $leaveOwner['User']['EmpName'];        
        $message = $leaveOwner['User']['EmpName'].' applied for leave.'
                .'\n\nFrom Date : '.$records[0]['leave_record']['From_Date']
                .'\nTo Date : '.$records[0]['leave_record']['To_Date']
                .'\n'.'Leave Type : '.$leaveType_temp
                .'\n'.'Leave Time : '.$leaveTime_temp
                .'\n'.'Leave Comment : '.$records[0]['leave_record']['Leave_comment'];

        ///find mail recipients
        $userId = $this->Auth->user('EmpId');
        $role = $this->User->find('first',array('conditions' => array('User.EmpId'=>$userId)));
        
        $condition_roleArr = array(
                array('User.role'  => array('CEO', 'pm'))
            );
        
        if ($leaveOwner['User']['role'] !== 'CEO' && $leaveOwner['User']['role'] !== 'pm') {
            $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                    'conditions' => array('emp_project.Eid' => $this->Auth->user('EmpId'))));
            
            if ($projects) {
                $projectsIdArr = array();
                foreach ($projects as $key => $value) {
                    $projectsIdArr[] = $value['emp_project']['Pid'];
                }
                $condition_roleArr[] = array('AND' => array('User.role' => 'tl', 'emp.Pid'    => $projectsIdArr));
            }
        }        
        
        $emailRecieversUserInfo = $this->User->find('all', array(
            'joins' => array(
                array(
                    'table' => 'emp_projects',
                    'alias' => 'emp',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'User.EmpId = emp.Eid'
                    )
                )
            ),
            'conditions' => array(
                'OR'    => $condition_roleArr,
                'AND'   => array('User.EmpId != ' => $this->Auth->user('EmpId'))
            ),
            'fields' => array('DISTINCT User.EmpId', 'User.EmpName', 'User.email')
        ));
                
        $recipientsEmails = array();
        if ($emailRecieversUserInfo) {
            foreach ($emailRecieversUserInfo as $key => $value) {
                $recipientsEmails[] = $value['User']['email'];
            }
        }                
        
        MailUtil::sendMail($recipientsEmails, $message, $emailSubject);
        
        $this->redirect(array('action' => 'add'));
    }
    
////////////////////////// Add Leave Record  ///////////////////////////////////////////////////////////
    private function __addLeaveRecord(){
        $errArray = array();
        $loggedUser = $this->Auth->user();

        if(!($this->request->is('post'))){
            $errArray[count($errArray)] = "Inputs are not post data.";
            $this->set("error", $errArray); 
            return null;
        }
        
        $now = new DateTime();
        $currentYearStartDate = new DateTime($now->format("Y").'-01-01'); 
        $currentYearEndDate = new DateTime($now->format("Y").'-12-31');
        
        $loggedUserAllLeaves = $this->leave_record->find('all', array(
                    'fields'     => array(
                        'leave_record.Eid',                             'leave_record.Leave_Type', 
                        'SUM(leave_record.real_days) AS sumOfLeaves'
                    ),
                    'conditions' => array(
                        'leave_record.Eid'           => $loggedUser['EmpId'],
                        'leave_record.Leave_Type'    => $this->request->data['Leave_Type'],
                        'leave_record.From_Date >= ' => $currentYearStartDate->format('Y-m-d'),
                        'leave_record.From_Date <= '   => $currentYearEndDate->format('Y-m-d'),
                        'leave_record.Leave_states !=' => 'rejected' 
                    ),
                    'group'      => array('leave_record.Leave_Type')
                )
            );

        $totalLeavesForLeaveType = 0;
        if ($this->request->data['Leave_Type'] === 'annual') {
            $totalLeavesForLeaveType = $loggedUser['nof_ann_lv'];
        } elseif ($this->request->data['Leave_Type'] === 'casual') {
            $totalLeavesForLeaveType = $loggedUser['nof_cas_lv'];
        } elseif ($this->request->data['Leave_Type'] === 'live') {
        	$totalLeavesForLeaveType = $loggedUser['nof_liv_lv'];
        }
        
        $remaingLeavesForLeaveType = $totalLeavesForLeaveType;
        if (!empty($loggedUserAllLeaves)) {
            $remaingLeavesForLeaveType -= $loggedUserAllLeaves[0][0]['sumOfLeaves'];
        }
        $fromDate = new DateTime($this->request->data['From_Date']);
        $toDate = new DateTime($this->request->data['To_Date']);
                
		if ($currentYearStartDate > $fromDate || $fromDate > $toDate) {
            $errArray[count($errArray)] = "Check your Start and End dates again.";
            $this->set("error", $errArray);
            return null;
		}
        
        if ($fromDate->format("Y") !== $toDate->format("Y")) {
            $errArray[count($errArray)] = "End date must be equal or less than the end date of leave starting year.";
            $this->set("error", $errArray); 
            return null;                                     
        }
        
//        $realDaysOfAppliedLeave = $this->__calculateRealDaysOfLeave($fromDate, $toDate);
        $realDaysOfAppliedLeave = LeaveUtil::calculateRealDaysForLeave(
                $fromDate, $toDate, $this->request->data['Leave_Time']);
        
        if ($realDaysOfAppliedLeave === 0) {
            $errArray[count($errArray)] = "Applied dates are holidays. Please check your leave dates.";
            $this->set("error", $errArray); 
            return null;                         
        }

        if ($realDaysOfAppliedLeave > 7) {
            $errArray[count($errArray)] = "Duration must be less than or equal to 7 days. Please Try Again.";
            $this->set("error", $errArray); 
            return null;             
        }
        
        if ( ($this->request->data['Leave_Type'] === 'casual' 
                    || $this->request->data['Leave_Type'] === 'annual'
                    || $this->request->data['Leave_Type'] === 'live' ) 
                && ($remaingLeavesForLeaveType < $realDaysOfAppliedLeave)) {
            // User has no enough leaves
            $errArray[count($errArray)] = "Sorry!! Your Remaining Leaves Not sufficient. Try again with NoPay..";
            $this->set("error", $errArray); 
            return null; 
        }
        
        /**
         *  Check any leaves withing applying leave period. 
         */
        $isLeaveTaken = $this->__checkAnyLeaveWithinPeriod($this->request->data, 
                $loggedUser, $fromDate, $toDate, $realDaysOfAppliedLeave);
        if ($isLeaveTaken) {
            // User has already taken leave.
            $errArray[count($errArray)] = "You already applied leaves within these days. Please Try Again.";
            $this->set("error", $errArray); 
            return null;
        }
        
        $isWFH = $this->__checkAnyWorkFromHomeWithingPeriod(
                $this->request->data, $loggedUser, $fromDate, $toDate, $realDaysOfAppliedLeave);
        if ($isWFH) {
            // User has already taken Work From Home.
            $errArray[count($errArray)] = "Your leave over lap with existing Work From Home.";
            $this->set("error", $errArray); 
            return null;
        }
        
        // Saving record.
        $newLeave['leave_record'] = array(
            'Leave_Type'    => $this->request->data['Leave_Type'],
            'From_Date'     => $this->request->data['From_Date'],
            'To_Date'       => $this->request->data['To_Date'],
            'Eid'           => $loggedUser['EmpId'],
            'Leave_comment' => $this->request->data['Leave_comment'],
            'Leave_Time'    => $this->request->data['Leave_Time'],
            'Leave_states'  => 'pending',
            'real_days'     => $realDaysOfAppliedLeave,
            'accept_id'     => $this->request->data['accept_id'],
            'EmpName'       => $loggedUser['EmpName']
        );

        $leaveSaved = $this->leave_record->save($newLeave['leave_record']);
        if (!($leaveSaved)) {
            $errArray[count($errArray)] = "Could not add the leave.";
            $this->set("error", $errArray); 
            return null;
        }
        return $leaveSaved;
    }
        
    private function __checkAnyLeaveWithinPeriod($requestData, $loggedUser, 
            $fromDate, $toDate, $realDaysOfAppliedLeave) {
        $isLeaveTaken = FALSE;
        
        $withinLeave = $this->leave_record->find('all', array(
                    'conditions'    => array(
                        'leave_record.Eid'              => $loggedUser['EmpId'],
//                        'leave_record.Leave_Type'       => $requestData['Leave_Type'],
                        'leave_record.From_Date <= '    => $requestData['From_Date'],
                        'leave_record.Leave_states != ' => 'rejected',
                        'leave_record.To_Date >= '      => $requestData['From_Date']
                    ),
                    'order'         => array(
                        'leave_record.From_Date' => 'ASC'
                    )
            ));

        if ($withinLeave) {
            foreach ($withinLeave as $key => $value) {
                if ($value['leave_record']['real_days'] >= 1) {                
                    $isLeaveTaken = TRUE;
                    break;    
                } elseif (floatval($value['leave_record']['real_days']) === 0.5 
                        && floatval($realDaysOfAppliedLeave) === floatval(1)) {
                    $isLeaveTaken = TRUE;
                    break;    
                } elseif (floatval($value['leave_record']['real_days']) === 0.5 
                        && floatval($realDaysOfAppliedLeave) === 0.5) {
                    /* Check the half day leaves */
                    if ($value['leave_record']['Leave_Time'] === $requestData['Leave_Time']) {
                        $isLeaveTaken = TRUE;
                        break;    
                    } else {
                        // Do nothing.
                    }
                } else {
                    // Do nothing.
                }
            }
        } else {
            // Do nothing.
        }
        
        if (!($isLeaveTaken)) {
            $prevLeave = $this->leave_record->find('first', array(
                        'conditions'    => array(
                            'leave_record.Eid'              => $loggedUser['EmpId'],
//                            'leave_record.Leave_Type'   => $requestData['Leave_Type'],
                            'leave_record.Leave_states != ' => 'rejected',
                            'leave_record.From_Date < '     => $requestData['From_Date']
                        ),
                        'order'         => array(
                            'leave_record.From_Date' => 'DESC'
                        )
                ));
            if ($prevLeave) {
                $prevLeaveToDate = new DateTime($prevLeave['leave_record']['To_Date']);
                if ($prevLeaveToDate >= $fromDate) {
                    $isLeaveTaken = TRUE;
                } else {
                    // Do nothing.
                }
            } else {
//                $isLeaveTaken = TRUE;
            }
        }
        
        if (!($isLeaveTaken)) {
            $nextLeave = $this->leave_record->find('first', array(
                        'conditions'    => array(
                            'leave_record.Eid'              => $loggedUser['EmpId'],
//                            'leave_record.Leave_Type'   => $requestData['Leave_Type'],
                            'leave_record.Leave_states != ' => 'rejected',
                            'leave_record.To_Date > '       => $requestData['To_Date']
                        ),
                        'order'         => array(
                            'leave_record.From_Date' => 'ASC'
                        )
                ));
            if ($nextLeave) {
                $nextLeaveFromDate = new DateTime($nextLeave['leave_record']['From_Date']);
                if ($toDate >= $nextLeaveFromDate) {
                    $isLeaveTaken = TRUE;
                } else {
                    // Do nothing.
                }
            } else {
                // Do nothing.
            }
        }
        return $isLeaveTaken;
    }
    
    private function __checkAnyWorkFromHomeWithingPeriod($requestData, $loggedUser, $fromDate, $toDate, $realDaysOfAppliedLeave) {
        $isLeaveTaken = FALSE;
        
        $withinLeave = $this->Work_from_homes->find('all', array(
                    'conditions'    => array(
                        'Work_from_homes.Eid'            => $loggedUser['EmpId'],
                        'Work_from_homes.From_Date <= '  => $requestData['From_Date'],
                        'Work_from_homes.wfh_states != ' => 'rejected',
                        'Work_from_homes.To_Date >= '    => $requestData['From_Date']
                    ),
                    'order'         => array(
                        'Work_from_homes.From_Date' => 'ASC'
                    )
            ));
        
        if ($withinLeave) {
            foreach ($withinLeave as $key => $value) {
                if ($value['Work_from_homes']['real_days'] >= 1) {                
                    $isLeaveTaken = TRUE;
                    break;    
                } elseif (floatval($value['Work_from_homes']['real_days']) === 0.5 
                        && floatval($realDaysOfAppliedLeave) === 0.5) {
                    /* Check the half day leaves */
                    if ($value['Work_from_homes']['wfh_Time'] === $requestData['Leave_Time']) {
                        $isLeaveTaken = TRUE;
                        break;    
                    } else {
                        // Do nothing.
                    }
                } else {
                    // Do nothing.
                }
            }
        } else {
            // Do nothing.
        }
        
        if (!($isLeaveTaken)) {
            $prevLeave = $this->Work_from_homes->find('first', array(
                        'conditions'    => array(
                            'Work_from_homes.Eid'            => $loggedUser['EmpId'],
                            'Work_from_homes.wfh_states != ' => 'rejected',
                            'Work_from_homes.From_Date < '   => $requestData['From_Date']
                        ),
                        'order'         => array(
                            'Work_from_homes.From_Date' => 'DESC'
                        )
                ));
            if ($prevLeave) {
                $prevLeaveToDate = new DateTime($prevLeave['Work_from_homes']['To_Date']);
                if ($prevLeaveToDate >= $fromDate) {
                    $isLeaveTaken = TRUE;
                } else {
                    // Do nothing.
                }
            } else {
                // Do nothing.
            }
        }

        if (!($isLeaveTaken)) {
            $nextLeave = $this->Work_from_homes->find('first', array(
                        'conditions'    => array(
                            'Work_from_homes.Eid'            => $loggedUser['EmpId'],
                            'Work_from_homes.wfh_states != ' => 'rejected',
                            'Work_from_homes.To_Date > '     => $requestData['To_Date']
                        ),
                        'order'         => array(
                            'Work_from_homes.From_Date' => 'ASC'
                        )
                ));
            if ($nextLeave) {
                $nextLeaveFromDate = new DateTime($nextLeave['Work_from_homes']['From_Date']);
                if ($toDate >= $nextLeaveFromDate) {
                    $isLeaveTaken = TRUE;
                } else {
                    // Do nothing.
                }
            } else {
                // Do nothing.
            }
        }
        return $isLeaveTaken;
    }
        
    public function add(){
        //$rec=0;
        date_default_timezone_set('Asia/Colombo');
        $this->set('title_for_layout', 'Apply Leave');

       $rec_count=0;
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        $userId = $this->Auth->user('EmpId');
        $all = $this->User->find('all');
        $this->set('users',$all);
        $errArray = array();
         $this->set('error',$errArray);
         
         $errCount = 0;
        
         $id11 = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        $this->set('owner',$id11['User']['id']);
        
        
        $events1 = $this->Event->find('all',array('fields'=> 'Event.start'));
        $today = date("Y-m-d");
        
        $ev = json_encode($events1);
       
        $this->set('holidays',$events1);
        //---leave assing------//
        $leave_no = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        $this->set('no_sick_lv',$leave_no['User']['nof_sick_lv']);
        $this->set('no_ann_lv',$leave_no['User']['nof_ann_lv']);
        $this->set('no_cas_lv',$leave_no['User']['nof_cas_lv']);
        $this->set('no_liv_lv',$leave_no['User']['nof_liv_lv']);
        
        //$notify = $this->User->find('first',array('conditions' => array('User.EmpId'=>$userId)));
        $admin = $this->User->find('all',array('conditions' => array('User.role'=>'admin')));
        $x=$this->emp_project->find('all',array('conditions' => array('emp_project.Eid'=>$userId)));
        for($i = 0; $i<count($x); $i++){                  
            $rec[$i] = $this->emp_project->find('all',array('conditions' => array('emp_project.Pid'=>$x[$i]['emp_project']['Pid'])));
        }
        if(count($x)<=0)
            {$rec=0;}
        
        $now = new DateTime();
        $currentYearStartDate = new DateTime($now->format("Y").'-01-01'); 
        $currentYearEndDate = new DateTime($now->format("Y").'-12-31');
        $userAllLeaves = $this->leave_record->find('all', array(
                    'fields'     => array(
                        'leave_record.Eid',                             'leave_record.Leave_Type', 
                        'SUM(leave_record.real_days) AS sumOfLeaves',   'leave_record.Leave_states'
                    ),
                    'conditions' => array(
                        'leave_record.Eid'           => $userId,
                        'leave_record.From_Date >= ' => $currentYearStartDate->format('Y-m-d'),
                        'leave_record.From_Date <= '   => $currentYearEndDate->format('Y-m-d'),
                        'leave_record.Leave_states !=' => 'rejected' 
                    ),
                    'group'      => array('leave_record.Leave_Type', 'leave_record.Leave_states')
                )
            );
        
        $noOfLeavesByTypeAndStatus = array(
            'annual' => array('pending' => 0, 'accepted' => 0),
            'sick'   => array('pending' => 0, 'accepted' => 0),
            'casual' => array('pending' => 0, 'accepted' => 0),
            'live'   => array('pending' => 0, 'accepted' => 0),
            'nopay'  => array('pending' => 0, 'accepted' => 0)
        );
        if (!empty($userAllLeaves)) {
            foreach ($userAllLeaves as $key => $value) {
                $leaveType_temp = $value['leave_record']['Leave_Type'];
                $leaveStatus_temp = $value['leave_record']['Leave_states'];
                $noOfLeavesByTypeAndStatus[$leaveType_temp][$leaveStatus_temp] = $value[0]['sumOfLeaves'];
            }
        }
        
        $this->set('a1', $noOfLeavesByTypeAndStatus['sick']['accepted']);
        $this->set('aa1', $noOfLeavesByTypeAndStatus['sick']['pending']);
        $this->set('a2', $noOfLeavesByTypeAndStatus['annual']['accepted']);
        $this->set('aa2', $noOfLeavesByTypeAndStatus['annual']['pending']);
        $this->set('a3', $noOfLeavesByTypeAndStatus['casual']['accepted']);
        $this->set('aa3', $noOfLeavesByTypeAndStatus['casual']['pending']);
        $this->set('a4', $noOfLeavesByTypeAndStatus['nopay']['accepted']);
        $this->set('aa4', $noOfLeavesByTypeAndStatus['nopay']['pending']);
        $this->set('a5', $noOfLeavesByTypeAndStatus['live']['accepted']);
        $this->set('aa5', $noOfLeavesByTypeAndStatus['live']['pending']);
        
        if($this->request->is('post')){
            
            $toModel['leave_record'] = $this->request->data;
            $this->leave_record->set($toModel);

            //model validation
            if($this->leave_record->validates()){
                //if data validates
                $leaveSaved = $this->__addLeaveRecord();
                if ($leaveSaved) {
                    $_SESSION['rand'] = 'Your Leave has been sent for approval.';
                    $this->sendMailForApproval($leaveSaved['leave_record']['id']);
                }
            }  else {
                // if data does not validate
                $errors = $this->leave_record->validationErrors;
                $this->Session->setFlash("Validation Error: request cannot be completed.");
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

        $records = $this->leave_record->find('first',array('conditions' => array('leave_record.id'=>$id)));
        
        if ($this->leave_record->delete($id)) {
            //$this->Session->setFlash(__('The post with id: %s has been deleted.', $id));
            $this->Session->setFlash(__('Your Leave Successfuly Canceled.'));
            
            // Send mail notification.
            $name = $this->User->find('first',array('conditions' => array('User.EmpId'=>$this->Auth->user('EmpId'))));

            $leaveType = LeaveUtil::getLeaveTypeName($records['leave_record']['Leave_Type']);
            $leaveType = ($leaveType === NULL) ? '' : $leaveType;

            $leaveTime = LeaveUtil::getLeaveTimeName($records['leave_record']['Leave_Time']);
            $leaveTime = ($leaveTime === NULL) ? '' : $leaveTime;

            $emailSubject = 'Leave Cancellation' . ' : ' . $name['User']['EmpName'];
            $message = $name['User']['EmpName'].' canceled the leave.'
                .'\n\nFrom Date : '.$records['leave_record']['From_Date']
                .'\nTo Date : '.$records['leave_record']['To_Date']
                .'\n'.'Leave Type : '.$leaveType
                .'\n'.'Leave Time : '.$leaveTime
                .'\n'.'Leave Comment : '.$records['leave_record']['Leave_comment'];

            $condition_roleArr = array(
                    array('User.role'  => array('CEO', 'pm'))
                ); 
            if ($name['User']['role'] !== 'CEO' && $name['User']['role'] !== 'pm') {
                $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                        'conditions' => array('emp_project.Eid' => $this->Auth->user('EmpId'))));

                if ($projects) {
                    $projectsIdArr = array();
                    foreach ($projects as $key => $value) {
                        $projectsIdArr[] = $value['emp_project']['Pid'];
                    }
                    $condition_roleArr[] = array('AND' => array('User.role' => 'tl', 'emp.Pid'    => $projectsIdArr));
                }
            }              
            $emailRecieversUserInfo = $this->User->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'emp_projects',
                        'alias' => 'emp',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'User.EmpId = emp.Eid'
                        )
                    )
                ),
                'conditions' => array(
                    'OR'    => $condition_roleArr,
                    'AND'   => array('User.EmpId != ' => $this->Auth->user('EmpId'))
                ),
                'fields' => array('DISTINCT User.EmpId', 'User.EmpName', 'User.email')
            ));
            $recipientsEmails = array();
            if ($emailRecieversUserInfo) {
                foreach ($emailRecieversUserInfo as $key => $value) {
                    $recipientsEmails[] = $value['User']['email'];
                }
            }                

            MailUtil::sendMail($recipientsEmails, $message, $emailSubject);                    
            
            $this->redirect(array('action' => 'leavereport'));
        }
    }
    
    public function view_leave_report(){

        $selectedProjects = array();
        if (!empty($this->request->data['projectName'])) {
            $selectedProjects = $this->request->data['projectName'];
        }
        $this->set('selectedProjects', $selectedProjects);

        //fetching employee Surname_RestName from ( virualField )
        $finalNameList = $this->User->find('list', array(
            "fields"=>array('EmpID','Surname_RestName'),
            "order"=>array('Surname_RestName'),
            
        ));
//        pr($finalNameList);
        $this->set('allemployees', $finalNameList);
                            
        $results = $this->User->find("list", array("fields"=>array('EmpId')));
        $employeeList = $this->User->find('all', array('conditions', array('User.role NOT IN ' => array('CEO'))));
        $this->set('employeeList', $employeeList);
        
        //fetching project list form db
        $this->loadModel('Project');
        $projectList = $this->Project->find('list', array('fields' =>array('Project.Pid', 'Project.pro_name')));
        $this->set('allProjects', $projectList);
        
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);

//        $this->autoRender = false;
        if ($this->request->is('post')) {
            $finalNameList = array();
            if ($selectedProjects) {
                $finalNameList = $this->User->find('list', array(
                    "fields"=>array('EmpID','Surname_RestName'),
                    "order"=>array('Surname_RestName'),
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
                        'emp.Pid' => $selectedProjects
                    )
                ));   
                $this->set('allemployees', $finalNameList);
            }
            
            $empId = $this->request->data['employeeId'];
            
            $condition_leaveReport = array();
            if ($empId !== 'all') {
                $condition_leaveReport['leave_record.Eid'] = $empId;
            } else {
                if ($selectedProjects) {
                    $empId = array();
                    foreach ($finalNameList as $key => $value) {
                        $empId[] = $key;
                    }  
                    $condition_leaveReport['leave_record.Eid'] = $empId;
                }                
            }
            
            if ($this->request->data['status'] != 'all') {
                $condition_leaveReport['leave_record.Leave_states'] = $this->request->data['status'];
            }
            
            if ($this->request->data['From_Date']) {
                $condition_leaveReport['leave_record.To_Date >= '] = $this->request->data['From_Date'];
            }
            
            if ($this->request->data['To_Date']) {
                $condition_leaveReport['leave_record.From_Date <= '] = $this->request->data['To_Date'];
            }
            
//            $this->paginate = array(
//                'conditions'    => array($condition_leaveReport),
//                'order'         => array('leave_record.From_Date' => 'DESC'));
//            $this->set('leaves', $this->paginate('leave_record'));
            $recordsList = $this->leave_record->find('all',
                array(
                    'conditions'    => $condition_leaveReport,
                    'order'         => array('leave_record.From_Date' => 'DESC')
                    )
                );            
            $this->set('recordsList', $recordsList);
        } else {
//            $today = date("Y"."-"."m"."-"."d");
//            $this->set('leaves', $this->paginate());
//            $this->paginate = array(
//                'order' => array('leave_record.From_Date' => 'DESC'));
//            $this->set('leaves', $this->paginate('leave_record'));
            
            $recordsList = $this->leave_record->find('all', array(
                    'order' => array('leave_record.From_Date' => 'DESC')
                ));
            $this->set('recordsList', $recordsList);            
        }
    }
    
   

    public function leavereport(){
        //debug($this->email_details->find('all'));exit;
        $this->set('title_for_layout', 'Leave Report');
         $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
        $userId = $this->Auth->user('EmpId');
              
        $this->Prg->commonProcess();
        $this->paginate = array( 
            'conditions' => array($this->leave_record->parseCriteria($this->passedArgs),'leave_record.Eid ' => $userId),
            'limit'      => 10,
            'order'      => array('leave_record.From_Date' => 'desc'),
        );
                    
        $this->set('leave_records',$this->paginate());
        //debug($this->paginate());
    }
    
   public function view_leave_repo(){
       
       // debug($this->User->find('all'));
       // exit();
        
         $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
        
        $search  = null;

        if (!($this->request->is('post'))) {
            $this->set('search_result',$search);
            $this->set('inc',0);
        }
        
        if($this->request->is('post')){
            $fd = $this->request->data['From_Date'];
            $td = $this->request->data['To_Date'];
            $status = $this->request->data['status'];
            
            $this->set('f_date',$fd);
            $this->set('t_date',$td);
            $this->set('status',$status);
            
            
            
            if($fd > $td){
                 $this->Session->setFlash(__('To Date must be greater than or equal to From Date')); 
                  $this->redirect(array('action' => 'view_leave_report'));
                 
            }else{
                
                if($status == 'accepted'){
                $search = $this->leave_record->find('all',array('conditions' => array(
                         'AND' => array('leave_record.Leave_states LIKE' => "accepted",
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $td,
				'leave_record.To_Date >=' => $td),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $fd)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $td)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $fd,
				'leave_record.To_Date <=' => $td))
			)))));
                }else if($status == 'rejected'){
                    $search = $this->leave_record->find('all',array('conditions' => array(
                         'AND' => array('leave_record.Leave_states LIKE' => "rejected",
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $td,
				'leave_record.To_Date >=' => $td),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $fd)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $td)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $fd,
				'leave_record.To_Date <=' => $td))
			)))));
                    
                }else if($status == 'pending'){
                    $search = $this->leave_record->find('all',array('conditions' => array(
                         'AND' => array('leave_record.Leave_states LIKE' => "pending",
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $td,
				'leave_record.To_Date >=' => $td),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $fd)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $td)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $fd,
				'leave_record.To_Date <=' => $td))
			)))));
                    
                }else{
                    $search = $this->leave_record->find('all',array('conditions' => array(
                         
			'OR'  => array( 'AND' => array(
				'leave_record.From_Date <=' => $td,
				'leave_record.To_Date >=' => $td),
                        
                         array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $fd)),
                             
                        array( 'AND' => array(
				'leave_record.From_Date <=' => $fd,
				'leave_record.To_Date >=' => $td)),
                             
                         array( 'AND' => array(
				'leave_record.From_Date >=' => $fd,
				'leave_record.To_Date <=' => $td))
			))));
                    
                }
        }
        if(count($search)>0) {
            $this->set('inc',1);
        } else 
            $this->set('inc',0);
        
        $this->set('search_result',$search);
        
    }
    } 
    
    public function leave_balance(){
        date_default_timezone_set('Asia/Colombo');
        $this->autoLayout = false;
        $this->autoRender = false;
        $leave_ty = $this->request->data('ltype');
        $userId = $this->Auth->user('EmpId');
        
        $now = new DateTime();
        $currentYearStartDate = new DateTime($now->format("Y").'-01-01');
        $currentYearEndDate = new DateTime($now->format("Y").'-12-31');
        
        $loggedUserAllLeaves = $this->leave_record->find('all', array(
                'fields'     => array(
                    'leave_record.Eid', 
                    'leave_record.Leave_Type', 
                    'leave_record.Leave_states',
                    'SUM(leave_record.real_days) AS sumOfLeaves'
                ),
                'conditions' => array(
                    'leave_record.Eid'           => $userId,
                    'leave_record.Leave_Type'    => $this->request->data['ltype'],
                    'leave_record.From_Date <= ' => $currentYearEndDate->format('Y-m-d'),
                    'leave_record.To_Date >= '   => $currentYearStartDate->format('Y-m-d'),
                    'leave_record.Leave_states !=' => 'rejected' 
                ),
                'group'      => array('leave_record.Leave_Type', 'leave_record.Leave_states')
            )
        );
        
        $use_leave_info = $this->User->find('first', array(
            'fields'     => array(
                'User.nof_sick_lv', 'User.nof_cas_lv',
                'User.nof_ann_lv',  'User.nof_liv_lv'
            ),  
            'conditions' => array('User.EmpId' => $userId)
        ));
        
        $response = array();
        $response['applied_leaves'] = $loggedUserAllLeaves;
        $response['user_leave_info'] = $use_leave_info;
        echo json_encode($response);
        exit();
        
        
        
//        $today = date("Y-m-d");
//        $sick_real_days=0;
//        $annual_real_days=0;
//        $casual_real_days=0;
//        $live_real_days=0;
//        $sick_pending_real_days=0;
//        $annual_pending_real_days=0;
//        $casual_pending_real_days=0;
//        $nopay_pending_real_days=0;
//        $live_pending_real_days=0;
//        $userId = $this->Auth->user('EmpId');
//        $leave_no = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
//        
//        if($leave_ty=='sick'){
//            $sick_panding_blance=$this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'sick','leave_record.Leave_states'=>'pending','leave_record.From_date >' => $today)));
//            $sick_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'sick','leave_record.Leave_states'=>'accepted')));
//            foreach ($sick_panding_blance as $zz1){
//                $sick_pending_real_days=($sick_pending_real_days + $zz1['leave_record']['real_days']);               
//            }//debug(7-$sick_real_days);
//            //$this->set('aa1',$sick_pending_real_days);
//            $remaining_leaves=$sick_pending_real_days;
//            
//            foreach ($sick_blance as $xx1){
//                $sick_real_days=($sick_real_days + $xx1['leave_record']['real_days']);               
//            }//debug(7-$sick_real_days);
//            //$this->set('a1',$leave_no['User']['nof_sick_lv']-$sick_real_days);
//            //$accepted_leaves=$leave_no['User']['nof_sick_lv']-$sick_real_days-$sick_pending_real_days;
//            $accepted_leaves='0';
//            
//        }else if($leave_ty=='annual'){
//            $annual_panding_blance=$this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'annual','leave_record.Leave_states'=>'pending','leave_record.From_date >' => $today)));
//            $annual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'annual','leave_record.Leave_states'=>'accepted')));
//            foreach ($annual_panding_blance as $zz2){
//                $annual_pending_real_days=($annual_pending_real_days + $zz2['leave_record']['real_days']);               
//            }//debug(7-$annual_real_days);
//            //$this->set('aa2',$annual_pending_real_days);
//            $remaining_leaves=$annual_pending_real_days;
//            
//            foreach ($annual_blance as $xx2){
//                $annual_real_days=($annual_real_days + $xx2['leave_record']['real_days']);               
//            }//debug(7-$annual_real_days);
//            //$this->set('a2',$leave_no['User']['nof_ann_lv']-$annual_real_days);
//            $accepted_leaves=$leave_no['User']['nof_ann_lv']-$annual_real_days-$annual_pending_real_days;
//                    
//            
//        }else if($leave_ty=='casual'){
//            $casual_pending_blance=$this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'casual','leave_record.Leave_states'=>'pending','leave_record.From_date >' => $today)));
//            $casual_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'casual','leave_record.Leave_states'=>'accepted')));
//            foreach ($casual_pending_blance as $zz3){
//                $casual_pending_real_days=($casual_pending_real_days + $zz3['leave_record']['real_days']);               
//            }//debug(7-$casual_real_days);
//            //$this->set('aa3',$casual_pending_real_days);
//            $remaining_leaves=$casual_pending_real_days;
//            
//            foreach ($casual_blance as $xx3){
//                $casual_real_days=($casual_real_days + $xx3['leave_record']['real_days']);               
//            }//debug(7-$casual_real_days);
//            //$this->set('a3',$leave_no['User']['nof_cas_lv']-$casual_real_days);
//            $accepted_leaves=$leave_no['User']['nof_cas_lv']-$casual_real_days-$casual_pending_real_days;
//            
//            
//        }else if($leave_ty=='nopay'){
//            $nopay_pending_blance=$this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'nopay','leave_record.Leave_states'=>'pending','leave_record.From_date >' => $today)));
//            foreach ($nopay_pending_blance as $ss3){
//                $nopay_pending_real_days=($nopay_pending_real_days + $ss3['leave_record']['real_days']);               
//            }//debug(7-$casual_real_days);
//            $accepted_leaves='0';
//            
//            //$this->set('aa4',$nopay_pending_real_days);
//            $remaining_leaves=$nopay_pending_real_days;
//        }else if($leave_ty=='live'){
//            $live_pending_blance=$this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'live','leave_record.Leave_states'=>'pending','leave_record.From_date >' => $today)));
//            $live_blance = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_Type'=>'live','leave_record.Leave_states'=>'accepted')));
//            foreach ($live_pending_blance as $pp3){
//                $live_pending_real_days=($live_pending_real_days + $pp3['leave_record']['real_days']);               
//            }//debug(7-$casual_real_days);
//            //$this->set('aa5',$live_pending_real_days);
//            $remaining_leaves=$live_pending_real_days;
//            
//            foreach ($live_blance as $xx5){
//                $live_real_days=($live_real_days + $xx5['leave_record']['real_days']);               
//            }//debug(7-live_real_days);
//            //$this->set('a5',$leave_no['User']['nof_liv_lv']-$live_real_days);
//            $accepted_leaves=$leave_no['User']['nof_liv_lv']-$live_real_days-$live_pending_real_days;
//            
//            
//        }
//        return $accepted_leaves.','.$remaining_leaves;
   } 
    
   public function accept_leave_cancel($cancel_id){
       
        $records = $this->leave_record->find('all',array('conditions' => array('leave_record.id'=>$cancel_id)));
        $name = $this->User->find('first',array('conditions' => array('User.EmpId'=>$this->Auth->user('EmpId'))));
        
        $leaveType = LeaveUtil::getLeaveTypeName($records[0]['leave_record']['Leave_Type']);
        $leaveType = ($leaveType === NULL) ? '' : $leaveType;
        
        $leaveTime = LeaveUtil::getLeaveTimeName($records[0]['leave_record']['Leave_Time']);
        $leaveTime = ($leaveTime === NULL) ? '' : $leaveTime;
        
        $emailSubject = 'Leave Cancellation' . ' : ' . $name['User']['EmpName'];
        $message = $name['User']['EmpName'].' canceled the leave.'
            .'\n\nFrom Date : '.$records[0]['leave_record']['From_Date']
            .'\nTo Date : '.$records[0]['leave_record']['To_Date']
            .'\n'.'Leave Type : '.$leaveType
            .'\n'.'Leave Time : '.$leaveTime
            .'\n'.'Leave Comment : '.$records[0]['leave_record']['Leave_comment'];

        $leaveDeleted = $this->leave_record->delete($cancel_id);
        if ($leaveDeleted === TRUE) {
            $condition_roleArr = array(
                    array('User.role'  => array('CEO', 'pm'))
                );        

            if ($name['User']['role'] !== 'CEO' && $name['User']['role'] !== 'pm') {
                $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                        'conditions' => array('emp_project.Eid' => $this->Auth->user('EmpId'))));

                if ($projects) {
                    $projectsIdArr = array();
                    foreach ($projects as $key => $value) {
                        $projectsIdArr[] = $value['emp_project']['Pid'];
                    }
                    $condition_roleArr[] = array('AND' => array('User.role' => 'tl', 'emp.Pid'    => $projectsIdArr));
                }
            }  
        
            $emailRecieversUserInfo = $this->User->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'emp_projects',
                        'alias' => 'emp',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'User.EmpId = emp.Eid'
                        )
                    )
                ),
                'conditions' => array(
                    'OR'    => $condition_roleArr,
                    'AND'   => array('User.EmpId != ' => $this->Auth->user('EmpId'))
                ),
                'fields' => array('DISTINCT User.EmpId', 'User.EmpName', 'User.email')
            ));        

            $recipientsEmails = array();
            if ($emailRecieversUserInfo) {
                foreach ($emailRecieversUserInfo as $key => $value) {
                    $recipientsEmails[] = $value['User']['email'];
                }
            }                

            MailUtil::sendMail($recipientsEmails, $message, $emailSubject);        
        }
        
        $this->redirect(array('action' => 'leavereport'));
    }

public function settings(){
    
    $this->autoLayout = false;
    $this->autoRender = false;
    
        $not = $this->request->data('arr');
        $x ="";
       
        if(count($not)>1){
            
            for($i  = 1; $i<count($not); $i++){
                $x .= $not[$i];
                $x .="@";
            }
            $this->User->id = $not[0];
                
            $this->User->saveField('inform',$x);
                
        }
        
        //return $x;
        
        
}

    public function applyLeave() {
        $this->autoLayout = false;
        $this->autoRender = false;
        $response = array();
        $errorMsg = array();

        $startLeaveDateStr = '2014-05-05';
        $endLeaveDateStr = '2014-05-06';
        $leaveType = 'nopay';
        $leaveTime = 'fullday';
        $leaveComment = '';

        $startLeaveDate = null;
        $endLeaveDate = null;
        $dateDiffInDates = 0;
        $leaveInRealDays = 0;

        if (!empty($startLeaveDateStr) && !empty($endLeaveDateStr)) {
            $startLeaveDate = DateTime::createFromFormat('Y-m-d', $startLeaveDateStr);
            $endLeaveDate = DateTime::createFromFormat('Y-m-d', $endLeaveDateStr);
        } else {
            $response['result'] = 'error';
            $response['errMsg'] = 'Dates are not correct.';
            echo json_encode($response);
            return;
        }

        // Validate data difference.
        if ($startLeaveDate && $endLeaveDate) {
            $timeDiffInSec = $endLeaveDate->getTimestamp() - $startLeaveDate->getTimestamp();

            if ($timeDiffInSec < 0) {
                $response['result'] = 'error';
                $response['errMsg'] = 'Dates are not correct.';
                echo json_encode($response);                
                return;
            } else {
                $dateDiffInDates = $timeDiffInSec / (60 * 60 * 24);
                $dateDiffInDates = $dateDiffInDates + 1;
                switch ($leaveTime) {
                    case 'fullday':
                        $leaveInRealDays = floatval($dateDiffInDates) * floatval(1);
                        break;
                    case 'halfday':
                        $leaveInRealDays = floatval($dateDiffInDates) * 0.5;
                        break;
                    default:
                        break;
                }
            }
        }

        $userId = $this->Auth->user('EmpId');

        $userModelData = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        var_dump($userModelData);
        // Get sum of leaves 
        if ($leaveType === 'annual' || $leaveType === 'casual') {
            $totalLeavesTaken = $this->leave_record->find('all', array(
                    'fields' => array('SUM(real_days) AS totalLeavesTaken'),
                    'conditions' => array('leave_record.Eid' => $userId, 
                            'leave_record.Leave_Type' => $leaveType,
                            'leave_record.Leave_states != '=> 'rejected',
                            'leave_record.From_Date >= ' => $startLeaveDate->format('Y').'-01-01',
                            'leave_record.From_Date <= ' => $startLeaveDate->format('Y').'-12-31' 
                            )));

            $leaveCountTaken = 0;
            if ($totalLeavesTaken[0][0]['totalLeavesTaken'] != null && !empty($userModelData)) {
                $leaveCountTaken = $totalLeavesTaken[0][0]['totalLeavesTaken'];
            }

            $leaveCountToBeTaken = $leaveCountTaken + $leaveInRealDays;

            if ($leaveType == 'annual') {
                if (floatval($leaveCountToBeTaken) > floatval($userModelData['User']['nof_ann_lv'])) {
                    $response['result'] = 'error';
                    $response['errMsg'] = 'No enough leaves.';
                    echo json_encode($response);                
                    return;
                }
            } elseif ($leaveType == 'casual') {
                if (floatval($leaveCountToBeTaken) > floatval($userModelData['User']['nof_cas_lv'])) {
                    $response['result'] = 'error';
                    $response['errMsg'] = 'No enough leaves.';
                    echo json_encode($response);                
                    return;
                }
            } else {
                // Do nothing.
            }
        } else {
            // Do nothing.
        }

        $beforeLeaveRecord = $this->leave_record->find('first', array(
                    'conditions' => array('leave_record.Eid'=>$userId, 
                        'leave_record.Leave_states != '=> 'rejected',
                        'leave_record.From_Date < ' => $startLeaveDateStr),
                    'order' => array('leave_record.From_date DESC')
                    ));
        if (!empty($beforeLeaveRecord)) {
            $beforeLeaveRecord_endDate = new DateTime($beforeLeaveRecord['leave_record']['To_Date']);
            if ($beforeLeaveRecord_endDate >= $startLeaveDate) {
                $response['result'] = 'error';
                $response['errMsg'] = 'You have already requested for leave.';
                echo json_encode($response);
                return;
            } else {
                // Do noting.
            }
        } else {
            // Do nothing.
        }

        $afterLeaveRecord = $this->leave_record->find('first', array(
                    'conditions' => array('leave_record.Eid'=>$userId, 
                        'leave_record.Leave_states !='=> 'rejected',
                        'leave_record.To_Date >' => $endLeaveDateStr),
                    'order' => array('leave_record.From_date ASC')
                    ));
        if (!empty($afterLeaveRecord)) {
            $afterLeaveRecord_startDate = new DateTime($afterLeaveRecord['leave_record']['From_Date']);
            if ($afterLeaveRecord_startDate <= $endLeaveDate) {
                $response['result'] = 'error';
                $response['errMsg'] = 'You have already requested for leave.';
                echo json_encode($response);
                return;
            } else {
                // Do nothing.
            }
        } else {
            // Do mothing.
        }

        $betweenLeaveRecord = $this->leave_record->find('first', array(
                    'conditions' => array('leave_record.Eid'=>$userId, 
                        'leave_record.Leave_states !='=> 'rejected',
                        'leave_record.From_Date <=' => $startLeaveDateStr,
                        'leave_record.To_Date >=' => $startLeaveDateStr),
                    'order' => array('leave_record.From_date ASC')
                    ));
        if (!empty($betweenLeaveRecord)) {
            $response['result'] = 'error';
            $response['errMsg'] = 'You have already requested for leave.';
            echo json_encode($response);
            return;            
        } else {
            // Do nothing.
        }

        // Save leave request.
        // $leaveRecordModelInfo = array('Leave_Type' => $leaveType,
        //         'From_Date' => $startLeaveDateStr, 'To_Date' => $endLeaveDateStr,
        //         'Eid' => $userId, ' Leave_comment' => $leaveComment, 'Leave_Time' => null,
        //         'Leave_states' => 'pending', 'real_days' => $leaveInRealDays, 'accept_id' => 2,
        //         'EmpName' => $userModelData['User']['EmpName']);


        $leaveRecordModelInfo =  array('leave_record' => array('Leave_Type' => $leaveType,
                'From_Date' => $startLeaveDateStr, 'To_Date' => $endLeaveDateStr,
                'Eid' => $userId, 'Leave_comment' => $leaveComment, 'Leave_Time' => null,
                'Leave_states' => 'pending', 'real_days' => $leaveInRealDays, 'accept_id' => 2,
                'EmpName' => $userModelData['User']['EmpName'], 'id' => 399));

         // $this->leave_record->data = $leaveRecordModelInfo;
        unset($leaveRecordModelInfo);
        $leaveRecordModelInfo['leave_record']['id'] = 401;
        $leaveRecordModelInfo['leave_record']['From_Date'] = $startLeaveDateStr;
        var_dump( $leaveRecordModelInfo);
        // $this->leave_record->create();
        $uuu = $this->leave_record->save($leaveRecordModelInfo);
        var_dump($uuu);
        echo json_encode('Test');
    }

    public function addLeaveForOther() {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser',$username);
    }
}
?>