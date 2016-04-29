<?php
App::uses('LeaveUtil', 'Lib');
App::uses('MailUtil', 'Lib');

class EmployeesActionsController extends AppController {
    var $uses=array('Work_from_homes','emp_project','Project','temp_detail','events','User','structure','email_details','Event','leave_record');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
            'addLeaveForOther',     'getLeaveAmountByEmpId',
            'viewEmployeeDetails', 'updateUser', 'deleteEmployee'
        );
    }

    public function addLeaveForOther() {
//        $tttt = MailUtil::setContentForLeaveApplyOnBehalfOf('Chathuraka', 'didula lakshitha', new DateTime('2014-12-12'), new DateTime('2014-12-12'), 'casual', 'blahh sdfjldjs djsdjf', 'fullday', 4);
//        MailUtil::sendMail(array('lakshitha.d@softcodeit.com'), $tttt);
        
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        
        $employees = $this->User->find('all', array(
            'conditions' => array(
                'User.EmpId != ' => $this->Auth->user('EmpId'),
                'User.role !='  => 'CEO'
            )
        ));
        $this->set('employees', $employees);
        
        $eventHolidays_temp = $this->Event->find('all', 
                array( 'group' => array('Event.start') ));
        $eventHolidays = array();
        foreach ($eventHolidays_temp as $key => $value) {
            $eventHolidays[] = $value['Event']['start'];
        }
        $this->set('eventHolidays', $eventHolidays);
        
        //when the request is post
        if($this->request->is('post')){
            $currentDate = new DateTime(date("Y-m-d"));
            $empId = $this->request->data['EmpId'];
            $leaveType = $this->request->data['Leave_Type'];
            $leaveTime = $this->request->data['Leave_Time'];
            $fromDate = new DateTime($this->request->data['From_Date']);
            $fromDateNonObject = $this->request->data['From_Date'];            
            $toDate = (isset($this->request->data['To_Date'])) 
                    ? new DateTime($this->request->data['To_Date']) : $fromDate;
            $toDateNonObject = (isset($this->request->data['To_Date'])) 
                    ? $this->request->data['To_Date'] : $fromDateNonObject;
            $leaveComment = $this->request->data['Leave_comment'];
            
            //checking the dates are valied
            if ($currentYearStartDate > $fromDate || $fromDate > $toDate) {
                $errorMessage = "Check again the dates are been selected.";
                $this->set("errorMessage", $errorMessage);
                return null;
            }
            
            $realDaysOfLeave = LeaveUtil::calculateRealDaysForLeave($fromDate, $toDate, $leaveTime);
            if ($realDaysOfLeave > 7) {
                $errorMessage = "Duration must be less than or equal to 7 days. Please Try Again.";
                $this->set("errorMessage", $errorMessage); 
                return null;             
            }

            if ($realDaysOfLeave === 0) {
                $errorMessage = "Applied dates are holidays. Please check your leave dates.";
                $this->set("errorMessage", $errorMessage); 
                return null;                         
            }
            
            $hasRemainingLeaves = LeaveUtil::hasRemaingLeavesForEmployee($empId, $leaveType, $fromDate, $realDaysOfLeave);
            if (!$hasRemainingLeaves) {
                $errorMessage = "Sorry!! Remaining Leaves Not sufficient. Try again with NoPay..";
                $this->set("errorMessage", $errorMessage); 
                return null;                             
            }
            
            $isAlreadyApplied = LeaveUtil::isLeaveApplied($empId, $fromDate, $toDate, $leaveTime);
            if ($isAlreadyApplied) {
                $errorMessage = "Leaves have already been applied within these days.";
                $this->set("errorMessage", $errorMessage); 
                return null;                             
            }

            $isWFHAlreadyApplied = LeaveUtil::isWorkFromHomeApplied($empId, $fromDate, $toDate, $leaveTime);
            if($isWFHAlreadyApplied){
                $errorMessage = "Work From Homes have already been applied within these days.";
                $this->set("errorMessage", $errorMessage); 
                return null;                             
            }            

                        //if all goes right saving
            $newLeave['leave_record'] = array(
                'Leave_Type'    => $this->request->data['Leave_Type'],
                'From_Date'     => $fromDateNonObject,
                'To_Date'       => $toDateNonObject,
                'Eid'           => $this->request->data['EmpId'],
                'Leave_comment' => $this->request->data['Leave_comment'],
                'Leave_Time'    => $this->request->data['Leave_Time'],
                'Leave_states'  => 'accepted', //'pending',
                'real_days'     => $realDaysOfLeave,
                'accept_id'     => $this->Auth->user('EmpId'),
                'EmpName'       => $this->getEmpName($this->request->data['EmpId']),
            );
            if($this->leave_record->save($newLeave)){

                $_SESSION['rand'] = 'Leave is successfully added for '.$newLeave['leave_record']['EmpName'].'.';
               // Sending Email
                $theMessage = MailUtil::setContentForLeaveApplyOnBehalfOf(
                    $this->Auth->user('EmpName'),
                    $newLeave['leave_record']['EmpName'], 
                    $fromDate, 
                    $toDate,
                    $leaveType,
                    $leaveTime,
                    $leaveComment,
                    $leaveTime,
                    $realDaysOfLeave );
                
                $employeesDetails   = $this->getEmpDetails($this->request->data['EmpId']); 
                $employeeEmail      = $employeesDetails['User']['email'];
                $senderDetails      = $this->getEmpDetails($this->Auth->user('EmpId')); 
                $senderEmail        = $senderDetails['User']['email'];
                
                //taking recipient list
                $recipientList = $this->User->find('list', array('conditions' => array('OR' => array(
                        'User.group_id' => array(2, 3), //to all who belogns to group_id 2 and 3 in users table
                        'User.EmpId' => $this->request->data['EmpId'], // to the leave recipient
                        )),
                    'fields' => array('User.EmpId', 'User.email')));

                //sending an email
                MailUtil::sendMail($recipientList, $theMessage);
                
            }else{
//                debug($this->leave_record->validationErrors);
                $errorMessage = $this->leave_record->validationErrors['0'];
                // $errorMessage = "Sorry, Leave is not added. Try Again";
                $this->set("errorMessage", $errorMessage);
                
                
            }

        } else {
            //if request is not 'POST' : do nothing
        }
        
//        $_SESSION['rand'] = 'Your Leave has been sent for approval.'.$ttt;
    }
    
    public function getEmpName($Eid) {
        // $this->User->find('list');  //do
        $employeeName = $this->User->find('first', array(
            'fields' => array('User.EmpName'),
            'conditions' => array(
                'User.EmpId' => $Eid,
            )
        ));
        return $employeeName['User']['EmpName'];
    }
    public function getEmpDetails($Eid) {
        $employeeDetails = $this->User->find('first', array(
            'conditions' => array(
                'User.EmpId' => $Eid,
            )
        ));
        return $employeeDetails;
    }
    

    public function getLeaveAmountByEmpId() {
        date_default_timezone_set('Asia/Colombo');
        
        $this->autoLayout = false;
        $this->autoRender = false;
        
        $empId = $this->request->data['empId'];
        
        $now = new DateTime();
        $currentYearStartDate = new DateTime($now->format("Y").'-01-01');
        $currentYearEndDate = new DateTime($now->format("Y").'-12-31');        
        
        // Get employee total allowed leaves.
        $employeeObj = $this->User->find('first', array(
            'conditions' => array(
                'User.EmpId' => $empId
            )
        ));
        $nof_ann_lv = ($employeeObj['User']['nof_ann_lv']) ? $employeeObj['User']['nof_ann_lv'] : 0;
        $nof_cas_lv = ($employeeObj['User']['nof_cas_lv']) ? $employeeObj['User']['nof_cas_lv'] : 0;
        $nof_liv_lv = ($employeeObj['User']['nof_liv_lv']) ? $employeeObj['User']['nof_liv_lv'] : 0;
        $nof_sick_lv = ($employeeObj['User']['nof_sick_lv']) ? $employeeObj['User']['nof_sick_lv'] : 0;
        $nof_nopay_lv = 0;

        // Get the employee's leave count based on status.
        $employeeLeaves = $this->leave_record->find('all', array(
                'fields'     => array(
                    'leave_record.Eid',                             'leave_record.Leave_Type', 
                    'SUM(leave_record.real_days) AS sumOfLeaves',   'leave_record.Leave_states'
                ),
                'conditions' => array(
                    'leave_record.Eid'              => $empId,
                    'leave_record.To_Date >= '      => $currentYearStartDate->format('Y-m-d'),
                    'leave_record.From_Date <= '    => $currentYearEndDate->format('Y-m-d'),
                    'leave_record.Leave_states !='  => 'rejected' 
                ),
                'group'      => array('leave_record.Leave_Type', 'leave_record.Leave_states')
            )
        );        
        $empLeaveCountStatus = array(
            'annual' => array('totalAmount' => $nof_ann_lv,   'pending' => 0, 'accepted' => 0, 'remainging' => 0),
            'casual' => array('totalAmount' => $nof_cas_lv,   'pending' => 0, 'accepted' => 0, 'remainging' => 0),
            'live'   => array('totalAmount' => $nof_liv_lv,   'pending' => 0, 'accepted' => 0, 'remainging' => 0),
            'sick'   => array('totalAmount' => $nof_sick_lv,  'pending' => 0, 'accepted' => 0, 'remainging' => 0),
            'nopay'  => array('totalAmount' => $nof_nopay_lv, 'pending' => 0, 'accepted' => 0, 'remainging' => 0)
        );
        if ($employeeLeaves) {
            foreach ($employeeLeaves as $key => $value) {
                $leaveStatus = $value['leave_record']['Leave_states'];
                $leaveType = $value['leave_record']['Leave_Type'];
                
                $empLeaveCountStatus[$leaveType][$leaveStatus] = $value[0]['sumOfLeaves'];
            }
            
            foreach ($empLeaveCountStatus as $key => $value) {
                $accepted = $value['accepted'];
                $pending = $value['pending'];
                $total = $value['totalAmount'];
                $remainings = $total - $accepted - $pending;
                $remainings = ($remainings > 0) ? $remainings : 0;
                $empLeaveCountStatus[$key]['remainging'] = $remainings;
            }
        } else {
            $empLeaveCountStatus['annual']['remainging'] = $empLeaveCountStatus['annual']['totalAmount'] 
                    - $empLeaveCountStatus['annual']['accepted'] - $empLeaveCountStatus['annual']['pending'];
            $empLeaveCountStatus['casual']['remainging'] = $empLeaveCountStatus['casual']['totalAmount'] 
                    - $empLeaveCountStatus['casual']['accepted'] - $empLeaveCountStatus['casual']['pending'];
        }
        
        $response = array();
        $response['data']['EmpId'] = $empId;
        $response['data']['leaves'] = $empLeaveCountStatus;
        
        echo json_encode($response);
        exit();        
    }

    public function viewEmployeeDetails()
    {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $user_details = $this->User->find('all',
            array(
                'conditions' => array(
                    'User.status' => 'active'
                )
            ));

        $this->set('user_detail', $user_details);


    }

    public function updateUser() {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $response = array();
        $empid = $this->request->query['empid'];
        $empRole = $this->request->query['empRole'];

        if (!$this->request->query) {
            $response['success'] = FALSE;
            $response['msg'] = 'Could get the data';
            echo json_encode($response);
            exit();
        }

        if ($this->request->query) {
            $this->loadModel('User');
            $this->loadModel('Group');

            $person = $this->User->find('first', array('conditions' => array('User.EmpId' => $empid)));
            $groupid = $this->Group->find('first', array('conditions'=> array('Group.name' => $empRole)));
            $this->User->id = $person['User']['id'];
            $groupidvalue = $groupid['Group']['id'];
            $this->User->saveField('role', $empRole);
            $this->User->saveField('group_id', $groupidvalue);
            $response['success'] = TRUE;
            echo json_encode($response);
        } else {
            $response['success'] = FALSE;
            $response['msg'] = 'Leave count is invalid.';
            echo json_encode($response);
        }
        exit();
    }
    public function deleteEmployee() {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $response = array();
        $deleteid = $this->request->query['empid'];


        if (!$this->request->query) {
            $response['success'] = FALSE;
            $response['msg'] = 'Could get the data';
            echo json_encode($response);
            exit();
        }

        if ($this->request->query) {
            $this->loadModel('User');
            $this->loadModel('delete_user');

            $deleteUser = $this->User->find('first', array('conditions' => array('EmpId' => $deleteid)));

            $this->User->EmpId = $deleteUser['User']['EmpId'];
           // $this->User->saveField('status', 'deactivate');
            $this->delete_user->saveField('EmpId', $deleteid);
            $this->delete_user->saveField('EmpName', $deleteUser['User']['EmpName']);
            $this->delete_user->saveField('email', $deleteUser['User']['email']);
            $this->delete_user->saveField('role', $deleteUser['User']['role']);
            $this->delete_user->saveField('group_id', $deleteUser['User']['group_id']);
            $this->delete_user->saveField('join_date', $deleteUser['User']['join_date']);
            if($deleteUser['User']['status']!= 0){
                $this->User->updateAll(array('status'=>0), array('AND' =>array('User.EmpId'=>$deleteUser['User']['EmpId'])));
            }
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
