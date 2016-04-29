<?php
/*
 * Controller/EventsController.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

class EventsController extends FullCalendarAppController {

	var $name = 'Events';
        
        //var $uses = array('leave_record','Event');
        
        var $uses = array('Event','leave_record','User','event_types');
        
         
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('feed1','feed2');
    }

        var $paginate = array(
            'limit' => 15
        );

        function index() {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
		//$this->Event->recursive = 1;
                
                //debug($this->paginate());
                //exit();
		//$this->set('events', $this->paginate());
            
            $this->paginate = array(
                 'joins' => array(
                  array(
                      'table' => 'event_types',
                      'alias' => 'EventType',
                      'type' => 'INNER',
                      'limit' => 15,
                      'conditions' => array(
                      'Event.event_type_id = EventType.id'
                       )
                  )
              ),
                    
                    
                
             'fields' => array('Event.*', 'EventType.*')
   
             );
            
           
            
            //$ev1  = $this->paginate($ev);
               $this->set('events', $this->paginate('Event'));
            
	}
	

	function view($id = null) {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
		if (!$id) {
			$this->Session->setFlash(__('Invalid event', true));
			$this->redirect(array('action' => 'index'));
		}
		//$this->set('event', $this->Event->read(null, $id));
                
                $ev = $this->Event->find('first', array(
                 'joins' => array(
                  array(
                      'table' => 'event_types',
                      'alias' => 'EventType',
                      'type' => 'INNER',
           
                      'conditions' => array(
                      'Event.event_type_id = EventType.id'
                       )
                  )
              ),
                    
                    'conditions' => array('Event.id' => $id),
                
             'fields' => array('Event.*', 'EventType.*')
   
             ));
               $this->set('event', $ev);
	}

	function add() {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
		if (!empty($this->data)) {
			$this->Event->create();
			if ($this->Event->save($this->data)) {
                            //$this->Event->id = $vars['id'];
                                $this->Event->saveField('time', $this->data['time']);
				$this->Session->setFlash(__('The event has been saved', true));
                                //debug($this->data);
                                
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true));
			}
		}
		//$this->set('eventTypes', $this->Event->EventType->find('list'));
                $this->set('eventTypes', $this->event_types->find('list'));
	}

	function edit($id = null) {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid event', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Event->save($this->data)) {
                            $this->Event->saveField('time', $this->data['time']);
				$this->Session->setFlash(__('The event has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Event->read(null, $id);
		}
		$this->set('eventTypes', $this->event_types->find('list'));
	}

	function delete($id = null) {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for event', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Event->delete($id)) {
			$this->Session->setFlash(__('Event deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Event was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

        // The feed action is called from "webroot/js/ready.js" to get the list of events (JSON)
	function feed($id=null) {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
            
           $userId = $this->Auth->user('EmpId');
            
            
		$this->layout = "ajax";
		$vars = $this->params['url'];
                
              $leave_usage = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_states'=>'accepted')));
               
                
               
		//$conditions = array('conditions' => array('UNIX_TIMESTAMP(start) >=' => $vars['start'], 'UNIX_TIMESTAMP(start) <=' => $vars['end']));
		//$events = $this->Event->find('all');
                
                $events = $this->Event->find('all', array(
                 'joins' => array(
                  array(
                      'table' => 'event_types',
                      'alias' => 'EventType',
                      'type' => 'INNER',
           
                      'conditions' => array(
                      'Event.event_type_id = EventType.id'
                       )
                  )
              ),
                
             'fields' => array('Event.*', 'EventType.*')
   
             ));
                
                
               
                foreach($leave_usage as $leaves){
                    $data1[] = array(
                                        'id' => $leaves['leave_record']['id'],
					'title'=>$leaves['leave_record']['Leave_Type'],
					'start'=>$leaves['leave_record']['From_Date'],
					'end' => $leaves['leave_record']['To_Date'],
					//'allDay' => $allday,
					'url' => Router::url('/') . 'leaverecord/add',
					'details' => $leaves['leave_record']['Leave_comment'],
					'className' => "red"
                    );
                }
                
                
		foreach($events as $event) {
			/**if($event['Event']['all_day'] == 1) {
				$allday = true;
				$end = $event['Event']['start'];
			} else {
				$allday = false;
				$end = $event['Event']['end'];
			}**/
			$data[] = array(
					'id' => $event['Event']['id'],
					'title'=>$event['Event']['title'],
					'start'=>$event['Event']['start'],
					'end' => $event['Event']['end'],
					//'allDay' => $allday,
					'url' => Router::url('/') . 'full_calendar/events/view/'.$event['Event']['id'],
					'details' => $event['Event']['details'],
					'className' => $event['EventType']['color']
			);
		}
                if(!empty($data1)){
                $arr1  = array_merge_recursive($data,$data1);
                //debug($data1);
                
		$this->set("json", json_encode( $arr1 ));
                //$this->set("json", json_encode($data));
                Configure::write('debug', 0);
                $this->autoRender = false;
                $this->autoLayout = false;
                $this->header('Content-Type: application/json');
                echo json_encode( $arr1 );
                //echo json_encode( $data);
                }else{
                  $this->set("json", json_encode( $data ));
                //$this->set("json", json_encode($data));
                Configure::write('debug', 0);
                $this->autoRender = false;
                $this->autoLayout = false;
                $this->header('Content-Type: application/json');
                echo json_encode( $data );  
                }
               
                
                
	}
        
         // The feed action is called from "webroot/js/ready.js" to get the list of events (JSON)
	function feed1($id=null) {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
            
           $userId = $this->Auth->user('EmpId');
            
            
		$this->layout = "ajax";
		$vars = $this->params['url'];
                
              $leave_usage = $this->leave_record->find('all',array('conditions' => array('leave_record.Eid'=>$userId,'leave_record.Leave_states'=>'accepted')));
               
                
               
		//$conditions = array('conditions' => array('UNIX_TIMESTAMP(start) >=' => $vars['start'], 'UNIX_TIMESTAMP(start) <=' => $vars['end']));
		//$events = $this->Event->find('all');
                
                $events = $this->Event->find('all', array(
                 'joins' => array(
                  array(
                      'table' => 'event_types',
                      'alias' => 'EventType',
                      'type' => 'INNER',
           
                      'conditions' => array(
                      'Event.event_type_id = EventType.id'
                       )
                  )
              ),
                
             'fields' => array('Event.*', 'EventType.*')
   
             ));
                
                
               
                foreach($leave_usage as $leaves){
                    $data1[] = array(
                                        'id' => $leaves['leave_record']['id'],
					'title'=>$leaves['leave_record']['Leave_Type'],
					'start'=>$leaves['leave_record']['From_Date'],
					'end' => $leaves['leave_record']['To_Date'],
					//'allDay' => $allday,
					'url' => Router::url('/') . 'leaverecord/add',
					'details' => $leaves['leave_record']['Leave_comment'],
					'className' => "red"
                    );
                }
                
                
		foreach($events as $event) {
			/**if($event['Event']['all_day'] == 1) {
				$allday = true;
				$end = $event['Event']['start'];
			} else {
				$allday = false;
				$end = $event['Event']['end'];
			}**/
			$data[] = array(
					'id' => $event['Event']['id'],
					'title'=>$event['Event']['title'],
					'start'=>$event['Event']['start'],
					'end' => $event['Event']['end'],
					//'allDay' => $allday,
					'url' => Router::url('/') . 'full_calendar/events/view/'.$event['Event']['id'],
					'details' => $event['Event']['details'],
					'className' => $event['EventType']['color']
			);
		}
                
                  $this->set("json", json_encode( $data ));
                //$this->set("json", json_encode($data));
                Configure::write('debug', 0);
                $this->autoRender = false;
                $this->autoLayout = false;
                $this->header('Content-Type: application/json');
                echo json_encode( $data );  
                
               
                
                
	}
        function feed2($id=null) {
         
         $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
            
           $userId = $this->Auth->user('EmpId');
            
            
		$this->layout = "ajax";
		$vars = $this->params['url'];
                
              $leave_usage = $this->leave_record->find('all',array('conditions' => array('leave_record.Leave_states'=>'accepted')));
               
                
               
		//$conditions = array('conditions' => array('UNIX_TIMESTAMP(start) >=' => $vars['start'], 'UNIX_TIMESTAMP(start) <=' => $vars['end']));
		//$events = $this->Event->find('all');
                
                $events = $this->Event->find('all', array(
                 'joins' => array(
                  array(
                      'table' => 'event_types',
                      'alias' => 'EventType',
                      'type' => 'INNER',
           
                      'conditions' => array(
                      'Event.event_type_id = EventType.id'
                       )
                  )
              ),
                
             'fields' => array('Event.*', 'EventType.*')
   
             ));
                
                
               
                foreach($leave_usage as $leaves){
                    $data1[] = array(
                                        'id' => $leaves['leave_record']['id'],
//					'title'=>$leaves['leave_record']['Leave_Type'],
                                        'title'=>$leaves['leave_record']['EmpName']." - ".$leaves['leave_record']['Leave_Type'],
					'start'=>$leaves['leave_record']['From_Date'],
					'end' => $leaves['leave_record']['To_Date'],
					//'allDay' => $allday,
//					'url' => Router::url('/') . 'leaverecord/add',
					'details' => $leaves['leave_record']['Leave_Time']." - ".$leaves['leave_record']['Leave_comment'],
//					'color' => "rgb(255,0,0)"
//                                        'className' => $this->setColor($leaves['leave_record']['Leave_Type'])
                    );
                }
                
                
		foreach($events as $event) {
			/**if($event['Event']['all_day'] == 1) {
				$allday = true;
				$end = $event['Event']['start'];
			} else {
				$allday = false;
				$end = $event['Event']['end'];
			}**/
			$data[] = array(
					'id' => $event['Event']['id'],
					'title'=>$event['Event']['title'],
					'start'=>$event['Event']['start'],
					'end' => $event['Event']['end'],
					//'allDay' => $allday,
					'url' => Router::url('/') . 'full_calendar/events/view/'.$event['Event']['id'],
					'details' => $event['Event']['details'],
					'className' => $event['EventType']['color']
			);
		}
                
                  $this->set("json", json_encode( $data ));
                //$this->set("json", json_encode($data));
                Configure::write('debug', 0);
                $this->autoRender = false;
                $this->autoLayout = false;
                $this->header('Content-Type: application/json');
                
                echo json_encode( $data1 );  
           
            
        }    

        // The update action is called from "webroot/js/ready.js" to update date/time when an event is dragged or resized
	function update() {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
		$vars = $this->params['url'];
		$this->Event->id = $vars['id'];
		$this->Event->saveField('start', $vars['start']);
		$this->Event->saveField('end', $vars['end']);
		$this->Event->saveField('all_day', $vars['allday']);
	}
        
        function setColor($leaveType) {
            pr($leaveType);
            switch ($leaveType) {
              case "annual":
                return "red";
                break;
              case "casual":
                return "blue";
                break;
              case "nopay":
                return "green";
                break;
              case "sick":
                return "yellow";
                break;
              default:
                return "pink";
            }
            
        }

}


        
?>
