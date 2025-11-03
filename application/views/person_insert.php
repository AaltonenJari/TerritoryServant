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
    <?php echo form_open('person_controller/check_insert', ['class' => 'maintenance_form_area']); ?>
    
    <div class="dialog-form-container">    
      <h1>Lisää henkilö</h1>
    
      <div class="dialog-form-content">
        <table id="cardtable">
          <tr>
            <td>
              <?php echo form_label('Etunimi: '); ?>
            </td>
            <td>
              <?php 
                $data_input = [
                  'id'    => 'person_name',
                  'name'  => 'person_name',
                  'value' =>  $person_name,
                  'class' => 'insert_value'
                ];
                echo form_input($data_input);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo form_label('Sukunimi: '); ?>
            </td>
            <td>
              <?php 
                $data_input = [
                    'id'    => 'person_lastname',
                    'name'  => 'person_lastname',
                    'value' =>  $person_lastname,
                    'class' => 'insert_value'
                ];
                echo form_input($data_input);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo form_label('Palvelusryhmä: '); ?>
            </td>
            <td>
              <?php 
              $js = [
                  'id'       => 'group_id',
                  'class'    => 'insert_value',
                  'onChange' => 'jsGroupChance(this, person_group);'
              ];
              echo form_dropdown('person_group_dropbox', $groups, $person_group_string, $js);
          
              $data_hidden = [
                  'type'  => 'hidden',
                  'id'    => 'person_group',
                  'name'  => 'person_group',
                  'value' =>  $person_group,
                  'class' => 'person_group'
              ];
              echo form_input($data_hidden);
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <?php echo form_label('Ryhmänvalvoja: '); ?>
            </td>
            <td>
              <?php 
              $js = [
                  'id'       => 'overseer_id',
                  'class'    => 'insert_value',
                  'onChange' => 'jsOverseerChance(this, person_leader);'
              ];
              echo form_dropdown('overseers_dropbox', $overseers, $person_leader, $js);
          
              $data_hidden = [
                  'type'  => 'hidden',
                  'id'    => 'person_leader',
                  'name'  => 'person_leader',
                  'value' =>  $person_leader,
                  'class' => 'person_leader'
              ];
              echo form_input($data_hidden);
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <?php echo form_label('Näytetäänkö: '); ?>
            </td>
            <td>
              <?php
                $data_input = [
                    'id'    => 'person_show',
                    'name'  => 'person_show',
                    'value' =>  $person_show,
                    'class' => 'insert_value'
                ];
                echo form_input($data_input);
              ?>
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
function jsGroupChance(selectObject,fieldObject) {
	var selectedString = selectObject.value;
	var selectedGroupId = selectedString.match(/\d+/g);
	selectedGroupId = Number(selectedGroupId);

	document.getElementById(fieldObject.id).value = selectedGroupId;
}

function jsOverseerChance(selectObject,fieldObject) {
	var selectedString = selectObject.value;

	document.getElementById(fieldObject.id).value = selectedString;
}

</script> 
         
</html>
