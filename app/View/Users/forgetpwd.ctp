     <?php echo $this->Form->create('User');?>
          <h1>Forget-Password</h1>
          <?php
                echo "<label>Enter Your Resgistered Email</label>";
                echo $this->Form->input('email', array('label'=>""));
          ?>
          <input name="" type="submit" value="submit" class="submit" />   
    <?php echo $this->Form->end();?>
 