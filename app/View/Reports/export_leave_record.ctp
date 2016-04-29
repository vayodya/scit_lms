<?php
    $line= $orders[2];
    $H1=array(
        '1' => 'Employee ID',
        '2' => 'Employee Name',
        '3' => 'Casual-Entitled',
        '4' => 'Casual-Consumed',
        '5' => 'Casual-Remaining',
        '6' => 'Annual-Entitled',
        '7' => 'Annual-Consumed',
        '8' => 'Annual-Remaining',
        '9' => 'Sick-Entitled',
        '10' => 'Sick-Consumed',
        '11' => 'Casual-Remaining',
        '12' => 'No-Pay',
        '13' => 'Work From Home',
     );
    $this->Csv->addRow($H1);
    foreach ($orders as $order){
        $line = $order;
        $this->Csv->addRow($line);
    } 
    $filename='Leave_Summary';
    echo  $this->Csv->render($filename);
?>