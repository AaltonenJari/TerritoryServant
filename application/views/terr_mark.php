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
            $data = [
                'type'  => 'hidden',
                'name'  => 'lastdate_old',
                'id'    => 'hidden_lastdate_old',
                'value' =>  $alue_lastdate,
                'class' => 'hidden_lastdate_old'
            ];
            echo form_input($data);
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
           $js = [
               'id'       => 'djnimi',
               'onChange' => 'jsFunction();'
           ];
           echo form_dropdown('djnimi', $lenders, $name, $js);
           
           //input field for name other than in the drop-down list
           $data = [
               'type'  => 'text',
               'name'  => 'djnimi',
               'id'    => 'djnimi_id',
               'value' =>  $name,
               'style' => 'display:none'
           ];
           
           echo form_input($data);
           
            
           //Save the old name (selected value from server)
           $data = [
           'type'  => 'hidden',
           'name'  => 'jnimi_old',
           'id'    => 'hidden_jnimi_old',
           'value' =>  $name,
           'class' => 'hidden_jnimi_old'
           ];
           
           echo form_input($data);
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
     
    </table> <!-- cardtable -->
    
    <table id="cardbuttons">
      <tr>
        <td width="40%">
          <?php echo form_submit(array('id' => 'submit_update', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Päivitä')); ?>
        </td>
        <td width="30%">
          <?php echo form_submit(array('id' => 'submit_history', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Historia')); ?>
        </td>
        <td width="30%">
          <?php echo form_submit(array('id' => 'submit_return_mark', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Paluu')); ?>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <?php echo $this->session->flashdata("error");	?>
        </td>
       </tr>
    </table> <!-- cardbuttons -->
    
    
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
    } else {
        $('#djnimi').val($('#hidden_jnimi_old').val());
    }

});

function jsFunction() {
	// Päivitä checkbox sen mukaan, onko nimi annettu vai ei
	var myselect = document.getElementById("djnimi");
	var chk_lainassa = document.getElementsByName("dlainassa")[0];

	if (myselect.options[myselect.selectedIndex].value != ' ') {
	  chk_lainassa.value = "1";
	  chk_lainassa.checked = true;
	  if (myselect.options[myselect.selectedIndex].value != 'uusinimi') {
	    document.getElementById("djnimi_id").value = myselect.options[myselect.selectedIndex].value;
	  }
	} else {
	  chk_lainassa.value = "0";
	  chk_lainassa.checked = false;
    }

	//Lisätäänkö uusi nimi? Laitetaan silloin lisäkenttä näkyviin
	if ($('#djnimi').val() == 'uusinimi') {
		$('#djnimi_id').val('');
        $('#djnimi_id').show();
    } else {
        $('#djnimi_id').hide();
    }
    
}


</script>         
         
</html>
