<?php
class ContactsController extends AppController {
    public $helperss = array('Html', 'Form','Csv');
    var $uses=array('leave_record','User','emp_project','Project','temp_detail','events','Work_from_homes');
    public function contact(){
    }
    public function download(){
    $userId = $this->Auth->user('EmpId');
    $this->set('orders', $this->leave_record->find('all',array('fileds' => array('leave_record.id'),'conditions' => array('leave_record.Eid' => $userId))));
    $this->layout = null;
    $this->autoLayout = false;
    Configure::write('debug', '0');
    }
    public function download_wfh(){
    $userId = $this->Auth->user('EmpId');
    $this->set('orders2', $this->Work_from_homes->find('all',array('conditions' => array('Work_from_homes.Eid' => $userId))));
    $this->layout = null;
    $this->autoLayout = false;
    Configure::write('debug', '0');
    }
    public function download_view_user(){
    $this->set('orders3', $this->User->find('all'));
    $this->layout = null;
    $this->autoLayout = false;
    Configure::write('debug', '0');
    }
    public function download_adminview_leave(){
    $this->set('orders4', $this->leave_record->find('all',array('conditions' => array('leave_record.Leave_states' => 'accepted'))));
    $this->layout = null;
    $this->autoLayout = false;
    Configure::write('debug', '0');
    }
    public function download_adminview_wfh(){
    $this->set('orders5', $this->Work_from_homes->find('all',array('conditions' => array('Work_from_homes.wfh_states' => 'accepted'))));
    $this->layout = null;
    $this->autoLayout = false;
    Configure::write('debug', '0');
    }
}
?>