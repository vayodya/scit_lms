<?php
// app/Controller/UsersController.php
class DocumentsController extends AppController {
    var $name = 'Documents';
    var  $uses = array('User','Admin','leave_record','Work_from_homes','temp_detail','Temp_user');
var $components='Search';
   
 
    function search() {
        // the page we will redirect to
        $url['action'] = 'index';
         
        // build a URL will all the search elements in it
        // the resulting URL will be
        // example.com/cake/posts/index/Search.keywords:mykeyword/Search.tag_id:3
        foreach ($this->data as $k=>$v){
            foreach ($v as $kk=>$vv){
                $url[$k.'.'.$kk]=$vv;
            }
        }
 
        // redirect the user to the url
        $this->redirect($url, null, true);
    }
   
/**
 * index method
 *
 * @return void
 */
    public function index() {
       
        $title = array();
         if(isset($this->passedArgs['Search.EmpName'])) {
            $this->paginate['conditions'][]['User.EmpName LIKE'] = str_replace('*','%',$this->passedArgs['Search.EmpName']);
            $this->data['Search']['EmpName'] = $this->passedArgs['Search.EmpName'];
            $title[] = __('Name',true).': '.$this->passedArgs['Search.EmpName'];
        }
    }

    
 
   }
  
?>
