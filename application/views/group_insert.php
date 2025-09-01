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
    <?php echo form_open('Group_controller/check_insert'); ?>
    
    <h1>Lisää palvelusryhmä</h1>
    
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
               <td colspan="4">
                 <?php echo $this->session->flashdata("error");	?>
               </td>
             </tr>
    </table>
    
    <?php echo form_close(); ?>

  </div><!-- container -->
</body>

         
</html>
