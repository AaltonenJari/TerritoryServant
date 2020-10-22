<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Käyttäjähallinta</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/person.css"); ?>">
  
  <script src="<?php echo base_url("assets/javascript/territoriesToPDF.js"); ?>"></script> 

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
    <?php $sivu_tunnus = "6"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>

    <!-- Asetetaan sivun pääotsikko -->
    <h1>Käyttäjähallinta</h1>

    <div id="filterArea" class="filterArea">
      <table id="selectortable">
        <tr>
          <th width="15%">Etsi / Rajaa</th>
          <th width="35%"></th>
          <th width="35%"></th>
          <th width="15%">Perustilaan</th>
        </tr>
  		<tr>
          <td>
 		    <input type="search" id="filterString" class="light-table-filter" data-table="order-table" placeholder="Filter">

	  		<?php $display_baseurl = base_url("index.php/User_controller/display") . "/" . $sort_by . "/" . $sort_order; ?>
      		<input type="hidden" id="displayBaseUrl" value="<?php echo $display_baseurl; ?>" />

	        <input type="hidden" id="filter_param" value="<?php echo $filter; ?>"/>
	        <input type="hidden" id="base_delete_url" value="<?php echo base_url("index.php/User_controller/delete"); ?>" />
	        <input type="hidden" id="base_profile_url" value="<?php echo base_url("index.php/User_controller/update_profile"); ?>" />
	        <input type="hidden" id="base_update_url" value="<?php echo base_url("index.php/User_controller/update"); ?>" />

	  		<?php $form_action_baseurl = base_url("index.php/User_controller/check_update"); ?>
      		<input type="hidden" id="formBaseUrl" value="<?php echo $form_action_baseurl; ?>" />
          </td>
          <td>
          </td>
          <td>
          </td>
          <td>
            <a href="<?php echo base_url("index.php/User_controller/display"); ?>" target="_parent" class="btn-clear"><button>CLR</button></a>
          </td>
  		</tr>
      </table>
    </div>

    <div id="content">
      <?php 
        $form_open_parameter = "User_controller/check_update";
        if (!empty($filter)) {
            $form_open_parameter .= "/" . $filter;
        }
        $attributes = ['id' => 'user_update_form'];
        echo form_open($form_open_parameter,$attributes); 
      ?>
        <div class="tableWrap">
          <table id="table2" class="order-table table">
            <thead>
              <tr>
                  <?php foreach ($display_fields as $field_name => $field_display) { ?>
                      <th <?php if ($sort_by == $field_name) echo "class=\"sort_" . $sort_order . "\"" ?>><span>
                          <?php $hdrurl = base_url("index.php/User_controller/display") . "/" . $field_name . "/" .
                              (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'); ?>
                          <?php $field_name_old = $field_display ."old" ?>
                          <input type="hidden" id="<?php echo $field_name_old; ?>" value="<?php echo $hdrurl; ?>" />
                          <?php if (!empty($filter)) { $hdrurl .= "/" . $filter; } ?>
                          <a id="<?php echo $field_display; ?>"
                             href="<?php echo $hdrurl; ?>"><?php echo $field_display; ?></a>
                      </span></th>
                  <?php } ?>
              <th>Poisto</th>
			  </tr>
            </thead>
            <tbody>
              <?php $rowidx = 0; $userid = 0; ?>
              <?php foreach ($users as $user) { ?>
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
                               case "user_id": ?>
                                   <td id="<?php echo $field_id_data; ?>">
                                   <?php
                                   $attributes = [
                                   'class' => $field_name
                                   ];
                                   echo form_label($user->$field_name, $field_id_data, $attributes);
                                   
                                   //Piilokenttä vanhaa arvoa varten
                                   $data_hidden = [
                                       'type'  => 'hidden',
                                       'id'    => $field_input_name_old_id,
                                       'name'  => $field_name_old_data,
                                       'value' => $user->$field_name,
                                       'class' => $field_name_old
                                   ];
                                   echo form_input($data_hidden);
                                   $userid = $user->$field_name;
                                   ?>
                                   </td>
                                   <?php
                                   break;
                                   
                               case "user_admin": ?>
                                   <td id="<?php echo $field_id_data; ?>">
                                   <?php
                                   //Piilokenttä uutta arvoa varten
                                   $data = [
                                       'type'  => 'hidden',
                                       'id'    => $field_input_name_id,
                                       'name'  => $field_name_data,
                                       'value' => $user->$field_name,
                                       'class' => $field_name
                                   ];
                                   echo form_input($data);
                                   
                                   //Editoitava kenttä
                                   $field_input_editing_id = $field_input_name_id . "_dropdown";
                                   $js = [
                                       'id'       => $field_input_editing_id,
                                       'class'    => "dropdown_field",
                                       'onChange' => "jsDropdownChance(this, " . $field_input_name_id . ")"
                                   ];
                                   //Valitsimet
                                   echo form_dropdown($field_input_editing_id, $userRoleOptions, $user->$field_name, $js);
                                   
                                   //Piilokenttä vanhaa arvoa varten
                                   $data_hidden = [
                                       'type'  => 'hidden',
                                       'id'    => $field_input_name_old_id,
                                       'name'  => $field_name_old_data,
                                       'value' => $user->$field_name,
                                       'class' => $field_name_old
                                   ];
                                   echo form_input($data_hidden);
                                   ?>
                                   </td>
                                   <?php
                                   break;

                               default: ?>
                                   <td id="<?php echo $field_id_data; ?>">
                                   <?php
                                   //Editoitava kenttä
                                   $data = [
                                       'type'  => 'text',
                                       'id'    => $field_input_name_id,
                                       'name'  => $field_name_data,
                                       'value' => $user->$field_name,
                                       'class' => $field_name
                                   ];
                                   $js = ['onChange' => 'jsFunction2(' . $field_input_name_id . ', ' . $userid . ');'];
                                   echo form_input($data,' ',$js);
                                   
                                   //Piilokenttä vanhaa arvoa varten
                                   $data_hidden = [
                                       'type'  => 'hidden',
                                       'id'    => $field_input_name_old_id,
                                       'name'  => $field_name_old_data,
                                       'value' => $user->$field_name,
                                       'class' => $field_name_old
                                   ];
                                   echo form_input($data_hidden);
                                   ?>
                                   </td>
                                   <?php
                                   break;
                           } // switch
                      } //foreach display_fields ?>
                    <td>
                      <?php $update_url = base_url("index.php/User_controller/update_profile") . "/admin/" . $user->user_id . "/" . $filter; ?>
    			      <a id="<?php echo "profile_" . $user->user_id; ?>" href="<?php echo $update_url; ?>" onClick='jsFunction4("<?php echo $user->user_id; ?>")'>Profiili</a>
                      <span> / </span>
                      <?php $delete_url = base_url("index.php/User_controller/delete") . "/" . $user->user_id . "/" . $filter; ?>
    			      <a id="<?php echo "del_" . $user->user_id; ?>" href="<?php echo $delete_url; ?>" onClick='jsFunction3("<?php echo $user->user_id; ?>")'>Poista</a>
                    </td>
                  </tr>
               <?php } //foreach users ?>
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
          <td width="40%">
          <?php          
          $data = [
             'type'  => 'submit',
             'id'    => 'submit_update',
             'name'  => 'action_btn',
             'value' => 'Päivitä',
             'class' => 'submit_btn'
          ];
             
          $js = ['onClick' => 'jsFunction_update(this);'];
          echo form_input($data,' ',$js);
          ?>
          </td>
          <td width="30%">
          <?php
          $data = [
             'type'  => 'submit',
             'id'    => 'submit_add',
             'name'  => 'action_btn',
             'value' => 'Lisää',
             'class' => 'submit_btn'
          ];
             
          $js = ['onClick' => 'jsFunction_add(this);'];
          echo form_input($data,' ',$js);
          ?>
          </td>
          <td width="15%">
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
          <td width="15%">
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

    <div id="bottomArea" class="bottomArea">
      <table id="bottomtable">
        <tr>
          <td width="85%">
            <div id="totalcount">
              <span>Löytyi: <span id="tableRowCount"> <?php echo $num_results; ?></span> henkilöä</span>
            </div>
          </td>
          <td width="15%">
            <div id="reportPrint">
              <input type="button" value="Raportti" class="btnAction" onclick="createPDF()" />
            </div>
          </td>
        </tr>
      </table>
    </div>


  </div><!-- wrapper -->
