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
    
    <div class="dialog-form-container">    
      <h1>Kirjautuminen</h1>
    
      <div class="dialog-form-content">
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
        </table> <!-- cardtable -->
    
        <div class="footer-area">
            <div class="button-center">
              <?php echo form_submit([
                'class' => 'submit_btn',
                'name'  => 'action',
                'value' => 'Login'
              ]); ?>
            </div>

          <div class="form-error">
            <?php echo $this->session->flashdata("error"); ?>
          </div>
        </div><!-- footer-area -->

      </div><!-- dialog-form-content -->
    </div><!-- dialog-form-container -->
    <?php echo form_close(); ?>

  </div><!-- container -->
</body>

</html>
