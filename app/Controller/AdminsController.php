<?php

App::uses('MailUtil', 'Lib');
App::uses('LeaveUtil', 'Lib');

// app/Controller/UsersController.php
class AdminsController extends AppController {

    var $name = 'Admin';
    var $uses = array('User', 'Admin', 'leave_record', 'Work_from_homes', 'temp_detail', 'Temp_user', 'emp_project', 'Project', 'Event', 'Holiday', 'email_details', 'aros_acos', 'acos');
    public $components = array('Session', 'Search.Prg', 'Paginator');
    public $helperss = array('Html', 'Form');
    public $presetVars = array(
        array('field' => 'From_Date', 'type' => 'value'),
        array('field' => 'To_Date', 'type' => 'value'),
        array('field' => 'Eid', 'type' => 'value'),
    );
    
    public $paginate = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('conform_wfh', 'email_leave_accept', 'email_leave_reject', 
                'email_wfh_accept', 'email_wfh_reject', 'mail_send', 'emails', 
                'admin_edit', 'admin_user_leave_edit', 'getAjaxUsers', 
                'isUsernameAvailable','getLeavesAccordingToStates', 'add_employee',
                'add_project', 'create_new_user', 'viewProjectDetails', 'deleteProject', 'filterProjects', 'addProject');
    }

    public function view_users() {
        $this->set('title_for_layout', 'View Users');

        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $this->set('users', $this->User->find('all'));
        $this->set('users', $this->paginate());
    }

    ////////---send mail after created new user account---/////
    public function send_mail($user_id, $password) {
        $encrypted = $user_id ^ 18544332;
        $confirmation_link = "http://" . $_SERVER['HTTP_HOST'] . $this->webroot . "Users/email_profileedit/" . $encrypted;
        $temp = array('Temp_user' => array('confirmation_id' => $encrypted));
        $this->Temp_user->save($temp);

        $records = $this->User->find('all', array('conditions' => array('User.id' => $user_id)));
        $message = 'Hi ' . $records[0]['User']['EmpName'] . ",You'r current Username : " . $records[0]['User']['username'] . " and Password : " . $password . " You can edit you'r profile through this link.. Thank You.";

        App::uses('CakeEmail', 'Network/Email');

        $email = new CakeEmail('gmail');
        $email->from('admin@lms.nexttestsite.com');
        $email->to($records[0]['User']['email']);
        $email->subject('Account Confirmation');
        $email->send($message . " " . $confirmation_link);

        $this->redirect(array('action' => 'create_new_user'));
    }

    private function sendLeaveApprovalNotification($message, $subject, $recipientsEmailsArr) {
        MailUtil::sendMail($recipientsEmailsArr, $message, $subject);
    }

    public function create_new_user() {
        $this->set('title_for_layout', 'Create User');
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $this->User->create();

        if ($this->request->is('post')) {
            //$join = date("Y-m-d");
            if (empty($this->data['users']['pro_picture']['name'])) {
                unset($this->request->data['users']['pro_picture']);
            }
            
            if (!empty($this->data['users']['pro_picture']['name'])) {
                $file = $this->data['users']['pro_picture'];
                $ary_ext = array('jpg', 'jpeg', 'gif', 'png'); //array of allowed extensions
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                if (in_array($ext, $ary_ext)) {
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . 'profile_pictures/' . $file['name']);
                    $this->request->data['users']['pro_picture'] = $file['name'];
                }
            }

            if ($this->request->data['role'] == 'admin') {
                $this->request->data['group_id'] = 5;
            } elseif ($this->request->data['role'] == 'pm') {
                $this->request->data['group_id'] = 2;
            } elseif ($this->request->data['role'] == 'tl') {
                $this->request->data['group_id'] = 3;
            } elseif ($this->request->data['role'] == 'normal') {
                $this->request->data['group_id'] = 4;
            } else {
                $this->request->data['group_id'] = 6;
            }

            $lastEmpId = $this->User->find('first', 
                    array('fields' => 'User.EmpId', 'order' => 'User.EmpId DESC'));
            $this->request->data['EmpId'] = ++$lastEmpId['User']['EmpId'];
            
            if ($this->User->save($this->request->data)) {
                if (!empty($file)) {
                    $x = '../profile_pictures/' . $file['name'];
                    $this->User->saveField('pro_picture', $x);
                } else {
                    $this->User->saveField('pro_picture', '../profile_pictures/Profile.jpg');
                }

                $this->User->saveField('notify', 'disabled');
                $this->User->saveField('status', 'active');
                $this->Session->setFlash(__('The user has been saved'));
            } else {
                $this->set('errors', $this->User->validationErrors);
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function delete($id = null) {
        $u1 = $this->User->find('all', array(
            'conditions' => array('User.id' => $id)));
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->User->delete($id)) {
            $eid = $u1[0]['User']['EmpId'];
            $ename = $u1[0]['User']['EmpName'];
            $this->Session->setFlash(__('The Employee with employee id: %s & employee name: %s has been removed.', h($eid), h($ename)));
            return $this->redirect(array('action' => 'view_users'));
        }
    }

    public function addProject(){
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $response = array();
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $projectid = $this->request->query['proid'];
        $empid = $this->request->query['empid'];

        if (!$this->request->query) {
            $response['success'] = FALSE;
            $response['msg'] = 'Could get the data';
            echo json_encode($response);
            exit();
        }
        if($this->request->query){
            $this->loadModel('emp_project');
            $checkRecord = $this->emp_project->find('first', array('conditions' => array('emp_project.Eid' => $empid, 'emp_project.Pid' => $projectid )));
            if(!empty($checkRecord)){
                $response['success'] = FALSE;
                $response['msg'] = 'This employee is already assigned to this project';
                echo json_encode($response);
                exit();
            }else{
                foreach ($projectid as $key1) {

                    foreach ($empid as $key2) {
                        $this->emp_project->saveField('Eid', $key2);
                        $this->emp_project->saveField('Pid', $key1);
                        $response['success'] = TRUE;
                        $response['msg'] = 'Successfully assigned employees to the project.';
                       //$this->Session->setFlash(__('Successfully assigned employees to the project.', true));
                        echo json_encode($response);
                        return;

                    }
                }

            }

        }
        else {
            $response['success'] = FALSE;
            $response['msg'] = 'assign project is invalid.';
            echo json_encode($response);
        }
        exit();
    }

    public function edit($id = null) {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
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

    public function view_leave_report() {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);

        $this->Prg->commonProcess();
        $this->paginate = array(
            'conditions' => array('leave_record.From_Date <=' => $today, 'leave_record.To_Date >=' => $today, 'leave_record.Leave_states' => 'accepted'));
        $this->set('leaves', $this->paginate());
    }

    public function conform($id = null) {
        $s = 0;
        $ss = $this->params['pass'];
        if (empty($ss[0])) {
            $this->Session->setFlash(__('Can not Update'));
        } else {
            $decrypted = $ss[0] ^ 18544332;
            $this->leave_record->id = $decrypted;
            $leave_details = $this->leave_record->find('first', array('conditions' => array('leave_record.id' => $decrypted)));
            if ($leave_details['leave_record']['Leave_states'] == 'pending') {
                if ($this->request->is('post') || $this->request->is('put')) {
                    $con_id = $this->temp_detail->find('all', array('conditions' => array('temp_detail.confirmation_id' => $ss[0])));

                    if (!empty($con_id) && $this->leave_record->save($this->request->data)) {
                        $this->Session->setFlash(__('Your Conformation has been saved.'));
                        $this->temp_detail->delete($con_id[0]['temp_detail']['id']);

                        if ($this->request->data['Leave_states'] == 'accepted') {
                            $decrypted = $ss[0] ^ 18544332;
                            $this->leave_record->id = $decrypted;

                            $empids = $this->leave_record->find('all', array(
                                'conditions' => array('leave_record.id' => $decrypted)));
                            $empid = $empids[0]['leave_record']['Eid'];
                            $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                                'conditions' => array('emp_project.Eid' => $empid)));

                            if (empty($projects)) {
                                
                            } else {

                                for ($i = 0; $i < count($projects); $i++) {
                                    $x[$i] = $this->User->find('all', array(
                                        'joins' => array(
                                            array(
                                                'table' => 'emp_projects',
                                                'alias' => 'emp',
                                                'type' => 'INNER',
                                                'conditions' => array(
                                                    'User.EmpId = emp.Eid'
                                                )
                                            )
                                        ),
                                        'conditions' => array(
                                            'emp.Pid' => $projects[$i]['emp_project']['Pid']
                                        ),
                                        'fields' => array('User.*', 'emp.*')
                                    ));
                                }
//////////////////////send email
                                $records = $this->leave_record->find('all', array('conditions' => array('leave_record.id' => $decrypted)));
                                $message1 = 'Employee id : ' . $empid . '</br>' . ' will have a leave ' . '</br>' . '&nbsp;&nbsp;&nbsp;From Date : ' . $records[0]['leave_record']['From_Date']
                                        . '&nbsp;&nbsp;&nbsp;To Date : ' . $records[0]['leave_record']['To_Date'] . '</br>' . 'Leave Type : ' . $records[0]['leave_record']['Leave_Type'] . '</br>' . 'Leave Time : ' . $records[0]['leave_record']['Leave_Time'];

                                $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));
                                $message2 = 'Your leave request is accepted' . '</br>' . '&nbsp;&nbsp;&nbsp;From Date : ' . $records[0]['leave_record']['From_Date']
                                        . '&nbsp;&nbsp;&nbsp;To Date : ' . $records[0]['leave_record']['To_Date'] . '</br>' . 'Leave Time : ' . $records[0]['leave_record']['Leave_Time'];

                                App::uses('CakeEmail', 'Network/Email');

                                $admin = $this->User->find('first', array('conditions' => array('User.role' => 'admin')));

                                //debug($records2);
                                for ($c = 0; $c < count($x[0]); $c++) {

                                    $xx = $this->email_details->find('first', array('order' => array('email_details.ID' => 'DESC')));
                                    /* $email = new CakeEmail('gmail');
                                      $email->template('accept', 'default');
                                      $email->emailFormat('html');
                                      $email->from('admin@lms.nexttestsite.com');
                                      $email->to($x[0][$c]['User']['email']);
                                      $email->subject('Notification'); */
                                    if (empty($xx)) {
                                        $this->email_details->id = 1;
                                        //debug($this->email_details->getLastInsertId());
                                        $this->email_details->saveField('email', $x[0][$c]['User']['email']);
                                        $this->email_details->saveField('subject', 'Leave Notification');
                                        //$this->email_details->saveField('message',$message);
                                    } else {

                                        $this->email_details->id = $xx['email_details']['ID'] + 1;
                                        //debug($this->email_details->getLastInsertId());
                                        $this->email_details->saveField('email', $x[0][$c]['User']['email']);
                                        $this->email_details->saveField('subject', 'Leave Notification');
                                        $this->email_details->saveField('message', $message);
                                    }

                                    //if($admin['User']['notify'] == 'enabled' && $x[0][$c]['User']['notify'] == 'notify' ){
                                    if ($x[0][$c]['User']['email'] == $records2[0]['User']['email']) {
                                        //$email->send($message2 );
                                        $this->email_details->saveField('message', $message2);
                                    } else {
                                        //$email->send($message1 ); 
                                        $this->email_details->saveField('message', $message1);
                                    }
                                    //}
                                }
                            }
                        } elseif ($this->request->data['Leave_states'] == 'rejected') {

                            $decrypted = $ss[0] ^ 18544332;
                            $this->leave_record->id = $decrypted;


                            $empids = $this->leave_record->find('first', array(
                                'conditions' => array('leave_record.id' => $decrypted)));
                            $empid = $empids['leave_record']['Eid'];
                            //$projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                            //'conditions' => array('emp_project.Eid' => $empid)));
                            //debug($projects);
                            //if (empty($projects)){
                            //} else{

                            /** for($i = 0; $i<count($projects); $i++){
                              $x[$i] = $this->User->find('all', array(
                              'joins' => array(
                              array(
                              'table' => 'emp_projects',
                              'alias' => 'emp',
                              'type' => 'INNER',

                              'conditions' => array(
                              'User.EmpId = emp.Eid'
                              )
                              )
                              ),
                              'conditions' => array(

                              'emp.Pid' => $projects[$i]['emp_project']['Pid']
                              ),
                              'fields' => array('User.*', 'emp.*')

                              ));

                              //debug($x);
                              }* */
//////////////////////send email
                            $records = $this->leave_record->find('all', array('conditions' => array('leave_record.id' => $decrypted)));
                            //$message1 = 'Employee id : ' . $empid . ', will have a leave '.'  '.'From Date : '.$records[0]['leave_record']['From_Date']
                            //          .'  '.'To Date : '.$records[0]['leave_record']['To_Date'].' '.'Leave Type : '.$records[0]['leave_record']['Leave_Type'].'  '.'Leave Time : '.$records[0]['leave_record']['Leave_Time'];

                            $records2 = $this->User->find('first', array('conditions' => array('User.EmpId' => $empid)));
                            $message2 = 'Your leave request is rejected' . '</br>' . '&nbsp;&nbsp;&nbsp;From Date : ' . $records[0]['leave_record']['From_Date']
                                    . '&nbsp;&nbsp;&nbsp;To Date : ' . $records[0]['leave_record']['To_Date'] . '</br>' . ' Leave Time : ' . $records[0]['leave_record']['Leave_Time'];

                            App::uses('CakeEmail', 'Network/Email');

                            //debug($records2);
                            $xx = $this->email_details->find('first', array('order' => array('email_details.ID' => 'DESC')));
                            if (empty($xx)) {
                                $this->email_details->id = 1;
                                //debug($this->email_details->getLastInsertId());
                                $this->email_details->saveField('email', $records2['User']['email']);
                                $this->email_details->saveField('subject', 'Leave Notification');
                                //$this->email_details->saveField('message',$message);
                            } else {

                                $this->email_details->id = $xx['email_details']['ID'] + 1;
                                //debug($this->email_details->getLastInsertId());
                                $this->email_details->saveField('email', $records2['User']['email']);
                                $this->email_details->saveField('subject', 'Leave Notification');
                                $this->email_details->saveField('message', $message);
                            }

                            if ($admin['User']['notify'] == 'enabled' && $records2['User']['notify'] == 'notify')
                                $email->send($message2);





//debug($x);
                        }
                    }
                }else {
                    // $this->Session->setFlash(__('Unable to save your Conformation.'));
                }
            } else
                $this->Session->setFlash(__('Request is already confirmed'));
        }
    }

    public function conform_wfh($id = null) {


        $ss = $this->params['pass'];
        $admin = $this->User->find('first', array('conditions' => array('User.role' => 'admin')));
        if (empty($ss[0])) {
            $this->Session->setFlash(__('Can not Update'));

            // $this->redirect(array('action' => 'notify'));
        } else {
            $decrypted = $ss[0] ^ 18544332;


            $this->Work_from_homes->id = $decrypted;

            $leave_details = $this->Work_from_homes->find('all', array(
                'conditions' => array('Work_from_homes.id' => $decrypted)));

            if ($leave_details[0]['Work_from_homes']['wfh_states'] == 'pending') {

                if ($this->request->is('post') || $this->request->is('put')) {

                    $con_id = $this->temp_detail->find('all', array(
                        'conditions' => array('temp_detail.confirmation_id' => $ss[0])));


                    if (!empty($con_id) && $this->Work_from_homes->save($this->request->data)) {
                        $this->Session->setFlash(__('Your Conformation has been saved.'));

                        $this->temp_detail->delete($con_id[0]['temp_detail']['id']);
                        ///////////////////////////////////////////////////
                        //debug($this->request->data);
                        /////////////////////////////////////////////////
                        if ($this->request->data['wfh_states'] == 'accepted') {
                            $decrypted = $ss[0] ^ 18544332;
                            $this->Work_from_homes->id = $decrypted;


                            $empids = $this->Work_from_homes->find('all', array(
                                'conditions' => array('Work_from_homes.id' => $decrypted)));
                            $empid = $empids[0]['Work_from_homes']['Eid'];
                            $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                                'conditions' => array('emp_project.Eid' => $empid)));

                            //debug($projects);
                            if (empty($projects)) {
                                
                            } else {

                                for ($i = 0; $i < count($projects); $i++) {
                                    $x[$i] = $this->User->find('all', array(
                                        'joins' => array(
                                            array(
                                                'table' => 'emp_projects',
                                                'alias' => 'emp',
                                                'type' => 'INNER',
                                                'conditions' => array(
                                                    'User.EmpId = emp.Eid'
                                                )
                                            )
                                        ),
                                        'conditions' => array(
                                            'emp.Pid' => $projects[$i]['emp_project']['Pid']
                                        ),
                                        'fields' => array('User.*', 'emp.*')
                                    ));
                                }
//////////////////////send email
                                $records = $this->Work_from_homes->find('all', array('conditions' => array('Work_from_homes.id' => $decrypted)));
                                $message1 = 'Employee id : ' . $empid . '</br>' . 'will work in home ' . '</br>' . '&nbsp;&nbsp;&nbsp;From Date : ' . $records[0]['Work_from_homes']['From_Date']
                                        . '&nbsp;&nbsp;&nbsp;To Date : ' . $records[0]['Work_from_homes']['To_Date'] . '</br>' . 'WFH Time : ' . $records[0]['Work_from_homes']['wfh_Time'];

                                $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));
                                $message2 = 'Your WFH request is accepted' . '</br>' . '&nbsp;&nbsp;&nbsp;From Date : ' . $records[0]['Work_from_homes']['From_Date']
                                        . '&nbsp;&nbsp;&nbsp;To Date : ' . $records[0]['Work_from_homes']['To_Date'] . '</br>' . 'WFH Time : ' . $records[0]['Work_from_homes']['wfh_Time'];

                                App::uses('CakeEmail', 'Network/Email');

                                //debug($records2);
                                for ($c = 0; $c < count($x[0]); $c++) {


                                    $email = new CakeEmail('gmail');
                                    $email->template('accept', 'default');
                                    $email->emailFormat('html');

                                    $email->from('admin@lms.nexttestsite.com');
                                    $email->to($x[0][$c]['User']['email']);
                                    $email->subject('Notification');

                                    if ($admin['User']['notify'] == 'enabled' && $x[0][$c]['User']['notify'] == 'notify') {
                                        if ($x[0][$c]['User']['email'] == $records2[0]['User']['email']) {
                                            $email->send($message2);
                                        } else {
                                            $email->send($message1);
                                        }
                                    }
                                }

//debug($x);
                            }
                            // $this->redirect(array('action' => 'notify')); 
                        } else {
                            //$this->redirect(array('action' => 'email_wfh_reject',$ss[0]));
                            $decrypted = $ss[0] ^ 18544332;
                            $this->Work_from_homes->id = $decrypted;

                            $records = $this->Work_from_homes->find('all', array('conditions' => array('Work_from_homes.id' => $decrypted)));
                            $messages = 'Your WFH request is rejected.' . 'From Date : ' . $records[0]['Work_from_homes']['From_Date']
                                    . '  ' . 'To Date : ' . $records[0]['Work_from_homes']['To_Date'] . 'WFH Time : ' . $records[0]['Work_from_homes']['wfh_Time'];


                            $empids = $this->Work_from_homes->find('all', array(
                                'conditions' => array('Work_from_homes.id' => $decrypted)));
                            $empid = $empids[0]['Work_from_homes']['Eid'];
                            $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));

                            App::uses('CakeEmail', 'Network/Email');

                            $email = new CakeEmail('gmail');
                            $email->template('reject', 'default');
                            $email->emailFormat('html');
                            $email->from('admin@lms.nexttestsite.com');
                            $email->to($records2['User']['email']);
                            $email->subject('Notification');

                            if ($admin['User']['notify'] == 'enabled' && $records2['User']['notify'] == 'notify')
                                $email->send($messages);
                        }
                    }
                }
            }



            else {
                $this->Session->setFlash(__('Request is already confirm'));
                //$this->redirect(array('action' => 'notify'));
            }
        }
    }

