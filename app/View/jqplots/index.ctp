                                      
<?php  echo $this->Javascript->link('excanvas.min.js');?>
<![endif]-->
 
<?php
echo $this->Javascript->link('jquery.min.js');
echo $this->Javascript->link('jquery.jqplot.min.js');
echo $this->Html->css('jquery.jqplot.min.css');
?>
 
<!-- 2 -->
<div id="chartdiv" style="height:400px;width:500px; margin:auto; "></div>
 
<!-- 3 -->
<script>
$(document).ready(function(){
    $.jqplot('chartdiv',  [[[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]]);
});
</script>
