<?php
 $line= $orders[0]['leave_record'];
 $H1=array('Employee ID','Employee Name','From Date','To Date','Leave Type','Leave Comment','Leave Time','Status','Real Day(s)','Accept ID');
 //$this->Csv->addRow(array_keys($line));
 $this->Csv->addRow($H1);
 foreach ($orders as $order){
      $line = $order['leave_record'];
      $this->Csv->addRow($line);
      //echo '</br>'; 
 } 
 $filename='LeaveRecord';
 echo  $this->Csv->render($filename);
?>