/////////////////////////////////////////
    public function leave_request() {
        $this->set('title_for_layout', 'Leave Requests');

        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $userId = $this->Auth->user('EmpId');
        $today = date("Y-m-d");
        $line = 'true';
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        $projects = $this->emp_project->find('all', array('conditions' => array('emp_project.Eid' => $userId)));

        $y = array();

        $leaveStatusArr = $this->Session->read('leave_request_LeaveStatusSelected');
        if (!(isset($leaveStatusArr))) {
            $leaveStatusArr = array('pending');                            // when the page loading, only pending requests are shown
        }

        if ($role['User']['role'] == 'CEO') {
            $x = $this->leave_record->find('all');
            $result1 = Set::sort($x, '{n}.leave_record.From_Date', 'DESC');
            $this->set('leave_request', $result1);
            $this->set('line', 'true');
        } else {

            $users_pm = $this->User->find('all', array('conditions' => array('User.group_id' => 2)));
            $users_tl = $this->User->find('all', array('conditions' => array('User.group_id' => 3)));
            $empIdsToBeExcluded = array();

            switch ($role['User']['role']) {
                case 'tl':
                    if (!empty($users_tl)) {
                        foreach ($users_tl as $value) {
                            $empIdsToBeExcluded[] = $value['User']['EmpId'];
                        }
                    }
                case 'pm':
                    if (!empty($users_pm)) {
                        foreach ($users_pm as $value) {
                            $empIdsToBeExcluded[] = $value['User']['EmpId'];
                        }
                    }
                default:
                    // Do nothing.
                    break;
            }
            
//            $leave_requests = $this->leave_record->find('all', array('conditions' => array(
//                    'Eid NOT' => $empIdsToBeExcluded, 
////                    'From_Date >=' => $today     // allowing to past leaves to be displayed
//                    'Leave_states' => $leaveStatusArr,
//                    ),
//                'order' => array('From_Date DESC')));
            
            // adding pagination
            $this->Paginator->settings = array(
                'conditions'    => array(
                    'leave_record.Eid NOT'      => $empIdsToBeExcluded,
                    'leave_record.Leave_states' => $leaveStatusArr,
                ),
                'order'         => array('leave_record.From_Date' => 'desc'),
                'limit'         => 10,
            );
            $toDisplayWithPagination = $this->Paginator->paginate('leave_record');

            $this->set('leave_request', $toDisplayWithPagination);

            $this->set('line', 'true');
            
        }
                
        if ($this->request->is('post')) {
            
            $leaveStatusArr = $this->request->data['leavetype'];
            $this->Session->write('leave_request_LeaveStatusSelected', $leaveStatusArr);
            
            if ($role['User']['role'] == 'CEO') { //if ceo
                $x = $this->leave_record->find('all', array(
                    'conditions' => array(
                        'Leave_states' => $leaveStatusArr,
                    )
                ));

                $result1 = Set::sort($x, '{n}.leave_record.From_Date', 'DESC');
                //$x = $this->leave_record->find('all', array('conditions' => array('leave_record.From_date >' => $today,'leave_record.accept_id !=' => 6)));
                $this->set('leave_request', $result1);
                $this->set('line', 'true');
                
            }  else { //if not ceo
                
                $users_pm = $this->User->find('all', array('conditions' => array('User.group_id' => 2)));
                $users_tl = $this->User->find('all', array('conditions' => array('User.group_id' => 3)));

                $empIdsToBeExcluded = array();

                switch ($role['User']['role']) {
                    case 'tl':
                        if (!empty($users_tl)) {
                            foreach ($users_tl as $value) {
                                $empIdsToBeExcluded[] = $value['User']['EmpId'];
                            }
                        }
                    case 'pm':
                        if (!empty($users_pm)) {
                            foreach ($users_pm as $value) {
                                $empIdsToBeExcluded[] = $value['User']['EmpId'];
                            }
                        }
                    default:
                        // Do nothing.
                        break;
                }

                $leave_requests = $this->leave_record->find('all', array('conditions' => array(
                        'Eid NOT' => $empIdsToBeExcluded,
                        'Leave_states' => $leaveStatusArr,
    //                    'From_Date >=' => $today     // allowing to past leaves to be displayed
                        ),
                    'order' => array('From_Date DESC')));
                
                $this->set('leave_request', $leave_requests);
                $this->set('line', 'true'); 
                
            }//end of else (if not ceo)
            
        }// 'post' request ends here
        
        $this->set('leaveStatusArr', $leaveStatusArr);
        
    }

