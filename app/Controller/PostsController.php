<?php 
class PostsController extends  AppController{
	var $name = 'Posts';
	public $helpers=array('Html','Form','Session');
	public $components=array('Session');
	

	public function index(){
		$this->set('posts',$this->Post->find('all'));
	}
	
	public function view($id=null){
		if(!$id)
		{
			throw new NotFoundException(__('Invalid Post'));
		}
		$post=$this->Post->findById($id);
		if(!$post)
		{
			throw new NotFoundException(__('Invalid Post'));
		}
		$this->set('post',$post);
	}
	
	public function add() {
		$this->Post->create();
		if ($this->request->is('post')) {
				for($i=1;$i<4;$i++)
				{
					if(empty($this->data['Post']['image'.$i]['name'])){
						unset($this->request->data['Post']['image'.$i]);
					}
					
					if(!empty($this->data['Post']['image'.$i]['name']))
					{
						$file=$this->data['Post']['image'.$i];
						$ary_ext=array('jpg','jpeg','gif','png'); //array of allowed extensions
						$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
						if(in_array($ext, $ary_ext))
						{
							move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/' . mktime().$file['name']	);
							$this->request->data['Post']['image'.$i] = mktime().$file['name'];
						}
					}
					
				}
			
 			if ($this->Post->save($this->request->data)) 
 			{
				$this->Session->setFlash('Your post has been saved.');
				$this->redirect(array('action' => 'index'));
			}
			else 
			{
				$this->Session->setFlash('Unable to add your post.');
			}
		}
	}
	
	
	public function edit($id=null){
		if(!$id)
		{
			throw new NotFoundException(__('Invalid Post'));
		}

		$post=$this->Post->findById($id);
		if(!$post)
		{
			throw new NotFoundException(__('Invalid Post'));
		}
		
		if(!empty($this->data))
		{
				$this->Post->id=$id;
				for($i=1;$i<4;$i++)
				{
				if(empty($this->data['Post']['image'.$i]['name'])){
						unset($this->request->data['Post']['image'.$i]);
					}
					if(!empty($this->data['Post']['image'.$i]['name']))
					{
							if(file_exists("img/uploads/posts/".$this->data['Post']['hiddenimage'.$i])){
 								unlink("img/uploads/posts/".$this->data['Post']['hiddenimage'.$i]);								
				     }
						
						$file=$this->data['Post']['image'.$i];
						$ary_ext=array('jpg','jpeg','gif','png'); //array of allowed extensions
						$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
						
						if(in_array($ext, $ary_ext))
						{
							move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/uploads/posts/' . mktime().$file['name']);
							$this->request->data['Post']['image'.$i] = mktime().$file['name'];
						}
					}
				}
				
				if($this->Post->save($this->request->data))
				{
					$this->Session->setFlash('Your Post has been Updated');			
					$this->redirect(array('action'=>'index'));	
				}
				else
				{
					$this->Session->setFlash('Unable to update your post.');
				}
		}
		
		if(!$this->request->data){
			$this->request->data=$post;
		}
	}
	
	public function delete($id=null){
		if($this->request->is('get')){
			throw new MethodNotAllowedException();
		}
		if($this->Post->delete($id)){
			$this->Session->setFlash('Post having ID : '.$id.' has been deleted');
			$this->redirect(array('action'=>'index'));
		}
	}
}
?>