<?php
class SearchComponent extends Component {
   
    var $controller = null;
 
    function initialize(&$controller)
    {
        $this->controller = $controller;
    }
   
    function getConditions(){
        $conditions = array();
      $data= empty($this->controller->params['named']) ? $this->controller->params['url'] : $this->controller->params['named'] ;
	  $this->controller->{$this->controller->modelClass}->schema();	  
        foreach($data as $key=>$value){
            if(isset($this->controller->{$this->controller->modelClass}->_schema[$key]) && !empty($value)){
                switch($this->controller->{$this->controller->modelClass}->_schema[$key]['type']){
                    case "string":
                        $conditions[$this->controller->modelClass . "." .$key . " LIKE"] = "%".trim($value)."%";						
                        break;
                    case "integer":
                        $conditions[$this->controller->modelClass . "." .$key] =  $value;
                        break;
                    case "date":
                        if(isset($this->controller->params['named'][$key."_fromdate"])){
                            $from = date("Y-m-d", strtotime( $this->controller->params['named'][$key."_fromdate"] ));
                            $conditions[$this->controller->modelClass.".".$key." >="] = trim($from);
                        }
                        if(isset($this->controller->params['named'][$key."_todate"])){
                            $to = date("Y-m-d", strtotime($this->params['named'][$key."_todate"]));
                            $conditions[$this->controller->modelClass.".".$key." <="] = $to;
                        }
                }
            }
        }
		return $conditions;
		
    }
	
}
?>