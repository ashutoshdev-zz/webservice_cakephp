<div class="users form">
 <?php $id=$this->Session->read('Auth.User.id');
 echo $this->Form->create('Profilemode',array('controller'=>'profilemodes','action'=>'profile_mode')); ?>
	<fieldset>
		<legend><?php echo __('Profile Mode'); ?></legend>
	<?php
                echo $this->Form->input('user_id',array('type'=>'hidden','value'=>$id));
		echo $this->Form->input('modetype', array(
            'options' => array('general' => 'General', 'meeting' => 'Meeting') ));	 
                echo $this->Form->input('description');
		echo $this->Form->input('longitude');
		echo $this->Form->input('latitude');
 		 	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<!--<div class="actions">
	<h3>< ?php echo __('Actions'); ?></h3>
	<ul>

		<li>< ?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
	</ul>
</div>-->
