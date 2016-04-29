<?php

class GroupsController extends AppController {
 
    var $name = 'Groups';
    var $helpers = array('Html', 'Form');
 
    
 
    function add() {
        if (!empty($this->data)) {
            $this->Group->create();
            if ($this->Group->save($this->data)) {
                $this->Session->setFlash(__('The Group has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Group could not be saved. Please, try again.', true));
            }
        }
    }
 
    
}
?>
