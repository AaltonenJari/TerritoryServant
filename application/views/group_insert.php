<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Ylläpito</title>
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
    <?php echo form_open('Group_controller/check_insert', ['class' => 'maintenance_form_area']); ?>
    
    <div class="dialog-form-container">    
      <h1>Lisää palvelusryhmä</h1>
    
      <div class="dialog-form-content">
        <table id="cardtable">
          <tr>
            <td>
              <?php echo form_label('Ryhmän nimi: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'group_name',
                  'name'=>'group_name','value'=> $group_name)); ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo form_label('Tapahtumia: '); ?>
            </td>
            <td>
              <?php echo form_input(array('id'=>'group_events',
                  'name'=>'group_events','value'=> $group_events)); ?>
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

         
</html>
