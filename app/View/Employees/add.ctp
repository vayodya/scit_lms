<!-- app/View/Users/add.ctp -->

<div class="users form">
<?php echo $this->Form->create('Employee'); ?>
    <fieldset>
        <legend><?php echo __('Add User'); ?></legend>
        <?php echo $this->Form->input('user_name');
        echo $this->Form->input('user_password');
        ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>