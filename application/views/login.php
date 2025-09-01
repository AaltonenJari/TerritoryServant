<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/terr_mark.css"); ?>">

</head>

<body>

  <div id="container">
    <?php echo form_open('LoginController/checkLogin'); ?>
    
    <h1>Kirjautuminen</h1>
    
    <table id="cardtable">
      <tr>
        <td>
          <?php echo form_label('Käyttäjätunnus: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'username',
              'name'=>'username')); ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Salasana: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'password','type'=>'password',
              'name'=>'password')); ?>
       </td>
      </tr>
      <tr>
        <td>
          <span class="text-danger"><?php echo form_error('username'); ?></span>
        </td>
        <td>
   		  <span class="text-danger"><?php echo form_error('password'); ?></span>	  
        </td>
      </tr>
    </table>
    
    <table id="cardbuttons">
      <tr>
        <td width="40%">
        </td>
        <td width="40%">
          <?php echo form_submit(array('id' => 'submit', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Login')); ?>
        </td>
        <td width="12%">
        </td>
       </tr>
      <tr>
        <td colspan="3">
          <?php echo $this->session->flashdata("error");	?>
        </td>
      </tr>
    </table>
    
    <?php echo form_close(); ?>

  </div><!-- container -->
</body>

</html>
