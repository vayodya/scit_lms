<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('AclComponent', 'Controller/Component');
class User extends AppModel {
    
    var $name = 'User';
     public $belongsTo = array('Group');
    public $actsAs = array('Acl' => array('type' => 'requester' ));

    //making a virtual field for get surname infront of the rest of the name
    var $virtualFields = array(
//        'Surname' => "SELECT SUBSTRING_INDEX(EmpName, ' ', -1)as Surname FROM users"
//        'Surname' => "SUBSTRING_INDEX(EmpName, ' ', -1)",
//        'RestName' => "TRIM(TRAILING SUBSTRING_INDEX( EmpName,  ' ', -1 ) FROM EmpName )",
//        'Surname_RestName'=>"CONCAT(Surname,RestName)",
        'Surname_RestName'=>"CONCAT(SUBSTRING_INDEX(EmpName, ' ', -1),', ' ,TRIM(TRAILING SUBSTRING_INDEX( EmpName,  ' ', -1 ) FROM EmpName ))"
    );
    
        public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }
    
    public function bindNode($user) {
    return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
}
    
    public $validate = array(
        
        
        'username' => array(
            'required' => array(
                'rule' => array('isUnique'),
                'message' => 'A unique username is required'
            )
        ),
        
        'pwd' => array(
            'required' => array(
               
                'message' => 'A password is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('minLength', '8'),
                'message' => 'Password size must be more than 8 characters'
            )
        ),
        'pro_picture' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A profile picture is required'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'normal','pm','tl','CEO')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            ) 
        ),
       /** 'EmpId' => array(
            'valid' => array(
                'rule' => array('isUnique',array(
                'message' => 'ID must be unique and numeric'),'isNumeric',array('message' => 'wrong'))
                
            )
        ),**/
        
        'EmpId' => array(
        'isNumeric' => array(
            'rule' => 'numeric',
            'message' =>'ID must be a numeric value',
            'last' =>true
        ),
        'isUnique' => array(
            'rule' => 'isUnique',
            'message' => 'ID must be an unique value'
        )
    ),
        
        
        'email' => array(
            'valid' => array(
                'rule' => array('email'),
                'message' => 'Please Enter valid Email address'
                
            )
        ),
        'captcha'=>array(
            'rule' => array('matchCaptcha'),
            'message'=>'Failed validating human check.'
	),
    );
    

    public function beforeSave($options = array()){
        if (isset($this->data[$this->alias]['password'])){
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }
    
    function matchCaptcha($inputValue){
        return $inputValue['captcha']==$this->getCaptcha(); //return true or false after comparing submitted value with set value of captcha
    }

    function setCaptcha($value){
            $this->captcha = $value; //setting captcha value
    }

    function getCaptcha(){
            return $this->captcha; //getting captcha value
    }

    function cptr($input){ ///------my new captcha validate function ----------//
        if(($this->captcha) == $input){return true;}else false;
    }
}
?>