////////////////////////////////////////////
    public function wfh_request() {
        $this->set('title_for_layout', 'WFH Requests');

        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $line = 'true';
        $today = date("Y-m-d");
        $userId = $this->Auth->user('EmpId');
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        $projects = $this->emp_project->find('all', array('conditions' => array('emp_project.Eid' => $userId)));
        
        $conditionsArr = array();
        $conditionsArr['Work_from_homes.Eid != '] = $userId;
        $conditionsArr['Work_from_homes.From_date >= '] = $today;

        if ($role['User']['role'] == 'tl') {
            if (!empty($projects)) {
                $projectIdArr = array();    
                for ($i = 0; $i < count($projects); $i++) {
                    $projectIdArr[] = $projects[$i]['emp_project']['Pid'];
                }
                
                // Collect user ids
                $empIdsOfRelatedProjects = array();
                $empIdByProjectId = $this->User->find('all', array(
                    'fields' => array('DISTINCT User.EmpId'),
                    'conditions' => array('emp.Pid' => $projectIdArr, 'User.EmpId != ' => $userId),
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
                ));
                
                if (!empty($empIdByProjectId)) {
                    foreach ($empIdByProjectId as $key => $value) {
                        $empIdsOfRelatedProjects[] = $value['User']['EmpId'];
                    }
                    
                    $conditionsArr['Work_from_homes.Eid'] = $empIdsOfRelatedProjects;
                }
                
            }
        }

//        $wfhRequests = $this->Work_from_homes->find('all', array(
//                    'conditions' => $conditionsArr,
//                    'joins' => array(
//                        array(
//                            'table' => 'users',
//                            'alias' => 'User',
//                            'type' => 'LEFT',
//                            'conditions' => array(
//                                'User.EmpId = Work_from_homes.Eid'
//                            )
//                        )
//                    ),
//                    'order' => array('Work_from_homes.From_Date DESC')
//                ));
//
//        $this->set('wfh_request', $wfhRequests);
        
        //adding pagination
        $this->Paginator->settings = array(
                'conditions'    => $conditionsArr,
                'order'         => array('Work_from_homes.From_Date' => 'desc'),
                'limit'         => 10,
        );
        
        
        $toDisplayWithiPagination = $this->Paginator->paginate('Work_from_homes');

        $this->set('wfh_request', $toDisplayWithiPagination);
        
        $this->set('line', 'true');
    }
/////////////////////////////////////////////

    public function accept($id) {
        $this->leave_record->id = $id;
        $userId = $this->Auth->user('EmpId');
        //$this->Session->setFlash(__('Leave request is accepted'));

        $exist = $this->leave_record->find('first', array('conditions' => array('leave_record.id' => $id)));
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));

        if (!empty($exist)) {
            if ($exist['leave_record']['accept_id'] <= $role['User']['group_id'] | (($exist['leave_record']['accept_id']) == 6 && ($role['User']['group_id'] != 6))) {
                if ($exist['leave_record']['Leave_states'] == 'accepted')
                    $this->Session->setFlash(__('Sorry, This Leave request has been already accepted.'));
                else
                    $this->Session->setFlash(__('Sorry, This Leave request has been already rejected.'));
            } else {
                $empids = $this->leave_record->find('first', array('conditions' => array('leave_record.id' => $id)));
                $empid = $empids['leave_record']['Eid'];
                $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
                $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                    'conditions' => array('emp_project.Eid' => $empid)));

                $this->leave_record->saveField('Leave_states', "accepted");
                $this->leave_record->saveField('accept_id', $role['User']['group_id']);
                $this->Session->setFlash(__('Leave request is accepted'));

                if ($role['User']['group_id'] == 2)
                    $author = 'Project Manager';
                elseif ($role['User']['group_id'] == 3)
                    $author = 'Team Lead';
                elseif ($role['User']['group_id'] == 6)
                    $author = 'CEO';

                for ($i = 0; $i < count($projects); $i++) {
                    $x[$i] = $this->User->find('all', array(
                        'joins' => array(
                            array(
                                'table' => 'emp_projects',
                                'alias' => 'emp',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'User.EmpId = emp.Eid'
                                )
                            )
                        ),
                        'conditions' => array(
                            'emp.Pid' => $projects[$i]['emp_project']['Pid']
                        ),
                        'fields' => array('User.*', 'emp.*')
                    ));
                }

