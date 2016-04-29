<!-- File: /app/View/Posts/view.ctp -->
<h1>ID : <?php echo h($post['Post']['id']); ?></h1>
<h1>TItle : <?php echo h($post['Post']['title']); ?></h1>
<h1>Body : <?php echo h($post['Post']['body']); ?></h1>
<?php 
for($i=1;$i<4;$i++)
{
	if(!empty($post['Post']['image'.$i]))
	{
		echo $this->Html->image('/img/uploads/posts/'.$post['Post']['image'.$i]); ?>
	<?php }	
}?>





