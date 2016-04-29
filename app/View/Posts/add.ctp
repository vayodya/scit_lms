<!-- File: /app/View/Posts/add.ctp -->

<h1>Add Post</h1><?php
echo $this->Form->create('Post', array('url' => array('action' => 'add'), 'enctype' => 'multipart/form-data')); 
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));

for($i=1; $i<4; $i++)
{
?>
         <div  id="attachment<?php echo $i;?>" <?php if($i !=1) echo "style='display:none;'";?> >
         	<div>
                  <?php echo $this->Form->input('image'.$i,array('type'=>'file','label' => false,'div' => false));?>
            </div>
            <div  id="attachmentlink<?php echo $i;?>"  <?php if($i==3) echo "style='display:none;'";?>><a href="javascript:void(0);" onclick="show('attachment<?php echo $i+1;?>'); hide('attachmentlink<?php echo $i;?>');">Add Another Attachment</a></div>
            </div>
            <?php } ?>
<?php 
echo $this->Form->end('Save Post');
?>
<script>
function show(target){
	document.getElementById(target).style.display = 'block';
}
function hide(target){
	document.getElementById(target).style.display = 'none';
}
</script>
