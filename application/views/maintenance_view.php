<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Ylläpito</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory_update.css"); ?>">
  
  <script src="<?php echo base_url("assets/javascript/territoriesToPDF.js"); ?>"></script> 
  <script src="<?php echo base_url('assets/javascript/resizableHeight.js'); ?>"></script>

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
    <?php $sivu_tunnus = "4"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>

    <div class="headerArea">
      <!-- Asetetaan sivun pääotsikko -->
      <h1>TerritoryServant - Alueiden ylläpito</h1>
    </div>

    <div id="filterArea" class="filterArea">
      <table id="selectortable">
        <tr>
          <th width="15%">Etsi / Rajaa</th>
          <th width="35%">Koodi</th>
          <th width="35%"></th>
          <th width="15%">Perustilaan</th>
        </tr>
  		<tr>
          <td>
 		    <input type="search" id="filterString" class="light-table-filter" data-table="order-table" placeholder="Filter">

	  		<?php $display_baseurl = base_url("index.php/Maintenance_controller/display") . "/" . $sort_by . "/" . $sort_order; ?>
      		<input type="hidden" id="displayBaseUrl" value="<?php echo $display_baseurl; ?>" />

	        <input type="hidden" id="filter_param" value="<?php echo $filter; ?>"/>
	        <input type="hidden" id="base_status_url" value="<?php echo base_url("index.php/Maintenance_controller/update_status"); ?>" />
	        <input type="hidden" id="base_delete_url" value="<?php echo base_url("index.php/Maintenance_controller/delete"); ?>" />

	  		<?php $form_action_baseurl = base_url("index.php/Maintenance_controller/check_update"); ?>
      		<input type="hidden" id="formBaseUrl" value="<?php echo $form_action_baseurl; ?>" />
          </td>
		  <td>
		    <input type="hidden" id="selCodeOld" value="<?php echo $code_sel; ?>" />
      		<select name="terrCodeChkBoxChooser" id="terrCodeChkBoxChooser" onChange="territory_code_change()">
         	  <option value="0" <?php if ($code_sel == "0") echo "selected=\"selected\""?> >Kaikki</option>
			  <?php 
                foreach ($territory_codes as $territory_code) {
                  foreach ($territory_code as $key=>$value) { ?>
  		            <option value="<?php echo $value; ?>" <?php if ($code_sel == $value) echo "selected=\"selected\""?> ><?php echo $value; ?></option>
                  <?php }
                }
              ?>
      	    </select> 
		  </td>
          <td>
          </td>
          <td>
            <a href="<?php echo base_url("index.php/Maintenance_controller/display"); ?>" target="_parent" class="btn-clear"><button>CLR</button></a>
          </td>
  		</tr>
      </table>
    </div>

    <div id="content">
      <?php 
        $form_open_parameter = "Maintenance_controller/check_update";
        if (!empty($code_sel)) {
            $form_open_parameter .= "/" . $code_sel;
        }
        if (!empty($filter)) {
            if (!empty($code_sel)) {
                $form_open_parameter .=  "/" . $filter;
            } else {
                $form_open_parameter .= "/0/" . $filter;
            }
        }
        $attributes = ['id' => 'maintenance_form'];
        echo form_open($form_open_parameter,$attributes);
      ?>
      <div class="contentResizable" style="<?php echo $saved_height ? 'height:'.$saved_height.'px;' : ''; ?>">
        <div class="scrollInner">
          <table id="table2" class="table">
            <thead>
              <tr>
                  <?php foreach ($display_fields as $field_name => $field_display) { ?>
                      <th <?php if ($sort_by == $field_name) echo "class=\"sort_" . $sort_order . "\"" ?>><span>
                          <?php $hdrurl = base_url("index.php/Maintenance_controller/display") . "/" . $field_name . "/" .
                              (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'); ?>
                          <?php $field_name_old = $field_display ."old" ?>
                          <input type="hidden" id="<?php echo $field_name_old; ?>" value="<?php echo $hdrurl; ?>" />
                          <?php if (!empty($code_sel)) { $hdrurl .= "/" . $code_sel; } else { $hdrurl .= "/0"; } ?>
                          <?php if (!empty($filter)) { $hdrurl .= "/" . $filter; } ?>
                          <a id="<?php echo $field_display; ?>"
                             href="<?php echo $hdrurl; ?>"><?php echo $field_display; ?></a>
                      </span></th>
                  <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php $rowidx = 0; ?>
              <?php foreach ($alueet as $alue) { ?>
                  <tr class="<?php echo empty($alue->$field_name) ? 'two-row-delete' : ''; ?>">
                      <?php $rowidx++; ?>
                       <?php foreach ($display_fields as $field_name => $field_display) {
                           $field_id_data = $field_name . $rowidx;
                           $field_name_data = $field_name . "[]";
                           $field_name_old_data = $field_name . "_old" . "[]";
                           
                           $field_input_name_id = "input_". $field_name . $rowidx;
                           $field_input_name_old_id = "input_old_". $field_name . $rowidx;
                           $field_name_old = $field_name . "_old";
                           
                           switch ($field_name) {
                               case "alue_code": ?>
                                   <td id="<?php echo $field_id_data; ?>"> 
                                   <?php 
                                   $attributes = [
                                       'class' => $field_name
                                   ];
                                   echo form_label($alue->$field_name, $field_id_data, $attributes);
                                   $data_hidden = [
                                          'type'  => 'hidden',
                                          'id'    => $field_input_name_old_id,
                                          'name'  => $field_name_old_data,
                                          'value' =>  $alue->$field_name,
                                          'class' => $field_name_old
                                      ];
                                   echo form_input($data_hidden);
                                   ?> 
                                   </td>
                                   <?php
                                   break;

                               case "event_count": ?>
                                   <td id="<?php echo $field_id_data; ?>"> 
                                   <div class="delete_link_container">
                                   <?php
                                   if ($alue->alue_group == '99') {
                                       // Palauta
                                       $recover_url = base_url("index.php/Maintenance_controller/update_status")
                                           . "/" . $alue->alue_code . "/recover/" . $filter;
                                       ?>
                                       <a class="delete-link-row" id="<?php echo "recover_" . $alue->alue_code; ?>"
                                          href="<?php echo $recover_url; ?>"
                                          onClick='territory_update_status("<?php echo $alue->alue_code; ?>", "recover")'>
                                          Palauta
                                       </a>
                                   <?php
                                   } else {
                                       // Poista
                                       $delete_url = base_url("index.php/Maintenance_controller/update_status")
                                           . "/" . $alue->alue_code . "/delete/" . $filter;
                                       ?>
                                       <a class="delete-link-row" id="<?php echo "delete_" . $alue->alue_code; ?>"
                                          href="<?php echo $delete_url; ?>"
                                          onClick='territory_update_status("<?php echo $alue->alue_code; ?>", "delete")'>
                                          Merkitse poistetuksi
                                       </a>
                                       <?php
                                       if (empty($alue->$field_name)) {
                                          $delete_url = base_url("index.php/Maintenance_controller/delete") . "/" . $alue->alue_code . "/delete/" . $filter; ?>
                                          <a href="#" class="delete-link-row" onclick="confirmDelete('<?php echo $alue->alue_code; ?>', '<?php echo $delete_url; ?>'); return false;">
                                            Poista pysyvästi
                                          </a>
                                       <?php } ?>
                                   <?php } ?>
                                   </div>
                                   </td>
                                   <?php
                                   break;
 
                              default: ?>
                                   <td id="<?php echo $field_id_data; ?>">
                                     <?php 
                                     $data = [
                                         'type'  => 'text',
                                         'id'    => $field_input_name_id,
                                         'name'  => $field_name_data,
                                         'value' =>  $alue->$field_name,
                                         'class' => $field_name
                                     ];
                                     echo form_input($data);
                         
                                     $data_hidden = [
                                         'type'  => 'hidden',
                                         'id'    => $field_input_name_old_id,
                                         'name'  => $field_name_old_data,
                                         'value' =>  $alue->$field_name,
                                         'class' => $field_name_old
                                     ];
                                     echo form_input($data_hidden);
                                     ?> 
                                   </td>
                                   <?php
                                   break;
                           } // switch
                      } //foreach display_fields ?>
                  </tr>
               <?php } //foreach alueet ?>
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
       </div>
      </div><!-- contentResizable -->

      <div class="button-area">
        <div class="button-group">
          <div class="button-left">
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
          </div>
 
          <div class="buttons-center">
            <?php if ($can_undo) {
              $data = [
                  'type'  => 'image',
                  'id'    => 'submit_undo',
                  'name'  => 'action_btn',
                  'value' => 'Undo',
                  'class' => 'submit_undo',
                  'src' => base_url("assets/images/Undo.jpg"),
                  'title' => 'Kumoa'
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

            <?php if ($can_redo) {
              $data = [
                  'type'  => 'image',
                  'id'    => 'submit_redo',
                  'name'  => 'action_btn',
                  'value' => 'Redo',
                  'class' => 'submit_undo',
                  'src' => base_url("assets/images/Redo.jpg"),
                  'title' => 'Tee uudelleen'
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
            </div>

            <div class="button-right">
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
            </div>
          </div><!-- button-group -->

          <div class="form-error">
            <?php echo $this->session->flashdata("error"); ?>
          </div>
        </div><!-- button-area -->
    
    <?php echo form_close(); ?>
    </div><!-- content -->

    <div id="bottomArea" class="bottomArea">
      <table id="bottomtable">
        <tr>
          <td width="85%">
            <div id="totalcount">
              <span>Löytyi: <span id="tableRowCount"> <?php echo $num_results; ?></span> aluetta</span>
            </div>
          </td>
          <td width="15%">
            <div id="reportPrint">
              <input type="button" value="Raportti" class="btnAction" onclick="territoryMaintenanceToPDF()" />
            </div>
          </td>
        </tr>
      </table>
    </div>


  </div><!-- wrapper -->
  
  <div id="confirmModal" class="confirm-dialog">
    
    <div class="confirm-dialog-container">
        
        <div class="confirm-text" id="confirmLine1"></div>
		<div class="confirm-text" id="confirmLine2"></div>
        
        <div class="confirm-button-area">
          <div class="confirm-button-group">
            <button class="confirm-button-left" id="confirmYes">Kyllä</button>
            <button class="confirm-button-right" onclick="closeConfirm()">Ei</button>
          </div>
        </div>
    </div>
  </div>
  
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

  document.getElementById("numero").href = document.getElementById("numeroold").value +
 	    "\\" + document.getElementById("selCodeOld").value +
 	    "\\" + document.getElementById("filter_param").value;

  document.getElementById("alue_nimi").href = document.getElementById("alue_nimiold").value +
        "\\" + document.getElementById("selCodeOld").value +
        "\\" + document.getElementById("filter_param").value;

  document.getElementById("lisätieto").href = document.getElementById("lisätietoold").value +
	    "\\" + document.getElementById("selCodeOld").value +
 	    "\\" + document.getElementById("filter_param").value;

  document.getElementById("koko").href = document.getElementById("kokoold").value +
        "\\" + document.getElementById("selCodeOld").value +
        "\\" + document.getElementById("filter_param").value;

  document.getElementById("poisto").href = document.getElementById("poistoold").value +
        "\\" + document.getElementById("selCodeOld").value +
        "\\" + document.getElementById("filter_param").value;
  
  document.getElementById('maintenance_form').action = document.getElementById("formBaseUrl").value +
        "\\" + document.getElementById("selCodeOld").value +
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
	  searchText = searchText.trim().toLowerCase();

	  $rows.show().filter(function() {
	    if (!searchText) return false; // jos hakukenttä on tyhjä, ei suodateta mitään

	    var found = false;
	    $(this).find("input:text, label, td").each(function() {
	      var text = ($(this).val() || $(this).text() || "")
	        .toLowerCase()
	        .replace(/\s+/g, " ");
	      if (text.includes(searchText)) {
	        found = true;
	        return false; // lopeta looppi
	      }
	    });
	    return !found; // jos ei löytynyt, piilota rivi
	  }).hide();

	  // Laske näkyvät rivit
	  var rowCount = $("#table2 tr:visible td").closest("tr").length;
	  return rowCount;
}

function territory_update_status(alue_code, action) {
    var baseUrl = document.getElementById("base_status_url").value;
    var filter = document.getElementById("filter_param").value;
    var newUrl = baseUrl + "/" + alue_code + "/" + action + "/" + filter;
    var idIndex = action + "_" + alue_code;
    document.getElementById(idIndex).href = newUrl;
}

function territory_code_change() {
	var myselect = document.getElementById("terrCodeChkBoxChooser");
	document.getElementById("selCodeOld").value = myselect.options[myselect.selectedIndex].value;

	var newUrl = document.getElementById("displayBaseUrl").value;
    newUrl = newUrl + "\\" + document.getElementById("selCodeOld").value;
    newUrl = newUrl + "\\" + document.getElementById("filter_param").value;
	location.replace(newUrl);
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

document.addEventListener('DOMContentLoaded', function () {
	  enableResizableSave(
	    '.contentResizable',
	    '<?php echo base_url('index.php/Territory_controller/save_height'); ?>'
	  );
	});

function confirmDelete(areaCode, deleteUrl) {
	document.getElementById('confirmLine1').innerHTML =
	    'Olet poistamassa aluetta <span class="bold-area">' + areaCode + '</span>.';

    document.getElementById('confirmLine2').textContent =
        'Haluatko jatkaa?';

    document.getElementById('confirmYes').onclick = function() {
        window.location.href = deleteUrl;  // Varsinainen poistokutsu
    };

    document.getElementById('confirmModal').style.display = 'block';
}

function closeConfirm() {
    document.getElementById('confirmModal').style.display = 'none';
}

</script>
</html>