//////////////////////send email
                $leaveOwner = $this->User->find('first', array('conditions' => array('User.EmpId' => $empid)));
                $leaveNotificationSubject = 'Leave Approval : ' . $leaveOwner['User']['EmpName'];

                $records = $this->leave_record->find('all', array('conditions' => array('leave_record.id' => $id)));
                $leaveType_temp = $records[0]['leave_record']['Leave_Type'];
                switch ($leaveType_temp) {
                    case 'annual':
                        $leaveType_temp = 'Annual';
                        break;
                    case 'sick':
                        $leaveType_temp = 'Sick';
                        break;
                    case 'casual':
                        $leaveType_temp = 'Casual';
                        break;
                    case 'live':
                        $leaveType_temp = 'Lieu';
                        break;
                    case 'nopay':
                        $leaveType_temp = 'No Pay';
                        break;
                    default:
                        break;
                }
                
                $leaveTime_temp = $records[0]['leave_record']['Leave_Time'];
                switch ($leaveTime_temp) {
                    case 'fullday':
                        $leaveTime_temp = 'Full Day';
                        break;
                    case '1sthalf':
                        $leaveTime_temp = '1st Half';
                        break;
                    case '2ndhalf':
                        $leaveTime_temp = '2nd Half';
                        break;
                    default:
                        break;
                }
                
                $message1 = $leaveOwner['User']['EmpName']."'s  Leave is approved by ".$role['User']['EmpName']
                        .'\n\nFrom Date : ' . $records[0]['leave_record']['From_Date']
                        .'\nTo Date : ' . $records[0]['leave_record']['To_Date']
                        .'\nLeave Type : ' . $leaveType_temp
                        .'\nLeave Time : ' . $leaveTime_temp;
                        
                $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));
                $message2 = 'Your leave request is accepted by ' . $author . '(' . $role['User']['EmpName'] . ')'
                        . '\n' . '&nbsp;&nbsp;&nbsp;From Date : ' . $records[0]['leave_record']['From_Date']
                        . '&nbsp;&nbsp;&nbsp;To Date : ' . $records[0]['leave_record']['To_Date'] . '\n' . 'Leave Time : ' . $records[0]['leave_record']['Leave_Time'];

                // Send mail to leave owner.
                $leaveOwnerEmail = array();
                $leaveOwnerEmail[] = $leaveOwner['User']['email'];

                // Send mails to leave approvers 
                $leaveApprovers = $this->User->find('all', array('conditions' => array('User.group_id' => array(2, 3, 6))));
                $recipientEmailList = array();
                $recipientEmailList[] = $role['User']['email'];
                if (!empty($leaveApprovers)) {
                    foreach ($leaveApprovers as $mailItem) {
                        $recipientEmailList[] = $mailItem['User']['email'];
                    }
                }

                $notifiedCount = 1 + count($leaveApprovers);

                $this->sendLeaveApprovalNotification($message2, $leaveNotificationSubject, $leaveOwnerEmail);
                $this->sendLeaveApprovalNotification($message1, $leaveNotificationSubject, $recipientEmailList);
                // }
            }
///end of exist
        } else {
            $this->Session->setFlash(__('Sorry, This Leave request has been canceled'));
        }
        $this->redirect(array('action' => 'leave_request'));
    }

//////////////////////////////////////////
    public function reject() {
        $id = $this->request->data['leave-request-id'];
            
        $admin = $this->User->find('first', array('conditions' => array('User.role' => 'admin')));
        $records = $this->leave_record->find('all', array('conditions' => array('leave_record.id' => $id)));
        $exist = $this->leave_record->find('all', array('conditions' => array('leave_record.id' => $id)));
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $records[0]['leave_record']['Eid'])));

        $isAllowedToReject = false;

        if (!empty($records)) {

            if ($exist[0]['leave_record']['accept_id'] <= $role['User']['group_id'] || (($exist[0]['leave_record']['accept_id']) == 6 && ($role['User']['group_id'] != 6))) {
                if ($exist[0]['leave_record']['Leave_states'] == 'accepted') {

                    $today = date("Y-m-d");
                    $datetime_today = new DateTime($today);

                    $datetime_leaveFrom = new DateTime($exist[0]['leave_record']['From_Date']);

                    if ($datetime_today <= $datetime_leaveFrom) {
                        $isAllowedToReject = true;
                    } else {
                        $this->Session->setFlash(__('Sorry, This Leave request has been already accepted.'));
                    }
                } else {
                    $this->Session->setFlash(__('Sorry, This Leave request has been already rejected.'));
                }
            } else {
                $isAllowedToReject = true;
            }
            //////////////////////
        } else {
            $this->Session->setFlash(__('Sorry, This Leave request has been canceled'));
        }
        
        // Do the requried processed for rejected leave request, If the request is allowed for rejection, 
        if ($isAllowedToReject === true) {
            $empids = $this->leave_record->find('all', array(
                'conditions' => array('leave_record.id' => $id)));
            $empid = $empids[0]['leave_record']['Eid'];
            $userId = $this->Auth->user('EmpId');
            $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
            $this->leave_record->id = $id;
            
            $this->leave_record->saveField('Leave_states', "rejected");
            $this->leave_record->saveField('accept_id', $role['User']['group_id']);
            $this->leave_record->saveField('reject_comment', $this->request->data['reject-comment']);
            $this->leave_record->saveField('approved_by', $userId);
            $this->Session->setFlash(__('Leave request is rejected'));

            if ($role['User']['group_id'] == 2)
                $author = 'Project manager';
            elseif ($role['User']['group_id'] == 3)
                $author = 'Tech Lead';
            elseif ($role['User']['group_id'] == 6)
                $author = 'CEO';

            $leaveType_temp = LeaveUtil::getLeaveTypeName($records[0]['leave_record']['Leave_Type']);
            $leaveType_temp = ($leaveType_temp === NULL) ? '' : $leaveType_temp;            
                
            $leaveTime_temp = LeaveUtil::getLeaveTimeName($records[0]['leave_record']['Leave_Time']);
            $leaveTime_temp = ($leaveTime_temp === NULL) ? '' : $leaveTime_temp;
                            
            $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));
            $userId = $this->Auth->user('EmpId');
            $leaveOwner = $records2[0];
                        
            $emailSubject = 'Leave Rejection : ' . $leaveOwner['User']['EmpName'];
            $message = 'Your leave is rejected by '.$role['User']['EmpName'].'.'
                    .'\nReason for Rejection : ' . $this->request->data['reject-comment']
                    .'\n\nLeave Details'
                    .'\n\nFrom Date : ' . $records[0]['leave_record']['From_Date']
                    .'\nTo Date : ' . $records[0]['leave_record']['To_Date']
                    .'\nLeave Type : ' . $leaveType_temp
                    .'\nLeave Time : ' . $leaveTime_temp
                    .'\nLeave Comment : ' . $records[0]['leave_record']['Leave_comment'];
            
            // Send mail to leave owner.
            $emailRecipients = array();
            $emailRecipients[] = $leaveOwner['User']['email'];
            MailUtil::sendMail($emailRecipients, $message, $emailSubject);

            // Send mail to persons who are responsible to leave approval. 
            $message1 = $leaveOwner['User']['EmpName']."'s  Leave is rejected by ".$role['User']['EmpName'].'.'
                    .'\nReason for Rejection : ' . $this->request->data['reject-comment']
                    .'\n\nLeave Details'
                    .'\nFrom Date : ' . $records[0]['leave_record']['From_Date']
                    .'\nTo Date : ' . $records[0]['leave_record']['To_Date']
                    .'\nLeave Type : ' . $leaveType_temp
                    .'\nLeave Time : ' . $leaveTime_temp
                    .'\nLeave Comment : ' . $leaveTime_temp;

            $condition_roleArr = array(
                    array('User.role'  => array('CEO', 'pm'))
                );            
            if ($leaveOwner['User']['role'] !== 'CEO' && $leaveOwner['User']['role'] !== 'pm') {
                $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                        'conditions' => array('emp_project.Eid' => $leaveOwner['User']['EmpId'])));

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
                    'AND'   => array('User.EmpId != ' => $leaveOwner['User']['EmpId'])
                ),
                'fields' => array('DISTINCT User.EmpId', 'User.EmpName', 'User.email')
            ));
            $recipientsEmails = array();
            if ($emailRecieversUserInfo) {
                foreach ($emailRecieversUserInfo as $key => $value) {
                    $recipientsEmails[] = $value['User']['email'];
                }
            }            
            MailUtil::sendMail($recipientsEmails, $message1, $emailSubject);            
        }   // END : if ($isAllowedToReject === true) 

        $this->redirect(array('action' => 'leave_request'));
    }

