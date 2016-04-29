<?php
/*
 * Controller/FullCalendarController.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

class FullCalendarController extends FullCalendarAppController {

	var $name = 'FullCalendar';
        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('view','index', 'leaveCalendar');
        }
        
        function view(){
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
        }
        
        function leaveCalendar() {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
        }
        
        function index() {
            $username = $this->Auth->user('EmpName');
            $this->set('loguser',$username);
            
//            $this->loadModel('Project');
//            pr($this->Project->find('list', array('fields' => array('Pid','pro_name'))));
            
//            pr($this->leave_record->find('all'));
//            $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
//
//            $testJason = json_encode($arr);
//            
////            pr($testJason);
//            $encodedArray = "{
//            [
//				{
//					title: 'All Day Event',
//					start: '2014-09-01'
//				},
//				{
//					title: 'Long Event',
//					start: '2014-09-07',
//					end: '2014-09-10'
//				},
//				{
//					id: 999,
//					title: 'Repeating Event',
//					start: '2014-09-09T16:00:00'
//				},
//				{
//					id: 999,
//					title: 'Repeating Event',
//					start: '2014-09-16T16:00:00'
//				},
//				{
//					title: 'Conference',
//					start: '2014-09-11',
//					end: '2014-09-13'
//				},
//				{
//					title: 'Meeting',
//					start: '2014-09-12T10:30:00',
//					end: '2014-09-12T12:30:00'
//				},
//				{
//					title: 'Lunch',
//					start: '2014-09-12T12:00:00'
//				},
//				{
//					title: 'Meeting',
//					start: '2014-09-12T14:30:00'
//				},
//				{
//					title: 'Happy Hour',
//					start: '2014-09-12T17:30:00'
//				},
//				{
//					title: 'Dinner',
//					start: '2014-09-12T20:00:00'
//				},
//				{
//					title: 'Birthday Party',
//					start: '2014-09-13T07:00:00'
//				},
//				{
//					title: 'Click for Google',
//					url: 'http://google.com/',
//					start: '2014-09-28'
//				}
//		]	
//                }" ;
//            $decodedArray =   json_decode($encodedArray, true);
//            pr($decodedArray);
//            
//            
//            $jsonData = '{ 
//                        "u1":{ "user":"John", "age":22, "country":"United States" },
//                        "u2":{ "user":"Will", "age":27, "country":"United Kingdom" },
//                        "u3":{ "user":"Abiel", "age":19, "country":"Mexico" }
//                        }'; 
//            
//            $tryingData = '{
//                            "a1":{ "one":"1", "two":"2"}                          
//                      }';
//            
//            $decodedArray2 =   json_decode($tryingData, true);
////            pr($decodedArray2);
////                              "a1":{ "one":"1", "two":"2"},
////                            'b2':{'eleven':'11', 'twelve':'12'},
////                            'c3':{'twentyone':'21','twentytwo':'22'}          
//            
//            //start fetching
//            
//            $this->loadModel('leave_record');
//            $allLeaves = $this->leave_record->find('all', array('conditions' => array('leave_record.Eid'=>38,'leave_record.Leave_states' => 'accepted')));
//////            pr($userId);
//////            pr($allLeaves);
////        
//            foreach($allLeaves as $key => $leaves){
//                    $data1[] = array(
//                                        'id'        => $leaves['leave_record']['id'],
//					'title'     => $leaves['leave_record']['Leave_Type'],
//					'start'     => $leaves['leave_record']['From_Date'],
//					'end'       => $leaves['leave_record']['To_Date'],
//					//'allDay' => $allday,
////					'url' => Router::url('/') . 'leaverecord/add',
////					'details' => $leaves['leave_record']['Leave_comment'],
//					'className' => "red"
//                    );
//            }
//            
////            pr($data1);
////            pr(json_encode($data1));
//            $this->set("typeJson", json_encode($data1));
//            Configure::write('debug', 0);
////            $this->autoRender = false;
////            $this->autoLayout = false;
////            $this->header('Content-Type: application/json');
////            echo json_encode( $data1 );
            
            
            
	}
}
?>
