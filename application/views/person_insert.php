<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Henkilötietojen lisäys</title>
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
    <?php echo form_open('person_controller/check_insert'); ?>
    
    <h1>Lisää henkilö</h1>
    
    <table id="cardtable">
      <tr>
        <td>
          <?php echo form_label('Etunimi: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'person_name',
              'name'=>'person_name','value'=> $person_name)); ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Sukunimi: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'person_lastname',
              'name'=>'person_lastname','value'=> $person_lastname)); ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Palvelusryhmä: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'person_group',
              'name'=>'person_group','value'=> $person_group)); ?>
        </td>
      </tr>

      <tr>
        <td>
          <?php echo form_label('Ryhmänvalvoja: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'person_leader',
              'name'=>'person_leader','value'=> $person_leader)); ?>
        </td>
      </tr>

      <tr>
        <td>
          <?php echo form_label('Näytetäänkö: '); ?>
        </td>
        <td>
          <?php echo form_input(array('id'=>'person_show',
              'name'=>'person_show','value'=> $person_show)); ?>
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
