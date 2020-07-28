<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Alueet - Asetukset</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">

  <!--link jquery ui css-->
  <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.css'); ?>" />

  <!--load jquery-->
  <script src="<?php echo base_url('assets/javascript/jquery-1.10.2.js'); ?>"></script>
  <!--load jquery ui js file-->
  <script src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.js'); ?>"></script>
  
</head>

<body>

  <div id="wrapper">
    <!-- Asetetaan navigointipalkki ja tämä sivu aktiiviseksi -->
    <?php $sivu_tunnus = "5"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>
    
 	<div id="settings_content">
      <!-- Asetetaan sivun pääotsikko -->
      <h1>Asetukset</h1>
 
	  <?php echo form_open('settings_controller/check_settings'); ?>
	    <table id="setting_table">
          <tr>
            <td>
              <?php echo form_label('Nimen esitysmuoto: '); ?>
            </td>
            <td>
              <?php echo form_hidden('namePresentationOld', $name_presentation); 
		 	  $options_name = array(
                       '0'         => 'Etunimi Sukunimi',
                       '1'         => 'Sukunimi, Etunimi',
              );

		 	  $js = 'id="namePresentationChooser" onChange="jsFunction();"';
		 	  echo form_dropdown('namePresentationChooser', $options_name, $name_presentation, $js);
              ?>
            </td>
          </tr>
          <tr>
             <td>
              <?php echo form_label('Merkintöjen esitysjärjestys: '); ?>
            </td>
            <td>
              <?php echo form_hidden('eventOrderOld', $event_date_order); 
		 	  $options_order = array(
                       'ASC'         => 'Nouseva',
                       'DESC'        => 'Laskeva',
              );

		 	  $js2 = 'id="eventOrderChooser" onChange="jsFunction2();"';
		 	  //echo form_dropdown('eventOrderChooser', $options_order, $event_date_order, $js2);
		 	  echo form_dropdown('eventOrderChooser', $options_order, set_value('eventOrderChooser',$event_date_order), $js2);
              ?>
            </td>
          </tr>
          <tr>
             <td>
              <?php echo form_label('Merkintöjen näyttämisaika: '); ?>
            </td>
            <td>
              <?php echo form_hidden('archiveYearsOld', $archive_time); 
		 	  $options_years = array(
                       '5'         => '5 vuotta',
                       '6'         => '6 vuotta',
                       '7'         => '7 vuotta',
                       '8'         => '8 vuotta',
                       '9'         => '9 vuotta',
                       '10'        => '10 vuotta',
                       '11'        => '11 vuotta',
                       '12'        => '12 vuotta',
                       '13'        => '13 vuotta',
                       '14'        => '14 vuotta',
                       '15'        => '15 vuotta',
                       '16'        => '16 vuotta',
                       '17'        => '17 vuotta',
                       '18'        => '18 vuotta',
                       '19'        => '19 vuotta',
                       '20'        => '20 vuotta',
              );

		 	  $js3 = 'id="archiveYearsChooser" onChange="jsFunction3();"';
		 	  echo form_dropdown('archiveYearsChooser', $options_years, $archive_time, $js3);
              ?>
            </td>
          </tr>
 
           <tr>
             <td>
              <?php echo form_label('Näytetäänkö liikealueet?: '); ?>
            </td>
            <td>
              <?php echo form_hidden('btSwitchOld', $bt_switch); 
              $options_bt_switch = array(
                       '0'         => 'Ei näytetä',
                       '1'         => 'Näytetään',
               );

		 	  $js4 = 'id="btSwitchChooser" onChange="jsFunction4();"';
		 	  echo form_dropdown('btSwitchChooser', $options_bt_switch, $bt_switch, $js4);
              ?>
            </td>
          </tr>
          
          <tr>
            <td>
              <?php echo form_label('Kierrosviikko alkaa:'); ?>
            </td>
            <td>
              <?php
			    echo form_hidden('circuitWeekStartOld', $circuit_week_start);
                $attributes = 'id="kvviikko_alkaa"';
                echo form_input('kvviikko_alkaa', set_value('kvviikko_alkaa', date('j.n.Y', strtotime($circuit_week_start))), $attributes);
               ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo form_label('Kierrosviikko päättyy:'); ?>
            </td>
            <td>
              <?php
                $data_name = array(
                  'name' => 'kvviikko_loppuu',
                  'id' => 'kvviikko_loppuu',
                  'value' => $circuit_week_end,
                  'readonly' => 'readonly'
                );
                echo form_input($data_name);
                
			    echo form_hidden('circuitWeekEndOld', $circuit_week_end);
               ?>
            </td>
          </tr>
		  
           <tr>
             <td>
              <?php echo form_label('Merkintöjen tallennustapa: '); ?>
            </td>
            <td>
              <?php echo form_hidden('eventSaveSwitchOld', $event_save_switch); 
              $options_event_save_switch = array(
                       '0'         => 'Vain lainaukset ja palautukset',
                       '1'         => 'Kaikki merkkaukset',
               );

		 	  $js5 = 'id="eventSwitchChooser" onChange="jsFunction5();"';
		 	  echo form_dropdown('eventSwitchChooser', $options_event_save_switch, $event_save_switch, $js5);
              ?>
            </td>
          </tr>

	      <tr>
           <td>
           </td>
          </tr>
          <tr>
           <td>
           </td>
          </tr>

	    </table> <!-- setting_table -->
		
		<table id="settings_buttons">
        <tr>
          <td width="30%">
          </td>
          <td width="30%">
            <?php echo form_submit(array('id' => 'submit', 'class' => 'btnFormAction', 'name' => 'action', 'value' => 'Päivitä')); ?>
          </td>
          <td width="40%">
            <?php echo form_submit(array('id' => 'submit', 'class' => 'btnFormAction', 'name' => 'action', 'value' => 'Paluu')); ?>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <?php echo $this->session->flashdata("error");	?>
          </td>
         </tr>
      </table> <!-- settings_buttons -->

	  <?php echo form_close(); ?>
 
	</div><!-- settings_content -->

  </div><!-- wrapper -->
