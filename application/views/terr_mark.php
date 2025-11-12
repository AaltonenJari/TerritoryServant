<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Merkitseminen</title>
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
    <div class="dialog-form-container">    
      <h1>Alueen merkitseminen</h1>
      <?php 
      //Muuttujat pvm-vertailua varten
      $alue_lastdate_datetype = new DateTime($alue_lastdate);
      $event_last_date_datetype = new DateTime($event_last_date);
      ?>
  
      <div class="dialog-form-content">
  
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
                    if ($mark_type == 3) {
                        echo form_label('Merkattu: ');
                    } else {
                        if ($alue_lastdate_datetype > $event_last_date_datetype) {
                            echo form_label('Käyty: ');
                        } else {
                            echo form_label('Lainattu: ');
                        }
                    }
                } else {
                    if ($return_type == 4) {
                        echo form_label('Merkattu: ');
                    } else {
                        echo form_label('Palautettu: ');
                    }
                }
              ?>
            </td>
            <td>
              <?php 
                if ($lainassa == 1) {
                    if ($alue_lastdate_datetype > $event_last_date_datetype) {
                        $terr_lastdate = $alue_lastdate;
                    } else {
                        $terr_lastdate = $event_last_date;
                    }
                } else {
                    $terr_lastdate = $mark_date;
                }
                echo form_label($terr_lastdate);
                $data = [
                    'type'  => 'hidden',
                    'name'  => 'lastdate_old',
                    'id'    => 'hidden_lastdate_old',
                    'value' =>  $terr_lastdate,
                    'class' => 'hidden_lastdate_old'
                ];
                echo form_input($data);
              ?>
            </td>
          </tr>
      
          <tr>
            <td>
              <?php 
              //Näytettävä kenttä
              if ($lainassa == 0) {
                  $dmark_text = 'Lainauspvm:';
              } else {
                  $dmark_text = 'Merkintäpvm:';
              }
              $dmark_label_name = 'dmark_text_name';
              $attributes = [
                  'id' => "dmark_text_id"
              ];
              echo form_label($dmark_text, $dmark_label_name, $attributes);
              ?>
            </td>
            <td>
              <?php 
                $attributes = [
                    'id' => 'dmerk',
                    'placeholder' => 'Merkintäpvm',
                    'class' => 'fullwidth'
                ];
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
                $attributes = [
                    'id'       => 'djnimi',
                    'onChange' => 'onTerritoryMarkup();',
                    'class'    => 'fullwidth'
                ];
                echo form_dropdown('djnimi', $lenders, $name, $attributes);
           
                //input field for name other than in the drop-down list
                $data = [
                   'type'  => 'text',
                   'name'  => 'djnimi',
                   'id'    => 'djnimi_id',
                   'value' => $name,
                   'style' => 'display:none',
                   'class' => 'fullwidth'
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
            <td colspan="2">
              <span class="text-danger"><?php echo form_error('djnimi'); ?></span>
             </td>
          </tr>
     
        </table> <!-- cardtable -->

        <div class="footer-area">
          <div class="button-group">
            <div class="button-left">
              <?php echo form_submit([
                'class' => 'submit_btn',
                'name'  => 'action',
                'value' => 'Päivitä'
              ]); ?>
            </div>
 
            <div class="button-center">
              <?php echo form_submit([
                'class' => 'submit_btn',
                'name'  => 'action',
                'value' => 'Historia'
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
        $("#dmark_text_id").html('Palautuspvm:');
    } else {
        if ($('#hidden_jnimi_old').val() != '') {
            $("#dmark_text_id").html('Merkintäpvm:');
        }
        $('#djnimi').val($('#hidden_jnimi_old').val());
     }

});

function onTerritoryMarkup() {
	// Päivitä checkbox sen mukaan, onko nimi annettu vai ei
	var myselect = document.getElementById("djnimi");
	var chk_lainassa = document.getElementsByName("dlainassa")[0];
	var mark_text = document.getElementById("dmark_text_id");
	var old_name = document.getElementById("hidden_jnimi_old");
	

	if (myselect.options[myselect.selectedIndex].value != ' ') {
	  chk_lainassa.value = "1";
	  chk_lainassa.checked = true;

	  if (myselect.options[myselect.selectedIndex].value != old_name.value) {
		  mark_text.innerHTML = "Lainauspvm:";
	  } else {
		  mark_text.innerHTML = "Merkintäpvm:";
	  }
	  
	  if (myselect.options[myselect.selectedIndex].value != 'uusinimi') {
	    document.getElementById("djnimi_id").value = myselect.options[myselect.selectedIndex].value;
	  }
	} else {
	  chk_lainassa.value = "0";
	  chk_lainassa.checked = false;
	  if (old_name.value != '') {
 	      mark_text.innerHTML = "Palautuspvm:";
	  }
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