/////////////////////////////////////////////

    public function wfh_accept($id) {
        $userId = $this->Auth->user('EmpId');

        $exist = $this->Work_from_homes->find('first', array(
            'conditions' => array('Work_from_homes.id' => $id)));
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        if (!empty($exist)) {

            if ($exist['Work_from_homes']['accept_id'] <= $role['User']['group_id'] | (($exist['Work_from_homes']['accept_id']) == 6 && ($role['User']['group_id'] != 6))) {
                if ($exist['Work_from_homes']['wfh_states'] == 'accepted')
                    $this->Session->setFlash(__('Sorry, This WFH request has been already accepted.'));
                else
                    $this->Session->setFlash(__('Sorry, This WFH request has been already rejected.'));
            }else {
                $admin = $this->User->find('first', array('conditions' => array('User.role' => 'admin')));
                $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
                $this->Work_from_homes->id = $id;
                $this->Work_from_homes->saveField('wfh_states', "accepted");
                $this->Work_from_homes->saveField('accept_id', $role['User']['group_id']);
                $this->Session->setFlash(__('Work from home request is accepted'));

                $empids = $this->Work_from_homes->find('all', array(
                    'conditions' => array('Work_from_homes.id' => $id)));
                $empid = $empids[0]['Work_from_homes']['Eid'];

                //////////////////////send email
                $wfhOwner = $this->User->find('first', array('conditions' => array('User.EmpId' => $empid)));

                $wfhRequest = $this->Work_from_homes->find('first', array('conditions' => array('Work_from_homes.id' => $id)));

                $wfhTime_temp = $wfhRequest['Work_from_homes']['wfh_Time'];
                
                switch ($wfhTime_temp) {
                    case 'fullday':
                        $wfhTime_temp = 'Full Day';
                        break;
                    case '1sthalf':
                        $wfhTime_temp = '1st Half';
                        break;
                    case '2ndhalf':
                        $wfhTime_temp = '2nd Half';
                        break;
                    default:
                        break;
                }

                $emailSubject = 'Work From Home Approval : ' . $wfhOwner['User']['EmpName'];
                
                // Send mail to WFH owner
                $message2 = 'Your WFH request is accepted by ' . $role['User']['EmpName']  
                        . '\nFrom Date : ' . $wfhRequest['Work_from_homes']['From_Date']
                        . '\nTo Date : ' . $wfhRequest['Work_from_homes']['To_Date'] 
                        . '\nWFH Time : ' . $wfhTime_temp;
                MailUtil::sendMail(array($wfhOwner['User']['email']), $message2, $emailSubject);
                
                // Send notification to other authorized persons (CEO, pm, tl related to relevance projects.
                $message1 = $wfhOwner['User']['EmpName'] . '\'s Work From Home Request is approved by ' 
                        . $role['User']['EmpName'] 
                        . '\nFrom Date : ' . $wfhRequest['Work_from_homes']['From_Date']
                        . '\nTo Date : ' . $wfhRequest['Work_from_homes']['To_Date'] 
                        . '\nWFH Time : ' . $wfhTime_temp;

                $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                    'conditions' => array('emp_project.Eid' => $empid)));
                $projectsIdArr = array();
                if ($projects) {
                    foreach ($projects as $key => $value) {
                        $projectsIdArr[] = $value['emp_project']['Pid'];
                    }
                }          
                
                $condition_roleArr = array(
                    array('User.role'  => array('CEO', 'pm'))
                );
//                if ($wfhOwnerUserInfo['User']['role'] !== 'CEO' 
//                        && $wfhOwnerUserInfo['User']['role'] !== 'pm'
//                        && !empty($projectsIdArr)) {
//                    $condition_roleArr[] = array('AND' => array('User.role' => 'tl', 'emp.Pid'    => $projectsIdArr));
//                } 
                
                if ($wfhOwner['User']['role'] !== 'CEO' 
                        && $wfhOwner['User']['role'] !== 'pm'
                        && !empty($projectsIdArr)) {
                    $condition_roleArr[] = array('AND' => array('User.role' => 'tl', 'emp.Pid'    => $projectsIdArr));
                }
                
                $authorizedNotificationReceivers = $this->User->find('all', array(
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
                        'AND'   => array('User.EmpId != ' => $empid)
                    ),
                    'fields' => array('DISTINCT User.EmpId', 'User.EmpName', 'User.email')
                ));                 
                $recipientsArr = array();
                if ($authorizedNotificationReceivers) {
                    foreach ($authorizedNotificationReceivers as $key => $value) {
                        $recipientsArr[] = $value['User']['email'];
                    }
                } 
                MailUtil::sendMail($recipientsArr, $message1, $emailSubject);
            }
        } else {
            $this->Session->setFlash(__('Sorry, This WFH has been canceled'));
        }

        $this->redirect(array('action' => 'wfh_request'));
    }

//////////////////////////////////////////
    public function wfh_reject() {
        $wfhId = $this->request->data['wfh-request-id'];
        $rejectComment = $this->request->data['reject-comment'];
        $userId = $this->Auth->user('EmpId');

        $exist = $this->Work_from_homes->find('first', array(
            'conditions' => array('Work_from_homes.id' => $wfhId)));
        $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        if (!empty($exist)) {

            if ($exist['Work_from_homes']['accept_id'] <= $role['User']['group_id'] | (($exist['Work_from_homes']['accept_id']) == 6 && ($role['User']['group_id'] != 6))) {
                if ($exist['Work_from_homes']['wfh_states'] == 'accepted')
                    $this->Session->setFlash(__('Sorry, This WFH request has been already accepted.'));
                else
                    $this->Session->setFlash(__('Sorry, This WFH request has been already rejected.'));
            }else {
                $admin = $this->User->find('first', array('conditions' => array('User.role' => 'admin')));
                $role = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
                $this->Work_from_homes->id = $wfhId;
                $this->Work_from_homes->saveField('wfh_states', "rejected");
                $this->Work_from_homes->saveField('accept_id', $role['User']['group_id']);
                $this->Work_from_homes->saveField('approved_by', $userId);
                $this->Work_from_homes->saveField('reject_comment', $rejectComment);
                $this->Session->setFlash(__('Work from home request is rejected'));

                if ($role['User']['group_id'] == 2)
                    $author = 'Project manager';
                elseif ($role['User']['group_id'] == 3)
                    $author = 'Tech Lead';
                elseif ($role['User']['group_id'] == 6)
                    $author = 'CEO';
                
                $wfhTime_temp = LeaveUtil::getLeaveTimeName($exist['Work_from_homes']['wfh_Time']);
                $empid = $exist['Work_from_homes']['Eid'];
                $wfhOwnerUserInfo = $this->User->find('first', array('conditions' => array('User.EmpId' => $empid)));

                $emailSubject = 'Work From Home Rejection : '.$wfhOwnerUserInfo['User']['EmpName'];
                
                // Send mail to wfh applicant. 
                $message = 'Your Work From Home request is rejected by '.$role['User']['EmpName']
                        .'\nReason for Rejection : ' . $rejectComment
                        .'\n\nWork From Home Details'
                        .'\n\nFrom Date : ' . $exist['Work_from_homes']['From_Date']
                        .'\nTo Date : ' . $exist['Work_from_homes']['To_Date'] 
                        .'\nWFH Time : ' . $wfhTime_temp
                        .'\nWFH Comment : ' . $exist['Work_from_homes']['wfh_comment'];
                $recipientsArr = array($wfhOwnerUserInfo['User']['email']);
                
                MailUtil::sendMail($recipientsArr, $message, $emailSubject);
                
                // Send mail to relevant official
                $message1 = $wfhOwnerUserInfo['User']['EmpName']."'s Work From Home request is rejected by ".$role['User']['EmpName']
                        .'\nReason for Rejection : ' . $rejectComment
                        .'\n\nWork From Home Details'
                        .'\n\nFrom Date : ' . $exist['Work_from_homes']['From_Date']
                        .'\nTo Date : ' . $exist['Work_from_homes']['To_Date'] 
                        .'\nWFH Time : ' . $wfhTime_temp
                        .'\nWFH Comment : ' . $exist['Work_from_homes']['wfh_comment'];
 
                $condition_roleArr = array(
                        array('User.role'  => array('CEO', 'pm'))
                    );     
                if ($wfhOwnerUserInfo['User']['role'] !== 'CEO' && $wfhOwnerUserInfo['User']['role'] !== 'pm') {
                    $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                            'conditions' => array('emp_project.Eid' => $wfhOwnerUserInfo['User']['EmpId'])));

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
                        'AND'   => array('User.EmpId != ' => $wfhOwnerUserInfo['User']['EmpId'])
                    ),
                    'fields' => array('DISTINCT User.EmpId', 'User.EmpName', 'User.email')
                ));            
                $recipientsEmails = array();
                if ($emailRecieversUserInfo) {
                    foreach ($emailRecieversUserInfo as $key => $value) {
                        $recipientsEmails[] = $value['User']['email'];
                    }
                }            
                MailUtil::sendMail($recipientsEmails, $message1, $emailSubject);
            }
        } else {
            $this->Session->setFlash(__('Sorry, This WFH has been canceled'));
        }
        $this->redirect(array('action' => 'wfh_request'));
    }

////////////////////////////////////////////

    public function view_leave_record($id) {
        $userId = $id;
        //$uId=$this->Auth->user('EmpId');
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);


        $annual_real_days = 0;
        $casual_real_days = 0;

        $no_ann_lv = 0;
        $no_cas_lv = 0;

        //---leave assing-------//
        $userId = $id;
        $j_d = $this->User->find('first', array('conditions' => array('User.EmpId' => $userId)));
        $join_date = $j_d['User']['join_date'];
        $today = date("Y-m-d");
        $today_year = date("y", strtotime($today));
        $today_month = date("m", strtotime($today));
        $diff = abs(strtotime($today) - strtotime($join_date));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $qtr = 0;
        //debug($join_date);exit;

        $join_month = date("m", strtotime($join_date));
        $join_year = date("y", strtotime($join_date));
        //debug($join_year);exit;
        $this->User->id = $userId;
        //debug($this->User->id);
        //-------------annual leave update----------//
        //--defin quotar--//