</body>
<script type="text/javascript">
$(function() {
    $("#kvviikko_alkaa").datepicker();
});

$("#kvviikko_alkaa").datepicker(
    {
        dateFormat: 'dd.mm.yy'
    }
);

$("#kvviikko_alkaa").change(function(){
	var starts = $("#kvviikko_alkaa").val();
	var numberOfDays = 5;
    var parts = starts.split('.');
	// new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
	var joindate = new Date(parts[2], parts[1]-1, parts[0]); // Note: months are 0-based
    joindate.setDate(joindate.getDate() + numberOfDays);

    var dd = joindate.getDate();
    
    var mm = joindate.getMonth() + 1;
    var y = joindate.getFullYear();
    var endDate = dd + '.' + mm + '.' + y;

    var dateValue = joindate.getDay(); 
    if (dateValue == 0) {
    	$('#kvviikko_loppuu').val(endDate);
        return true;
    }
    else {
        alert("Kierrosviikon alku ei ole tiistai! " + dateValue);
        //Palauta vanha päivä
        $('#kvviikko_alkaa').val(document.getElementsByName("circuitWeekStartOld")[0].value);
        $('#kvviikko_loppuu').val(document.getElementsByName("circuitWeekEndOld")[0].value);
        return false; 
    } 
}); 

function jsFunction() {
	  var myselect = document.getElementById("namePresentationChooser");
	  document.getElementsByName("namePresentationOld")[0].value = myselect.options[myselect.selectedIndex].value;
	}

function jsFunction2() {
	  var myselect = document.getElementById("eventOrderChooser");
  	  document.getElementsByName("eventOrderOld")[0].value = myselect.options[myselect.selectedIndex].value;
	}

function jsFunction3() {
	  var myselect = document.getElementById("archiveYearsChooser");
	  document.getElementsByName("archiveYearsOld")[0].value = myselect.options[myselect.selectedIndex].value;
	}

function jsFunction4() {
	  var myselect = document.getElementById("btSwitchChooser");
	  document.getElementsByName("btSwitchOld")[0].value = myselect.options[myselect.selectedIndex].value;
	}

function jsFunction5() {
	  var myselect = document.getElementById("eventSwitchChooser");
	  document.getElementsByName("eventSaveSwitchOld")[0].value = myselect.options[myselect.selectedIndex].value;
	}

</script>   	
</html>
