<?php

App::uses('Controller', 'Controller');


class AppController extends Controller {
	
        
        

        
public $components = array(
    'Acl',
    'Auth' => array(
        'authorize' => array(
            'Actions' => array('actionPath' => 'controllers')
        )
    ),
    'Session'
);    

public function beforeRender() {
    $this->response->disableCache();
   
}

    public function beforeFilter() {
        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Auth->loginRedirect = array('plugin' => NULL,'controller' => 'users', 'action' => 'home');
$role = $this->Auth->user('role'); //If you are using Auth
     
      if ($role == 'admin') { 
         $this->set('role', $role); 
     } 

     if($role == 'admin')
     {
         $this->set('is_admin', true);
          $this->set('is_pm', false);
     }
     else if($role == 'pm')
     {
         $this->set('is_pm', true);
         $this->set('is_admin', false);
     }
      else if($role == 'CEO')
     {
         $this->set('is_CEO', true);
         $this->set('is_admin', false);
         $this->set('is_pm', false);
         $this->set('is_tl', false);
     }
      else if($role == 'tl')
     {
         $this->set('is_CEO', false);
         $this->set('is_admin', false);
         $this->set('is_pm', false);
         $this->set('is_tl', true);
     }
 else {
        $this->set('is_admin', false);
        $this->set('is_pm', false);
         $this->set('is_CEO', false);
         $this->set('is_tl', false);
     }
    }
    
    public function isAuthorized($user) {
    /** Admin can access every action
    if (isset($user['role']) && $user['role'] === 'admin') {
        return true;
    }

    // Default deny
    return false;**/
    }
}
?>