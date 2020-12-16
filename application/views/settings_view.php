<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Asetukset</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/settings.css"); ?>">
  
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

   <div class="headerArea">
      <!-- Asetetaan sivun pääotsikko -->
      <h1>TerritoryServant - Asetukset</h1>
    </div>

    <div id="content">
      <?php 
        $form_open_parameter = "Settings_controller/check_update";
        $attributes = ['id' => 'settings_form'];
        echo form_open($form_open_parameter,$attributes);
      ?>
        <div class="tableWrap">
          <table id="table2" class="order-table table">
            <thead>
              <tr>
                <?php foreach ($display_fields as $field_name => $field_display) { 
                  switch ($field_name) {
                      case "setting_id":
                      case "setting_order_id":
                      case "setting_input_type":
                      case "setting_input_id":
                      case "setting_admin":
                          break;
                          
                      case "setting_desc":
                      case "setting_value":
                          ?>
                          <th <?php if ($sort_by == $field_name) echo "class=\"sort_" . $sort_order . "\"" ?>><span>
                              <?php $hdrurl = base_url("index.php/Settings_controller/display") . "/" . $field_name . "/" .
                                  (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'); ?>
                              <?php $field_name_old = $field_display ."old" ?>
                              <input type="hidden" id="<?php echo $field_name_old; ?>" value="<?php echo $hdrurl; ?>" />
                              <a id="<?php echo $field_display; ?>"
                                 href="<?php echo $hdrurl; ?>"><?php echo $field_display; ?></a>
                          </span></th>
                          <?php
                          break;
                      
                      default:    
                          break;
                  }
                } // foreach $display_fields ?>
              </tr>
            </thead>
            <tbody>
              <?php $rowidx = 0; ?>
              <?php foreach ($settings as $setting) { ?>
                <?php if (($setting->setting_admin == "0") || ($setting->setting_admin == "1" && $admin == "1")) { ?>
                <tr>
                  <?php $rowidx++; ?>
                  <?php foreach ($display_fields as $field_name => $field_display) {
                     $field_id_data = $field_name . $rowidx;
                     $field_name_data = $field_name . "[]";
                     $field_name_old_data = $field_name . "_old" . "[]";
                     
                     $field_input_name_id = "input_". $field_name . $rowidx;
                     $field_input_name_old_id = "input_old_". $field_name . $rowidx;
                     $field_name_old = $field_name . "_old";
                     
                     switch ($field_name) {
                         case "setting_desc": ?>
                             <td id="<?php echo $field_id_data; ?>"> 
                             <?php 
                             //Näytettävä kenttä
                             $attributes = [
                                 'class' => $field_name
                             ];
                             echo form_label($setting->$field_name, $field_id_data, $attributes);
                             
                             //Piilokenttä joka lähetetään eteenpäin
                             $data_hidden = [
                                 'type'  => 'hidden',
                                 'id'    => $field_input_name_old_id,
                                 'name'  => $field_name_old_data,
                                 'value' => $setting->$field_name,
                                 'class' => $field_name_old
                             ];
                             echo form_input($data_hidden);
                             
                             //Piilokenttä avainta varten (setting_input_id)
                             $data_hidden_key = [
                                 'type'  => 'hidden',
                                 'id'    => 'input_old_setting_id'. $rowidx,
                                 'name'  => 'setting_id_old' . '[]',
                                 'value' =>  $setting->setting_input_id,
                                 'class' => 'setting_id_old'
                             ];
                             echo form_input($data_hidden_key);
                             ?>
                             </td>
                             <?php
                             break;
             
                         case "setting_value": ?>
                             <td id="<?php echo $field_id_data; ?>">
                               <?php
                               switch ($setting->setting_input_type) {
                                   case "dropbox":
                                       //Piilokenttä uutta arvoa varten
                                       $data = [
                                          'type'  => 'hidden',
                                          'id'    => $field_input_name_id,
                                          'name'  => $field_name_data,
                                          'value' => $setting->$field_name,
                                          'class' => $field_name
                                       ];
                                       echo form_input($data);
                                       
                                       //Editoitava kenttä
                                       $field_input_editing_id = $setting->setting_input_id;
                                       $js = [
                                           'id'       => $field_input_editing_id,
                                           'class'    => "dropdown_field",
                                           'onChange' => "jsDropdownChance(this, " . $field_input_name_id . ")"
                                       ];
                                       //Valitsimet
                                       $options = $setting->setting_input_id . "Options";
                                       echo form_dropdown($field_input_editing_id, $$options, $setting->$field_name, $js);
                                       
                                       //Piilokenttä vanhaa arvoa varten
                                       $data_hidden = [
                                          'type'  => 'hidden',
                                          'id'    => $field_input_name_old_id,
                                          'name'  => $field_name_old_data,
                                          'value' => $setting->$field_name,
                                          'class' => $field_name_old
                                       ];
                                       echo form_input($data_hidden);
                                       break;
                                   
                                   case "checkbox":
                                       //Piilokenttä uutta arvoa varten
                                       $data = [
                                           'type'  => 'hidden',
                                           'id'    => $field_input_name_id,
                                           'name'  => $field_name_data,
                                           'value' => $setting->$field_name,
                                           'class' => $field_name
                                       ];
                                       echo form_input($data);
                                       
                                       //Editoitava kenttä
                                       $field_input_editing_id = $setting->setting_input_id;
                                       $js = [
                                           'id'       => $field_input_editing_id,
                                           'class'    => "checkbox_field",
                                           'onChange' => "jsCheckBoxChance(this, " . $field_input_name_id . ")"
                                       ];
                                       //Valitsin
                                       $checkboxName = $setting->setting_input_id;
                                       $checked = false;
                                       if ($setting->$field_name == 1) {
                                           $checked = true;
                                       }
                                       echo form_checkbox($checkboxName, $setting->$field_name, $checked, $js);
                                       
                                       
                                       //Piilokenttä vanhaa arvoa varten
                                       $data_hidden = [
                                           'type'  => 'hidden',
                                           'id'    => $field_input_name_old_id,
                                           'name'  => $field_name_old_data,
                                           'value' => $setting->$field_name,
                                           'class' => $field_name_old
                                       ];
                                       echo form_input($data_hidden);
                                       break;
                                       
                                   case "date":
                                   case "datereadonly":
                                       //Editoitava kenttä
                                       $field_input_editing_id = $setting->setting_input_id;
                                       
                                       $data = array();
                                       $data['type'] = 'text';
                                       $data['id'] = $field_input_editing_id;
                                       $data['name'] = $field_name_data;
                                       $data['value'] = set_value($field_input_editing_id, date('j.n.Y', strtotime($setting->$field_name)));
                                       if ($setting->setting_input_type == "datereadonly") {
                                           $data['readonly'] = 'readonly';
                                           $data['class'] = 'enableadminonly';
                                       } else {
                                           $data['class'] = $field_name;
                                       }
             
                                       echo form_input($data);
                                       //Piilokenttä vanhaa arvoa varten
                                       $field_input_editing_old_id = $setting->setting_input_id . "Old";
                                       $data_hidden = [
                                          'type'  => 'hidden',
                                          'id'    => $field_input_editing_old_id,
                                          'name'  => $field_name_old_data,
                                          'value' => $setting->$field_name,
                                          'class' => $field_name_old
                                       ];
                                       echo form_input($data_hidden);
                                       break;
                                   
                                   case "adminreadonly":
                                       //Editoitava kenttä
                                       $data = array();
                                       $data['type'] = 'text';
                                       $data['id'] = $field_input_name_id;
                                       $data['name'] = $field_name_data;
                                       $data['value'] = $setting->$field_name;
                                       if (empty($admin)) {
                                           $data['readonly'] = 'readonly';
                                           $data['class'] = 'enableadminonly';
                                           //$data['disabled'] = 'disabled';
                                       } else {
                                           $data['class'] = $field_name;
                                       }
                                       
                                       echo form_input($data);
                                       //Piilokenttä vanhaa arvoa varten
                                       $data_hidden = [
                                       'type'  => 'hidden',
                                       'id'    => $field_input_name_old_id,
                                       'name'  => $field_name_old_data,
                                       'value' => $setting->$field_name,
                                       'class' => $field_name_old
                                       ];
                                       echo form_input($data_hidden);
                                       break;
                                   
                                   case "text":
                                       //Editoitava kenttä
                                       $data = [
                                           'type'  => 'text',
                                           'id'    => $field_input_name_id,
                                           'name'  => $field_name_data,
                                           'value' => $setting->$field_name,
                                           'class' => $field_name
                                       ];
                                       echo form_input($data);
                                       //Piilokenttä vanhaa arvoa varten
                                       $data_hidden = [
                                           'type'  => 'hidden',
                                           'id'    => $field_input_name_old_id,
                                           'name'  => $field_name_old_data,
                                           'value' => $setting->$field_name,
                                           'class' => $field_name_old
                                       ];
                                       echo form_input($data_hidden);
                                       break;
                                   
                                   default:
                                     break;
                               }
                               ?> 
                             </td>
                             <?php
                             break;
             
                         default:    
                             break;
                    } // switch
                  } //foreach display_fields ?>
                </tr>
                <?php } ?>
              <?php } //foreach settings ?>
            </tbody>
          </table>
          <p>
            <?php
            $data_hidden = [
                'type'  => 'hidden',
                'id'    => 'submit_action',
                'name'  => 'action',
                'value' => 'Update',
                'class' => 'submit_action_btn'
            ];
            echo form_input($data_hidden);
            ?>
          </p>
      </div><!-- tableWrap -->
      <table id="cardbuttons">
        <tr>
          <td width="35%">
            <?php          
            $data = [
               'type'  => 'submit',
               'id'    => 'submit_remove',
               'name'  => 'action_btn',
               'value' => 'Päivitä',
               'class' => 'submit_btn'
            ];
               
            $js = ['onClick' => 'jsFunction_update(this);'];
            echo form_input($data,' ',$js);
            ?>
           </td>
          <td width="10%">
            <?php if ($can_undo) {
                $data = [
                    'type'  => 'image',
                    'id'    => 'submit_undo',
                    'name'  => 'action_btn',
                    'value' => 'Undo',
                    'class' => 'submit_undo',
                    'src' => base_url("assets/images/Undo.jpg")
                ];
                
                $js = ['onClick' => 'jsFunction_undo(this);'];
                echo form_submit($data,' ',$js);
            } else {
                $data = [
                    'type'  => 'image',
                    'id'    => 'submit_undo_disabled',
                    'name'  => 'action_btn_disabled',
                    'value' => 'Undo',
                    'class' => 'submit_btn_disabled',
                    'src' => base_url("assets/images/Undo_disabled.jpg"),
                    'disabled'  => 'true'
                ];
                echo form_submit($data);
            } ?>
            </td>
          <td width="25%">
            <?php if ($can_redo) {
                $data = [
                    'type'  => 'image',
                    'id'    => 'submit_redo',
                    'name'  => 'action_btn',
                    'value' => 'Redo',
                    'class' => 'submit_undo',
                    'src' => base_url("assets/images/Redo.jpg")
                ];
                
                $js = ['onClick' => 'jsFunction_redo(this);'];
                echo form_submit($data,' ',$js);
                
            } else {
                $data = [
                    'type'  => 'image',
                    'id'    => 'submit_redo_disabled',
                    'name'  => 'action_btn_disabled',
                    'value' => 'Redo',
                    'class' => 'submit_btn_disabled',
                    'src' => base_url("assets/images/Redo_disabled.jpg"),
                    'disabled'  => 'true'
                ];
                echo form_submit($data);
            } ?>
            </td>
          <td width="10%">
          </td>
          <td width="20%">
            <?php          
            $data = [
               'type'  => 'submit',
               'id'    => 'submit_return_history',
               'name'  => 'action_btn',
               'value' => 'Paluu',
               'class' => 'submit_btn'
            ];
               
            $js = ['onClick' => 'jsFunction_return(this);'];
            echo form_input($data,' ',$js);
            ?>
         </td>
        </tr>
        <tr>
          <td colspan="5">
            <?php echo $table_not_found;	?>
          </td>
        </tr>
        <tr>
          <td colspan="4">
            <?php echo $this->session->flashdata("error");	?>
          </td>
        </tr>
      </table> <!-- cardbuttons -->
    
    <?php echo form_close(); ?>
    </div><!-- content -->

    <div class="middleArea">
    </div>

 

  </div><!-- wrapper -->
</body>

<script>
$(function() {
    $("#circuitWeekStart").datepicker();
});

$("#circuitWeekStart").datepicker(
    {
        dateFormat: 'dd.mm.yy'
    }
);

$("#circuitWeekStart").change(function(){
	var starts = $("#circuitWeekStart").val();
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
    	$('#circuitWeekEnd').val(endDate);
        return true;
    }
    else {
        alert("Kierrosviikon alku ei ole tiistai! " + dateValue);
        //Palauta vanha päivä
        $('#circuitWeekStart').val(document.getElementsByName("circuitWeekStartOld")[0].value);
        $('#circuitWeekEnd').val(document.getElementsByName("circuitWeekEndOld")[0].value);
        return false; 
    } 
}); 


function jsCheckBoxChance(me,fieldObject) {
    var chkValue = 0;
	if (me.checked) {
		chkValue = 1;
	}
	document.getElementById(fieldObject.id).value = chkValue;
}

function jsDropdownChance(selectObject,fieldObject) {
	var selectedValue = selectObject.value;
	document.getElementById(fieldObject.id).value = selectedValue;
}

function jsFunction4() {
	var myselect = document.getElementById("terrCodeChkBoxChooser");
	document.getElementById("selCodeOld").value = myselect.options[myselect.selectedIndex].value;
    var newUrl = document.getElementById("displayBaseUrl").value;
	//alert(newUrl);
	location.replace(newUrl);
}

function jsFunction_update(me) {
	document.getElementById("submit_action").value = "Update";
}

function jsFunction_return(me) {
	document.getElementById("submit_action").value = "Return";
}

function jsFunction_undo(me) {
	document.getElementById("submit_action").value = "Undo";
}

function jsFunction_redo(me) {
	document.getElementById("submit_action").value = "Redo";
}
</script>
</html>
