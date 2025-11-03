<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Käyttäjähallinta</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/terr_mark.css"); ?>">
  <!--link jquery ui css-->
  <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.css'); ?>" />

  <!--load jquery-->
  <script src="<?php echo base_url('assets/javascript/jquery-1.10.2.js'); ?>"></script>
  <!--load jquery ui js file-->
  <script src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.js'); ?>"></script>

</head>

<body>

  <div id="container">
    <?php echo form_open('User_controller/check_insert'); ?>
    
    <div class="dialog-form-container">    
      <h1>Lisää käyttäjä</h1>
    
      <div class="dialog-form-content">
        <table id="cardtable">
          <tr>
            <td>
              <?php echo form_label('Käyttäjätunnus: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'user_username',
                  'name'=>'user_username','value'=> $user_username)); ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo form_label('Salasana: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'user_password',
                  'name'=>'user_password','value'=> $user_password)); ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo form_label('Etunimi: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'user_firstname',
                  'name'=>'user_firstname','value'=> $user_firstname)); ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo form_label('Sukunimi: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'user_lastname',
                  'name'=>'user_lastname','value'=> $user_lastname)); ?>
            </td>
          </tr>

          <tr>
            <td>
              <?php echo form_label('Sähköposti: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'user_email',
                  'name'=>'user_email','value'=> $user_email)); ?>
            </td>
          </tr>
 
          <tr>
            <td>
              <?php echo form_label('Admin-käyttäjä: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'user_admin',
                  'name'=>'user_admin','value'=> $user_admin)); ?>
            </td>
          </tr>
        </table> <!-- cardtable -->
    
        <div class="footer-area">
          <div class="button-group">
            <div class="button-left">
              <?php echo form_submit([
                'class' => 'submit_btn',
                'name'  => 'action',
                'value' => 'Lisää'
              ]); ?>
            </div>
 
            <div class="button-right">
              <?php echo form_submit([
                'class' => 'submit_btn',
                'name'  => 'action',
                'value' => 'Paluu'
              ]); ?>
            </div>
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

<script>  

</script> 
         
</html>
