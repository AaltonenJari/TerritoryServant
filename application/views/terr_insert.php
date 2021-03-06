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
    <?php echo form_open('maintenance_controller/check_insert'); ?>
    
    <h1>Lisää alue</h1>
    
    <table id="cardtable">
      <tr>
        <td>
          <?php echo form_label('Aluenumero: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'alue_code',
              'name'=>'alue_code','value'=> $alue_code)); ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Alueen nimi: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'alue_detail',
              'name'=>'alue_detail','value'=> $alue_detail)); ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Alueen lisätieto: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'alue_location',
              'name'=>'alue_location','value'=> $alue_location)); ?>
        </td>
      </tr>

      <tr>
        <td>
          <?php echo form_label('Alueen koko: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'alue_taloudet',
              'name'=>'alue_taloudet','value'=> $alue_taloudet)); ?>
        </td>
      </tr>
    </table>
    
    <table id="cardbuttons">
      <tr>
        <td width="30%">
          <?php echo form_submit(array('id' => 'submit', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Lisää')); ?>
        </td>
        <td width="30%">
        </td>
        <td width="40%">
          <?php echo form_submit(array('id' => 'submit', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Paluu')); ?>
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