</body>

<script>
var $rows = $('#table2 tbody tr');

$('#filterString').keyup(function() {
  var searchText = $(this).val().toLowerCase();

  //Filter the table and get tche count of the sown rows
  var rowCount = filter_table(searchText);

  document.getElementById("tableRowCount").innerHTML = rowCount;

  //Zebra stripe the table after filtering
  var k = 0;
  var table = document.getElementById("table2");
  for (var i = 0, row; row = table.rows[i]; i++) {
  	row = table.rows[i];
      if (!(row.style.display === 'none')) {
      	if (k % 2) {
         		row.style.backgroundColor = "#eee";
           } else  {
         		row.style.backgroundColor = "white";
          }
          k++;
      }
  }         

  //Display settings
  document.getElementById("filter_param").value = searchText;

  document.getElementById("tunnus").href = document.getElementById("tunnusold").value +
 	    "\\" + document.getElementById("filter_param").value;

  document.getElementById("käyttäjänimi").href = document.getElementById("käyttäjänimiold").value +
        "\\" + document.getElementById("filter_param").value;

  document.getElementById("etunimi").href = document.getElementById("etunimiold").value +
        "\\" + document.getElementById("filter_param").value;

  document.getElementById("sukunimi").href = document.getElementById("sukunimiold").value +
        "\\" + document.getElementById("filter_param").value;

  document.getElementById("sähköposti").href = document.getElementById("sähköpostiold").value +
        "\\" + document.getElementById("filter_param").value;

  document.getElementById("Käyttäjärooli").href = document.getElementById("Käyttäjärooliold").value +
        "\\" + document.getElementById("filter_param").value;

  document.getElementById('user_update_form').action = document.getElementById("formBaseUrl").value +
        "\\" + document.getElementById("filter_param").value;
});

