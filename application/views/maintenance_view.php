<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Alueet - seuranta ja merkitseminen</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory_update.css"); ?>">
  
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
    <?php $sivu_tunnus = "4"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>

    <!-- Asetetaan sivun pääotsikko -->
    <h1>Alueet - Ylläpito</h1>

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

	  		<?php $display_baseurl = base_url("index.php/maintenance_controller/display") . "/" . $sort_by . "/" . $sort_order; ?>
      		<input type="hidden" id="displayBaseUrl" value="<?php echo $display_baseurl; ?>" />

	        <input type="hidden" id="filter_param" value="<?php echo $filter; ?>"/>
	        <input type="hidden" id="base_update_url" value="<?php echo base_url("index.php/Maintenance_controller/delete"); ?>" />
          </td>
		  <td>
		    <input type="hidden" id="selCodeOld" value="<?php echo $code_sel; ?>" />
      		<select name="terrCodeChkBoxChooser" id="terrCodeChkBoxChooser" onChange="jsFunction4()">
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
            <a href="<?php echo base_url("index.php/maintenance_controller/display"); ?>" target="_parent" class="btn-clear"><button>CLR</button></a>
          </td>
  		</tr>
      </table>
    </div>

    <div id="content">
      <?php $form_open_parameter = "maintenance_controller/check_update/" . $code_sel; ?>
      <?php echo form_open($form_open_parameter); ?>
        <div class="tableWrap">
          <table id="table2" class="order-table table">
            <thead>
              <tr>
                  <?php foreach ($display_fields as $field_name => $field_display) { ?>
                      <th <?php if ($sort_by == $field_name) echo "class=\"sort_" . $sort_order . "\"" ?>><span>
                          <?php $hdrurl = base_url("index.php/maintenance_controller/display") . "/" . $field_name . "/" .
                              (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'); ?>
                          <?php $field_name_old = $field_display ."old" ?>
                          <input type="hidden" id="<?php echo $field_name_old; ?>" value="<?php echo $hdrurl; ?>" />
                          <?php if (!empty($code_sel)) { $hdrurl .= "/" . $code_sel; } ?>
                          <?php if (!empty($filter)) { $hdrurl .= "/" . $filter; } ?>
                          <a id="<?php echo $field_display; ?>"
                             href="<?php echo $hdrurl; ?>"><?php echo $field_display; ?></a>
                      </span></th>
                  <?php } ?>
                  <th>Poisto</th>
              </tr>
            </thead>
            <tbody>
              <?php $rowidx = 0; ?>
              <?php foreach ($alueet as $alue) { ?>
                  <tr>
                      <?php $rowidx++; ?>
                       <?php foreach ($display_fields as $field_name => $field_display) {
                           $field_id_data = $field_name . $rowidx;
                           $field_name_data = $field_name . "[]";
                           $field_name_old_data = $field_name . "_old" . "[]";
                           
                           $field_input_name_data = "input_". $field_name . $rowidx;
                           $field_input_name_old_data = "input_old_". $field_name . $rowidx;
                           $field_name_old = $field_name . "_old";
                           
                           if ($field_name == "alue_code") { ?>
                             <td id="<?php echo $field_id_data; ?>"> 
                              <?php 
                              echo form_label($alue->$field_name);
                              $data_hidden = [
                                     'type'  => 'hidden',
                                     'id'    => $field_input_name_old_data,
                                     'name'  => $field_name_old_data,
                                     'value' =>  $alue->$field_name,
                                     'class' => $field_name_old
                                 ];
                              echo form_input($data_hidden);
                              ?> 
                            </td>
                          <?php } else { ?>
                            <td id="<?php echo $field_id_data; ?>"> 
                              <?php 
                              $data = [
                                  'type'  => 'text',
                                  'id'    => $field_input_name_data,
                                  'name'  => $field_name_data,
                                  'value' =>  $alue->$field_name,
                                  'class' => $field_name
                              ];
                              echo form_input($data);

                              $data_hidden = [
                                  'type'  => 'hidden',
                                  'id'    => $field_input_name_old_data,
                                  'name'  => $field_name_old_data,
                                  'value' =>  $alue->$field_name,
                                  'class' => $field_name_old
                              ];
                              echo form_input($data_hidden);
                              
                                 ?> 
                            </td>
                          <?php } ?>
                      <?php } ?>
                    <td>
                      <?php $terr_url = base_url("index.php/maintenance_controller/delete") . "/" . $alue->alue_code . "/" . $filter; ?>
    			      <a href="<?php echo $terr_url; ?>" onClick='jsFunction3("<?php echo $alue->alue_code; ?>")'>Poista</a>
                    </td>
                  </tr>
               <?php } ?>
            </tbody>
          </table>
      </div><!-- tableWrap -->
      <table id="cardbuttons">
        <tr>
          <td width="40%">
          <?php echo form_submit(array('id' => 'submit_update', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Päivitä')); ?>
          </td>
          <td width="30%">
          <?php echo form_submit(array('id' => 'submit_add', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Lisää')); ?>
          </td>
          <td width="15%">
          <?php if ($can_undo) { 
                   echo form_submit(array('id' => 'submit_undo', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Undo')); 
                } else {
                   echo form_submit(array('id' => 'submit_undo_disabled', 'class'=> 'submit_btn_disabled', 'name' => 'action', 'value' => 'Undo', 'disabled'  => 'true'));
                } ?>
          </td>
          <td width="15%">
          <?php if ($can_redo) { 
                   echo form_submit(array('id' => 'submit_redo', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Redo'));
                } else {
                   echo form_submit(array('id' => 'submit_redo_disabled', 'class'=> 'submit_btn_disabled', 'name' => 'action', 'value' => 'Redo', 'disabled'  => 'true'));
                } ?>
          </td>
        </tr>
        <tr>
          <td colspan="3">
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
              <span>Löytyi: <span id="tableRowCount"> <?php echo $num_results; ?></span> aluetta</span>
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

  document.getElementById("numero").href = document.getElementById("numeroold").value +
 	    "\\" + document.getElementById("selCodeOld").value +
 	    "\\" + document.getElementById("filter_param").value;

  document.getElementById("alue_nimi").href = document.getElementById("alue_nimiold").value +
        "\\" + document.getElementById("selCodeOld").value +
        "\\" + document.getElementById("filter_param").value;

	document.getElementById("lisätieto").href = document.getElementById("lisätietoold").value +
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

function jsFunction3(alue_code) {
    var newUrl = document.getElementById("base_update_url").value;
	var newUrl = newUrl + "/" + alue_code + "/" + document.getElementById("filter_param").value;
	document.getElementById(alue_code).href = newUrl;
}

function jsFunction4() {
	var myselect = document.getElementById("terrCodeChkBoxChooser");
	document.getElementById("selCodeOld").value = myselect.options[myselect.selectedIndex].value;
    var newUrl = document.getElementById("displayBaseUrl").value;
    newUrl = newUrl + "\\" + document.getElementById("selCodeOld").value;
    newUrl = newUrl + "\\" + document.getElementById("filter_param").value;
	//alert(newUrl);
	location.replace(newUrl);
}
</script>
</html>
