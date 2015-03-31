     <?php echo $this->Form->create('User');?>
    <h1>Reset-Password</h1>
        <?php
            echo "<label>New-Password</label>";
            echo $this->Form->input('password', array('label'=>"",'class'=>'aa'));
            echo "<label>Confirm-Password</label>";
            echo $this->Form->input('password_confirm', array('label'=>"",'class'=>'aa'));
        ?>
        <input name="" type="submit" value="submit" class="submit" />   
    <?php echo $this->Form->end();?>
 