$(document).ready(function() {
	  var searchText = $('#filter_param').val().toLowerCase();
	  document.getElementById("filterString").value = searchText;

	  //Filter the table and get tche count of the sown rows
	  var rowCount = filter_table(searchText);
	  document.getElementById("tableRowCount").innerHTML = rowCount;

	  //Zebra stripe the table after filtering
	  $("#table2 tbody tr:visible:even").css("background-color", "#eee");
});

function filter_table(searchText) {
	  $rows
	    .show()
	    .filter(function() {
	      var $inputs = $(this).find("input:text");
	      var found = searchText.length == 0; // for empty search, show all rows
	      for (var i=0; i < $inputs.length && !found; i++) {
	        var text = $inputs.eq(i).val().toLowerCase().replace(/\s+/g, ' ');
	        found = text.length > 0 && text.indexOf(searchText) >= 0;
	      }
	      return !found;
	   })
	   .hide();
	   
	  //Get the row count of the filtered table
	  var rowCount = 0;
	  var rows = document.getElementById("table2").getElementsByTagName("tr");
	  for (var i = 0; i < rows.length; i++) {
	      if (rows[i].style.display == 'none') {
	      	continue;
	      }
	      if (rows[i].getElementsByTagName("td").length > 0) {
	          rowCount++;
	      }
	  }
	  return rowCount;
}

function jsFunction2(fieldObject, userId) {
	var newValue = document.getElementById(fieldObject.id).value;
	if (!newValue.length) { //Jos tyhjä, käytä arvoa '0'.
		newValue = "0";
	}
	var fieldName = document.getElementById(fieldObject.id).className;
	
	var newUrl = document.getElementById("base_update_url").value;
	var newUrl = newUrl + "/" + userId + "/" + fieldName + "/" + newValue;
	var newUrl = newUrl + "/" + document.getElementById("filter_param").value;
	//alert(newUrl);
	//location.replace(newUrl);
}

function jsFunction3(userId) {
    var newUrl = document.getElementById("base_delete_url").value;
    newUrl = newUrl + "/" + userId + "/" + document.getElementById("filter_param").value;
    var idIndex = "del_" + userId;
	document.getElementById(idIndex).href = newUrl;
}

function jsFunction4(userId) {
    var newUrl = document.getElementById("base_profile_url").value;
	newUrl = newUrl + "/admin/" + userId + "/" + document.getElementById("filter_param").value;
    var idIndex = "profile_" + userId;
	document.getElementById(idIndex).href = newUrl;
}


function jsDropdownChance(selectObject,fieldObject) {
	var selectedValue = selectObject.value;
	document.getElementById(fieldObject.id).value = selectedValue;
}

function jsFunction_update(me) {
	document.getElementById("submit_action").value = "Update";
}

function jsFunction_add(me) {
	document.getElementById("submit_action").value = "Add";
}

function jsFunction_undo(me) {
	document.getElementById("submit_action").value = "Undo";
}

function jsFunction_redo(me) {
	document.getElementById("submit_action").value = "Redo";
}
</script>
</html>
