<?php class Group extends AppModel {
    var $name = 'Group';
    var $hasMany = array('User');
    public $actsAs = array('Acl' => array('type' => 'requester'));

    public function parentNode() {
        return null;
    }
}
?>