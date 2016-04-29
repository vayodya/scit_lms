<?php
 $line= $orders6[0]['leave_record'];
//debug($line);exit;
$H1=array('Employee ID','Employee Name','Leave Type','From Date','To Date','Leave Comment','Leave Time','Leave Status','Real Days','Accept ID');  

//$this->Csv->addRow(array_keys($line));
$this->Csv->addRow($H1); 
 foreach ($orders6 as $order){
      $line = $order['leave_record'];
      echo $this->Csv->addRow($line);
      //echo '</br>'; 
 } 
 $filename='LeaveRecord';
 echo  $this->Csv->render($filename);
?>