//        if (1 <= $join_month && $join_month <= 3) {
//            if (1 <= $today_month && $today_month <= 3) {
//                $qtr = 1;
//            }
//        } elseif (4 <= $join_month && $join_month <= 6) {
//            if (4 <= $today_month && $today_month <= 6) {
//                $qtr = 2;
//            }
//        } elseif (7 <= $join_month && $join_month <= 9) {
//            if (7 <= $today_month && $today_month <= 9) {
//                $qtr = 3;
//            }
//        } else {
//            if (11 <= $today_month && $today_month <= 12) {
//                $qtr = 4;
//            }
//        }
        //--end---defin quotar--//

//        if (($today_year - $join_year) < 2) {
//            if (($today_year - $join_year) >= 1) {
//                if (1 <= $join_month && $join_month <= 3 && $qtr == 1) {
//                    $no_ann_lv = 14;
//                } elseif (4 <= $join_month && $join_month <= 6 && $qtr == 2) {
//                    $no_ann_lv = 10;
//                } elseif (7 <= $join_month && $join_month <= 9 && $qtr == 3) {
//                    $no_ann_lv = 7;
//                } elseif ($qtr == 4) {
//                    $no_ann_lv = 4;
//                }
//            } else {
//                //no annual leaves  
//                $no_ann_lv = 0;
//            }
//        } else {
//            //normal annual 14
//            $no_ann_lv = 14;
//        }

        //-------------casual leave update----------//
        //debug($years);
//        if ($join_date != null) {
//            if ($years < 2) {
//                if ($years < 1) {
//                    //1 leave for every 2 month 
//                    if ($join_year == $today_year) {//for same year
//                        if (($today_month - $join_month) >= 12) {
//                            $no_cas_lv = 6; /* debug('awa2'); */
//                        } elseif (($today_month - $join_month) >= 10) {
//                            $no_cas_lv = 5; /* debug('awa3'); */
//                        } elseif (($today_month - $join_month) >= 8) {
//                            $no_cas_lv = 4; /* debug('awa4'); */
//                        } elseif (($today_month - $join_month) >= 6) {
//                            $no_cas_lv = 3; /* debug('awa5'); */
//                        } elseif (($today_month - $join_month) >= 4) {
//                            $no_cas_lv = 2; /* debug('awa6'); */
//                        } elseif (($today_month - $join_month) >= 2) {
//                            $no_cas_lv = 1; /* debug('awa7'); */
//                        }
//                    } else { //for different year
//                        //if(($join_month-$today_month)>=12){$this->User->saveField('nof_cas_lv',6);/*debug('awa12');*/}
//                        //elseif(($join_month-$today_month)>=10){$this->User->saveField('nof_cas_lv',5);/*debug('awa13');*/}
//                        //elseif(($join_month-$today_month)>=8){$this->User->saveField('nof_cas_lv',4);/*debug('awa14');*/}
//                        //elseif(($join_month-$today_month)>=6){$this->User->saveField('nof_cas_lv',3);/*debug('awa15');*/}
//                        //elseif(($join_month-$today_month)>=4){$this->User->saveField('nof_cas_lv',2);/*debug('awa16');*/}
//                        //elseif(($join_month-$today_month)>=2){$this->User->saveField('nof_cas_lv',1);/*debug('awa17');*/}
//                        if (((12 - $join_month) + $today_month) >= 12) {
//                            $no_cas_lv = 6; /* debug('awa12'); */
//                        } elseif (((12 - $join_month) + $today_month) >= 10) {
//                            $no_cas_lv = 5; /* debug('awa13'); */
//                        } elseif (((12 - $join_month) + $today_month) >= 8) {
//                            $no_cas_lv = 4; /* debug('awa14'); */
//                        } elseif (((12 - $join_month) + $today_month) >= 6) {
//                            $no_cas_lv = 3; /* debug('awa15'); */
//                        } elseif (((12 - $join_month) + $today_month) >= 4) {
//                            $no_cas_lv = 2; /* debug('awa16'); */
//                        } elseif (((12 - $join_month) + $today_month) >= 2) {
//                            $no_cas_lv = 1; /* debug('awa17'); */
//                        }
//                    }
//                } else {
//                    //special task
//                    if (($join_year + 1) == $today_year) {
//                        $extra_month = 12 - $today_month;
//                        $ex_cas = (12 / 7) * $extra_month;
//                        $no_cas_lv = ($ex_cas + 6);
//                    }
//                }
//            } else {
//                //normal casual 7
//                $no_cas_lv = 7;
//            }
//        } else {
//            $no_cas_lv = 0;
//        }

        //--------- find leave balance ----------------//


        $employeeInfo = $this->User->find('first', 
                array('conditions' => array('User.EmpId' => $id)));
        if ($employeeInfo) {
            $no_ann_lv = $employeeInfo['User']['nof_ann_lv'];
            $no_cas_lv = $employeeInfo['User']['nof_cas_lv'];
            $no_liue_lv = $employeeInfo['User']['nof_liv_lv'];
        }
        
        $now = new DateTime();
        $currentYearStartDate = new DateTime($now->format("Y").'-01-01');
        $currentYearEndDate = new DateTime($now->format("Y").'-12-31');
        
        $employeeLeaves = $this->leave_record->find('all', array(
                'fields'     => array(
                    'leave_record.Eid', 
                    'leave_record.Leave_Type', 
                    'leave_record.Leave_states',
                    'SUM(leave_record.real_days) AS sumOfLeaves'
                ),
                'conditions' => array(
                    'leave_record.Eid'           => $id,
                    'leave_record.Leave_Type'    => array('annual', 'casual', 'live'),
                    'leave_record.From_Date <= ' => $currentYearEndDate->format('Y-m-d'),
                    'leave_record.To_Date >= '   => $currentYearStartDate->format('Y-m-d'),
                    'leave_record.Leave_states' => 'accepted' 
                ),
                'group'      => array('leave_record.Leave_Type')
            )
        );
        
        $annual_real_days = 0;
        $casual_real_days = 0;
        $liue_real_days = 0;
        
        if ($employeeLeaves) {
            foreach ($employeeLeaves as $key => $value) {
                switch ($value['leave_record']['Leave_Type']) {
                    case 'casual':
                        $casual_real_days = $value[0]['sumOfLeaves'];
                        break;
                    case 'annual':
                        $annual_real_days = $value[0]['sumOfLeaves'];
                        break;
                    case 'live':
                        $liue_real_days = $value[0]['sumOfLeaves'];
                        break;
                    default:
                        break;
                }
            }
        }

        $this->set('a2', $no_ann_lv - $annual_real_days);
        $this->set('a3', $no_cas_lv - $casual_real_days);
        $this->set('a4', $no_liue_lv - $liue_real_days);

        /////leave usage///////////////////
        $leave_usage = $this->leave_record->find('all', array('conditions' => array('leave_record.Eid' => $userId, 'leave_record.Leave_states' => 'accepted')));
        $this->set('used_leave', $leave_usage);
        ///////////////////////////employee name////////////////
        $emp_name = $this->User->find('all', array('conditions' => array('User.EmpId' => $userId)));
        $this->set('name', $emp_name[0]['User']['EmpName']);
        $this->set('eid', $userId);
    }

/////////////////////////////////////
    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    public function error() {
        
    }

