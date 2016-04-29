<?php
class EmployeesController extends AppController{
     var $name = 'employees';
    public $helpers = array('Html', 'Form');
    public $components = array('Session');
   
    
    //index page action
    public function index() {
        
        //$uname = $this->data['employee']['username'];
        //print personal details
                         $details = $this->Employee->find('all', array(
                                          'conditions' => array('Employee.user_name' => 'qa')));
             
                        $this->set('detail',$details);
        
        
    }
    
    //view page action
   /** public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post pradeep,this page not found..'));
        }
        
        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post pradeep'));
        }
        $this->set('post', $post);
    }
    
    //add page action
    public function add() {
        if ($this->request->is('post')) {
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');
            $this->Post->create();
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('Your post has been saved.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Unable to add your post.'));
            }
        }
    }
    
    //edit page action
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Post->id = $id;
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('Your post has been updated.'));
                $this->redirect(array('action' => 'index'));
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
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

         if ($this->Post->delete($id)) {
            $this->Session->setFlash(__('The post with id: %s has been deleted.', $id));
            $this->redirect(array('action' => 'index'));
        }
    }
    
    public function isAuthorized($user) {
    // All registered users can add posts
    if ($this->action === 'add') {
        return true;
    }

    // The owner of a post can edit and delete it
    if (in_array($this->action, array('edit', 'delete'))) {
        $postId = $this->request->params['pass'][0];
        if ($this->Post->isOwnedBy($postId, $user['id'])) {
            return true;
        }
    }

    return parent::isAuthorized($user);
}**/
    
    
}
?>
