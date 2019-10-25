<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Aluen merkitseminen</title>
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
    <?php echo form_open('territory_controller/check_territory'); ?>
    
    <h1>Merkitse alue</h1>
    
    <table id="cardtable">
      <tr>
        <td>
          <?php echo form_label('Aluenumero: '); ?>
        </td>
        <td>
          <?php echo form_label($alue_code); ?>
          <?php echo form_hidden('alue_code', $alue_code);  ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Alueen nimi: '); ?>
        </td>
        <td>
          <?php echo form_label($alue_detail); ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Alueen lisätieto: '); ?>
        </td>
        <td>
          <?php echo form_label($alue_location); ?>
        </td>
      </tr>

      <tr>
        <td>
          <?php echo form_label('Lainassa:'); ?>
        </td>
        <td>
          
          <?php 
            if ($lainassa == 1) {
                echo form_checkbox('dlainassa', set_value('dlainassa', '1'), TRUE);
            } else {
                echo form_checkbox('dlainassa', set_value('dlainassa', '0'), FALSE);
            }
          ?>
          <?php echo form_hidden('lainassa_old', $lainassa);  ?>
        </td>
      </tr>
      
      <tr>
        <td>
          <?php 
            if ($lainassa == 1) {
                echo form_label('Lainattu: '); 
            } else {
                echo form_label('Palautettu: '); 
            }
          ?>
        </td>
        <td>
          <?php 
            echo form_label($alue_lastdate);
            echo form_hidden('lastdate_old', $alue_lastdate);
          ?>
        </td>
      </tr>
      
      <tr>
        <td>
          <?php echo form_label('Merkintäpvm:'); ?>
        </td>
        <td>
          <?php 
            $attributes = 'id="dmerk" placeholder="Merkintäpvm"';
            echo form_input('dmerk', set_value('dmerk', date("j.n.Y")), $attributes);
           ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo form_label('Kenellä:'); ?>
        </td>
        <td>
          <?php 
            $attributes = 'id="djnimi" placeholder="Nimi"';
            echo form_input('djnimi', set_value('djnimi', $name), $attributes); 
            echo form_hidden('jnimi_old', $name); 
          ?>
         </td>
      </tr>

      <tr>
        <td>
        </td>
        <td>
          <span class="text-danger"><?php echo form_error('djnimi'); ?></span>
         </td>
      </tr>
     
    </table>
    
    <table id="cardbuttons">
      <tr>
        <td width="30%">
          <?php echo form_submit(array('id' => 'submit', 'name' => 'action', 'value' => 'Päivitä')); ?>
        </td>
        <td width="30%">
        </td>
        <td width="40%">
          <?php echo form_submit(array('id' => 'submit', 'name' => 'action', 'value' => 'Paluu')); ?>
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

<script type="text/javascript">
$(function() {
    $("#dmerk").datepicker();
});

$("#dmerk").datepicker(
    {
        dateFormat: 'dd.mm.yy'
    }
);

$('input[type="checkbox"]').click(function() {
    if (!this.checked) {
        $('#djnimi').val('');
    }

});
</script>         
         
</html>