////////////////////////////////////////////
    public function add_project() {
        $this->set('title_for_layout', 'Add Project');
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);

        $pro_details = $this->Project->find('all');
        $this->set('pro_detail', $pro_details);

        if ($this->Project->save($this->request->data)) {
            $this->Session->setFlash(__('Project details has been saved.'));
        }
    }
    public function add_employee() {
        $this->set('title_for_layout', 'Add Employee');
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        
        // collect projects
        $d = ($this->Project->find('list', array(
                    'fields' => array('Project.Pid', 'Project.pro_name')
        )));
        $this->set('pa', $d);
        
        // Collect employees
        $employeeList = $this->User->find('list', array(
            'fields'    => array('User.EmpId', 'User.EmpName')
        ));
        
        $this->set('employeeList', $employeeList);

        if($this->request->is('post')){

            $toEmp_ProjectsArray = $this->request->data;
            $toSaveArray = array();

            $counter = 0;
            foreach ($toEmp_ProjectsArray['projectName'] as $key1 => $value) {

                foreach ($toEmp_ProjectsArray['employeeName'] as $key2 => $value) {

                    $toSaveArray[$counter]['emp_project']['Pid'] = $toEmp_ProjectsArray['projectName'][$key1];
                    $toSaveArray[$counter]['emp_project']['Eid'] = $toEmp_ProjectsArray['employeeName'][$key2];
                    $counter++;
                }
            }
            unset($counter);

            if($this->emp_project->saveMany($toSaveArray)){
                $this->Session->setFlash(__('Successfully assigned employees to the project.'));

            }  else {
                //do nothing
                $this->Session->setFlash(__('Sorrry, Employee is not been assigned to the project.'));
            }
            $this->redirect(array('action' => 'add_employee'));
        }
    }

    ////////////////////////////
    public function leave_record($id) {
        $userId = $id;

        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);

        $sick_real_days = 0;
        $annual_real_days = 0;
        $casual_real_days = 0;

        //---leave assing-------//
        $uId = $this->Auth->user('EmpId');
        $leave_no = $this->User->find('first', array('conditions' => array('User.EmpId' => $uId)));
        $no_sick_lv = $leave_no['User']['nof_sick_lv'];
        $no_ann_lv = $leave_no['User']['nof_ann_lv'];
        $no_cas_lv = $leave_no['User']['nof_cas_lv'];

        //--------- find leave balance ----------------//


        $sick_blance = $this->leave_record->find('all', array('conditions' => array('leave_record.Eid' => $userId, 'leave_record.Leave_Type' => 'sick', 'leave_record.Leave_states' => 'accepted')));
        foreach ($sick_blance as $xx1) {
            $sick_real_days = ($sick_real_days + $xx1['leave_record']['real_days']);
        }//debug(7-$sick_real_days);
        $this->set('a1', $no_sick_lv - $sick_real_days);

        $annual_blance = $this->leave_record->find('all', array('conditions' => array('leave_record.Eid' => $userId, 'leave_record.Leave_Type' => 'annual', 'leave_record.Leave_states' => 'accepted')));
        foreach ($annual_blance as $xx2) {
            $annual_real_days = ($annual_real_days + $xx2['leave_record']['real_days']);
        }//debug(7-$annual_real_days);
        $this->set('a2', $no_ann_lv - $annual_real_days);

        $casual_blance = $this->leave_record->find('all', array('conditions' => array('leave_record.Eid' => $userId, 'leave_record.Leave_Type' => 'casual', 'leave_record.Leave_states' => 'accepted')));
        foreach ($casual_blance as $xx3) {
            $casual_real_days = ($casual_real_days + $xx3['leave_record']['real_days']);
        }//debug(7-$casual_real_days);
        $this->set('a3', $no_cas_lv - $casual_real_days);

        /////leave usage///////////////////
        $leave_usage = $this->leave_record->find('all', array('conditions' => array('leave_record.Eid' => $userId, 'leave_record.Leave_states' => 'accepted')));
        $this->set('used_leave', $leave_usage);
        ///////////////////////////employee name////////////////
        $emp_name = $this->User->find('all', array('conditions' => array('User.EmpId' => $userId)));
        $this->set('name', $emp_name[0]['User']['EmpName']);
        $this->set('eid', $userId);
    }

    public function add_holidays() {
        ////////////////////////////
        $this->set('title_for_layout', 'Add Leaves');

        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $this->set('users', $this->User->find('all'));
        $this->set('users', $this->paginate());
        ////////////////////////////

        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $this->Event->create();
        if ($this->request->is('post')) {
            if (empty($this->data['admins']['Holidays']['name'])) {
                unset($this->request->data['admins']['Holidays']);
                //debug($this->request->data['admins']['Holidays']);
            }


            if (!empty($this->data['admins']['Holidays']['name'])) {
                $file = $this->data['admins']['Holidays'];
                //debug($file);
                $ary_ext = array('csv'); //array of allowed extensions
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                if (in_array($ext, $ary_ext)) {
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . 'uploads/' . $file['name']);
                    $this->request->data['admins']['Holidays'] = $file['name'];
                } else {
                    $this->Session->setFlash('Can not add holidays');
                    $this->redirect(array('action' => 'add_leaves'));
                }

                //$file_path = $file['name'];
                //debug($file_path);
                $this->redirect(array('action' => 'upload', $file['name']));
            } else
                $this->Session->setFlash('Can not add holidays');
            $this->redirect(array('action' => 'add_leaves'));
        }
    }

    public function upload($x) {
        App::import("Vendor", "parsecsv");

        $csv = new parseCSV();
        $file_path = WWW_ROOT . 'uploads/' . $x;

        $csv->auto($file_path);

        $x = 1;
        foreach ($csv->data as $row) {
            $this->Event->id = $x;
            $dt = new DateTime();

            $start = $row['start'];
            $end = $row['end'];

            $start1 = $dt->createFromFormat('m/d/Y', $start);
            $start2 = $start1->format('Y-m-d');

            $end1 = $dt->createFromFormat('m/d/Y', $end);
            $end2 = $end1->format('Y-m-d');

            $this->Event->saveField('event_type_id', $row['event_type_id']);
            $this->Event->saveField('title', $row['title']);
            $this->Event->saveField('details', $row['details']);
            $this->Event->saveField('start', $start2);
            $this->Event->saveField('end', $end2);
            $this->Event->saveField('time', $row['time']);
            $x = $x + 1;
        }

        $this->Session->setFlash('Successfully updated1');
        $this->redirect(array('action' => 'add_holidays'));

        //debug($csv->data);
    }

    public function email_leave_accept($ss) {
        var_dump('***************************************');
        exit();

        if (!empty($ss)) {
            $decrypted = $ss ^ 18544332;
            $this->leave_record->id = $decrypted;


            $empids = $this->leave_record->find('all', array(
                'conditions' => array('leave_record.id' => $decrypted)));
            $empid = $empids[0]['leave_record']['Eid'];
            $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                'conditions' => array('emp_project.Eid' => $empid)));

            //debug($projects);
            if (empty($projects)) {
                
            } else {

                for ($i = 0; $i < count($projects); $i++) {
                    $x[$i] = $this->User->find('all', array(
                        'joins' => array(
                            array(
                                'table' => 'emp_projects',
                                'alias' => 'emp',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'User.EmpId = emp.Eid'
                                )
                            )
                        ),
                        'conditions' => array(
                            'emp.Pid' => $projects[$i]['emp_project']['Pid']
                        ),
                        'fields' => array('User.*', 'emp.*')
                    ));

                    //debug($x);
                }
//////////////////////send email
                $records = $this->leave_record->find('all', array('conditions' => array('leave_record.id' => $decrypted)));
                $message1 = 'Employee id : ' . $empid . ', will have a leave ' . '  ' . 'From Date : ' . $records[0]['leave_record']['From_Date']
                        . '  ' . 'To Date : ' . $records[0]['leave_record']['To_Date'] . ' ' . 'Leave Type : ' . $records[0]['leave_record']['Leave_Type'] . '  ' . 'Leave Time : ' . $records[0]['leave_record']['Leave_Time'];

                $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));
                $message2 = 'Your leave request is accepted' . 'From Date : ' . $records[0]['leave_record']['From_Date']
                        . '  ' . 'To Date : ' . $records[0]['leave_record']['To_Date'] . ' Leave Time : ' . $records[0]['leave_record']['Leave_Time'];

                App::uses('CakeEmail', 'Network/Email');

                //debug($records2);
                for ($c = 0; $c < count($x[0]); $c++) {


                    $email = new CakeEmail('gmail');
                    $email->from('admin@lms.nexttestsite.com');
                    $email->to($x[0][$c]['User']['email']);
                    $email->subject('Notification');

                    if ($x[0][$c]['User']['email'] == $records2[0]['User']['email']) {
                        $email->send($message2);
                    } else {
                        $email->send($message1);
                    }
                }

//debug($x);
            }
//$this->redirect(array('action' => 'notify'));
        }
    }

    public function email_leave_reject($ss) {
        if (!empty($ss)) {
            $decrypted = $ss ^ 18544332;
            $this->leave_record->id = $decrypted;


            $records = $this->leave_record->find('all', array('conditions' => array('leave_record.id' => $decrypted)));
            $messages = 'Your leave request is rejected.' . ' From Date : ' . $records[0]['leave_record']['From_Date']
                    . '  ' . 'To Date : ' . $records[0]['leave_record']['To_Date'] . ' Leave Time : ' . $records[0]['leave_record']['Leave_Time'];



            $empids = $this->leave_record->find('all', array(
                'conditions' => array('leave_record.id' => $decrypted)));
            $empid = $empids[0]['leave_record']['Eid'];
            $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));

            App::uses('CakeEmail', 'Network/Email');

            $email = new CakeEmail('gmail');
            $email->from('admin@lms.nexttestsite.com');
            $email->to($records2[0]['User']['email']);
            $email->subject('Notification');


            $email->send($messages);

            //$this->redirect(array('action' => 'notify')); 
        }
    }

    public function email_wfh_accept($ss = null) {
        if (!empty($ss)) {
            $decrypted = $ss ^ 18544332;
            $this->Work_from_homes->id = $decrypted;


            $empids = $this->Work_from_homes->find('all', array(
                'conditions' => array('Work_from_homes.id' => $decrypted)));
            $empid = $empids[0]['Work_from_homes']['Eid'];
            $projects = $this->emp_project->find('all', array('fields' => 'DISTINCT emp_project.Pid',
                'conditions' => array('emp_project.Eid' => $empid)));

            //debug($projects);
            if (empty($projects)) {
                
            } else {

                for ($i = 0; $i < count($projects); $i++) {
                    $x[$i] = $this->User->find('all', array(
                        'joins' => array(
                            array(
                                'table' => 'emp_projects',
                                'alias' => 'emp',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'User.EmpId = emp.Eid'
                                )
                            )
                        ),
                        'conditions' => array(
                            'emp.Pid' => $projects[$i]['emp_project']['Pid']
                        ),
                        'fields' => array('User.*', 'emp.*')
                    ));

                    //debug($x);
                }
//////////////////////send email
                $records = $this->Work_from_homes->find('all', array('conditions' => array('Work_from_homes.id' => $decrypted)));
                $message1 = 'Employee id : ' . $empid . ', will work in home ' . '  ' . 'From Date : ' . $records[0]['Work_from_homes']['From_Date']
                        . '  ' . 'To Date : ' . $records[0]['Work_from_homes']['To_Date'] . ' WFH Time : ' . $records[0]['Work_from_homes']['wfh_Time'];

                $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));
                $message2 = 'Your WFH request is accepted' . 'From Date : ' . $records[0]['Work_from_homes']['From_Date']
                        . '  ' . 'To Date : ' . $records[0]['Work_from_homes']['To_Date'] . ' WFH Time : ' . $records[0]['Work_from_homes']['wfh_Time'];

                App::uses('CakeEmail', 'Network/Email');

                //debug($records2);
                for ($c = 0; $c < count($x[0]); $c++) {


                    $email = new CakeEmail('gmail');
                    $email->from('admin@lms.nexttestsite.com');
                    $email->to($x[0][$c]['User']['email']);
                    $email->subject('Notification');

                    if ($x[0][$c]['User']['email'] == $records2[0]['User']['email']) {
                        $email->send($message2);
                    } else {
                        $email->send($message1);
                    }
                }

