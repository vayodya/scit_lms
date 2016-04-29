<?php

App::uses('Controller', 'Controller');


class AppController extends Controller {
	
        
        
public $components = array('Session','Auth' => array(
            'loginRedirect' => array('controller' => 'users', 'action' => 'home'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
        )
    );

public function beforeRender() {
    $this->response->disableCache();
   
}

    public function beforeFilter() {
$role = $this->Auth->user('role'); //If you are using Auth
     //$this->Session->read('User.role'); // If you are using a normal login session.
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
 else {
        $this->set('is_admin', false);
        $this->set('is_pm', false);
     }
    }
    
    public function isAuthorized($user) {
    // Admin can access every action
    if (isset($user['role']) && $user['role'] === 'admin') {
        return true;
    }

    // Default deny
    return false;
    }
}
?>