//debug($x);
            }
            // $this->redirect(array('action' => 'notify')); 
        }
    }

    public function email_wfh_reject($ss) {
        if (!empty($ss)) {
            $decrypted = $ss ^ 18544332;
            $this->Work_from_homes->id = $decrypted;

            $records = $this->Work_from_homes->find('all', array('conditions' => array('Work_from_homes.id' => $decrypted)));
            $messages = 'Your WFH request is rejected.' . 'From Date : ' . $records[0]['Work_from_homes']['From_Date']
                    . '  ' . 'To Date : ' . $records[0]['Work_from_homes']['To_Date'] . 'WFH Time : ' . $records[0]['Work_from_homes']['wfh_Time'];


            $empids = $this->Work_from_homes->find('all', array(
                'conditions' => array('Work_from_homes.id' => $decrypted)));
            $empid = $empids[0]['Work_from_homes']['Eid'];
            $records2 = $this->User->find('all', array('conditions' => array('User.EmpId' => $empid)));

            App::uses('CakeEmail', 'Network/Email');

            $email = new CakeEmail('gmail');
            $email->from('admin@lms.nexttestsite.com');
            $email->to('tharangalakma90@gmail.com');
            $email->subject('Notification');


            $email->send($messages);

            //$this->redirect(array('action' => 'notify')); 
        }
    }

    public function mail_send() {





        App::uses('CakeEmail', 'Network/Email');



        $query = $this->email_details->find('all', array('conditions' => array('email_details.status' => null)));



        for ($i = 0; $i < count($query); $i++) {

            $email = new CakeEmail('gmail');
            $email->template('default', 'default');
            $email->emailFormat('html');
            $email->from('admin@softcodeit.net');
            $email->to($query[$i]['email_details']['email']);
            $email->subject($query[$i]['email_details']['subject']);


            $email->send($query[$i]['email_details']['message']);
            $this->email_details->id = ($query[$i]['email_details']['ID']);
            $this->email_details->saveField('status', 'sent');
        }
    }

    public function getValDays() {
        $this->autoLayout = false;
        $this->autoRender = false;
        $fd = $this->request->data('fd');
        $td = $this->request->data('td');


        $diff = abs(strtotime($td) - strtotime($fd));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24)) + 1;

        //$events = $this->events->find('all');


        if ($fd == $td) {
            $events = $this->Event->find('first', array('conditions' => array('Event.start' => $fd)));
            if (!empty($events))
                $days--;
        }
        else {

            $events = $this->Event->find('all', array('conditions' => array('Event.start >=' => $fd, 'Event.start <=' => $td)));
            $holidays = count($events);
            $days = $days - $holidays;
        }
        return $days;
    }

    public function emails() {




        $records = $this->leave_record->find('all');


        debug($records);

        exit();
    }

    public function admin_edit($id = null, $role = null) {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $us = $this->User->find('all', array(
            'conditions' => array('User.id' => $id)));
        $this->set('users', $us);

        $this->set('user_role', $role);



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

    public function admin_user_leave_edit($id = null) {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);
        $us = $this->User->find('first', array('conditions' => array('User.id' => $id)));
        $this->set('no_ann_lv', $us['User']['nof_ann_lv']);
        $this->set('no_cas_lv', $us['User']['nof_cas_lv']);
        $this->set('no_liv_lv', $us['User']['nof_liv_lv']);

        //$this->set('users', $us);  
        //$this->set('user_role', $role);
        //debug($us['User']['nof_ann_lv']);exit;



        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->id = $id;

            if (!empty($this->request->data)) {
                $xa = $this->request->data['no_ann_lv'];
                $xc = $this->request->data['no_cas_lv'];
                $xl = $this->request->data['no_liv_lv'];
                //debug($this->User->id);exit;
                //$this->User->saveField('no_ann_lv',$xa);
                //if ($this->User->save($this->request->data)) {
                //$this->request->data['EmpId'] = $id;        
                $this->User->saveField('nof_ann_lv', $xa);
                $this->User->saveField('nof_cas_lv', $xc);
                $this->User->saveField('nof_liv_lv', $xl);
                $this->Session->setFlash(__('Updated.'));
                return $this->redirect(array('action' => 'add_holidays'));
            } else
                $this->Session->setFlash(__('Unable to update.'));
        }
    }

    function getAjaxUsers() {
        $this->autoLayout = false;
        $this->autoRender = false;
        $name = $this->request->data('name');


        $result = "";

        $uname = $this->User->find('all', array('conditions' => array('User.EmpName LIKE' => '%' . $name . '%')));

        if ($uname) {

            $i = 0;
            foreach ($uname as $names) {
                $result.="<a id ='noline' href='http://lms.softcodeit.net/___softcodeit/admins/view_leave_record/" . $names['User']['EmpId'] . "'>" . $names['User']['EmpName'] . "</a> " . "</br>";

                $i++;
                if ($i == 5)
                    break;
            }
        }



        return $result;
    }

    /**
     *  This is ajax request.
     */
    public function isUsernameAvailable() {
        $this->autoLayout = false;
        $this->autoRender = false;

        $emp_username = $this->request->data('username');
        $response = array();
        $errorMsg = array();

        $isValid = true;
        if (!isset($emp_username) || empty($emp_username)) {
            $isValid = false;
            $response['error_username'] = 'Username can not be empty.';
        }

        if ($isValid === true) {
            // Check username availability.
            $userForUsername = $this->User->find('first', array('conditions' => array('User.username' => $emp_username)));
            $isUsernameInSystem = false;
            if (!empty($userForUsername)) {
                $isUsernameInSystem = true;
            } else {
                // NOTHING HAPPENS
            }
            
            if ($isUsernameInSystem === false) {
                $response = array(
                    'result' => 'success');
            } else {
                $response['result'] = 'error';
                
                if ($isUsernameInSystem === true) {
                    $response['error_username'] = 'Username is available in system.';
                }
            }
        } else {
            $response['result'] = 'error';
        }
        echo json_encode($response);
    }

    public function viewProjectDetails()
    {
        $username = $this->Auth->user('EmpName');
        $this->set('loguser', $username);

        //fetching project list form db
        $this->loadModel('Project');
        $this->loadModel('emp_project');
        $projectList = $this->Project->find('list', array('fields' =>array('Project.Pid', 'Project.pro_name')));
        $this->set('allProjects', $projectList);

        //fetching employee Surname_RestName from ( virualField )
        $finalNameList = $this->User->find('list', array(
            "fields"=>array('EmpID','Surname_RestName'),
            "order"=>array('Surname_RestName'),
        ));

        $this->set('allemployees', $finalNameList);

        $results = $this->User->find("list", array("fields"=>array('EmpId')));
        $employeeList = $this->User->find('all', array('conditions', array('User.role NOT IN ' => array('CEO'))));

        $this->set('employeeList', $employeeList);

        $selectedProjects = array();

       if (isset($this->request->query['projectName'])) {
            $selectedProjects = $this->request->query['projectName'];
        }

        $this->set('selectedProjects', $selectedProjects);
        $conditions = array();

        if (isset($this->request->query['employeeId']) && $this->request->query['employeeId'] != 'all' && $this->request->query['projectName'] == '') {
            $conditions =  array(
                'emp.Eid' => $this->request->query['employeeId']
            );
        }

        $employeeValue = array();
        $employeeValue = $this->request->query['employeeId'];
        $projectValue = $this->request->query['projectName'];
        foreach($employeeValue as $value){

            if(isset($value) && $value != 'all' && $projectValue == ''){
                $conditions =  array(
                    'emp.Eid' => $employeeValue
                );
            }

            foreach($projectValue as $valuelist){

                if(isset($valuelist) && $valuelist != ''){
                    $conditions =  array(
                        'pro.Pid' => $projectValue

                    );
                }
            }
        }if(isset($value) && $value != 'all' && isset($projectValue) && $projectValue != ''){

        $conditions =  array(
            'pro.Pid' => $projectValue,
            'emp.Eid' => $employeeValue
        );
    }

        $project_details = $this->User->find('all', array(
            'fields' => array('User.EmpName', 'pro.pro_name', 'emp.id','pro.Pid'),
            'joins' => array(
                array(
                    'table' => 'emp_projects',
                    'alias' => 'emp',
                    'type' => 'INNER',
                    'conditions' => array(
                        'emp.Eid = User.EmpId'
                    )
                ),
                array(
                    'table' => 'projects',
                    'alias' => 'pro',
                    'type' => 'INNER',
                    'conditions' => array(
                        'pro.Pid = emp.Pid'
                    )
                )
            ),
            'conditions' => $conditions
        ));

        $this->set('project_detail', $project_details);

    }

    public function deleteProject(){
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $response = array();
        $tdid = $this->request->query['tdid'];

        if (!$this->request->query) {
            $response['success'] = FALSE;
            $response['msg'] = 'Could get the data';
            echo json_encode($response);
            exit();
        }

        if ($this->request->query) {
            $this->loadModel('emp_project');

            $empProject = $this->emp_project->find('first', array('conditions' => array('id' => $tdid)));

            $this->emp_project->id = $empProject['emp_project']['id'];
            $this->emp_project->delete($tdid);
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
