<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Alueet - seuranta ja merkitseminen</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory_update.css"); ?>">
  
  <script src="<?php echo base_url("assets/javascript/territorysearch_update.js"); ?>"></script> 
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
    <?php $sivu_tunnus = $this->session->userdata('sivutunnus');?>
    <?php $this->load->view('common/navbar.php')?>

    <!-- Asetetaan sivun pääotsikko -->
    <?php if ($sivu_tunnus == "1") { ?>
      <h1>Tervetuloa käyttämään alueohjelmaa!</h1>
      <h2>Suosittele seuraavia alueita.</h2>
    <?php } else { ?>
      <h1>Alueet - seuranta ja merkitseminen</h1>
    <?php } ?>

    <div id="filterArea" class="filterArea">
      <table id="selectortable">
        <tr>
          <th width="50%">Etsi / Rajaa</th>
          <th width="10%">Rajaa lainassa</th>
          <th width="25%">Rajaa käyty pvm</th>
          <th width="15%">Perustilaan</th>
        </tr>
        <tr>
          <td>
            <input type="search" id="filterString" class="light-table-filter" data-table="order-table" placeholder="Filter">

            <?php $display_baseurl = base_url("index.php/territory_controller/display") . "/" . $sort_by . "/" . $sort_order; ?>
            <input type="hidden" id="displayBaseUrl" value="<?php echo $display_baseurl; ?>" />

            <input type="hidden" id="filter_param" value="<?php echo $filter; ?>"/>
          </td>
          <td>
            <input type="hidden" id="selChkBoxOld" value="<?php echo $chkbox_sel; ?>" />
            <select name="borrowChkBoxChooser" id="borrowChkBoxChooser" onChange="jsFunction()">
              <option value="0" <?php if ($chkbox_sel == "0") echo "selected=\"selected\""?> >Kaikki</option>
              <option value="1" <?php if ($chkbox_sel == "1") echo "selected=\"selected\""?> >Seurakunnassa</option>
              <option value="2" <?php if ($chkbox_sel == "2") echo "selected=\"selected\""?> >Lainassa</option>
            </select> 
          </td>
          <td>
            <input type="hidden" id="selDateOld" value="<?php echo $date_sel; ?>" />
            <select name="borrowDateChooser" id="borrowDateChooser" onChange="jsFunction2()">
              <option value="0" <?php if ($date_sel == "0") echo "selected=\"selected\""?> >Kaikki</option>
              <option value="1" <?php if ($date_sel == "1") echo "selected=\"selected\""?> >Käyty &gt; 12 kk</option>
              <option value="2" <?php if ($date_sel == "2") echo "selected=\"selected\""?> >Käyty &gt; 4 kk</option>
              <option value="3" <?php if ($date_sel == "3") echo "selected=\"selected\""?> >Käyty &gt; 6 kk</option>
            </select> 
          </td>
          <td>
            <a href="<?php echo base_url("index.php/territory_controller/display"); ?>" target="_parent" class="btn-clear"><button>CLR</button></a>
          </td>
        </tr>
      </table>
    </div>

    <div id="content">
      <?php echo form_open('territory_controller/update_territories'); ?>
        <div class="tableWrap">
          <table id="table2" class="order-table table">
            <thead>
              <tr>
                  <?php foreach ($display_fields as $field_name => $field_display) { ?>
                      <th <?php if ($sort_by == $field_name) echo "class=\"sort_" . $sort_order . "\"" ?>><span>
                          <?php $hdrurl = base_url("index.php/territory_controller/display") . "/" . $field_name . "/" .
                              (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc'); ?>
                          <a id="<?php echo $field_display; ?>"
                             href="<?php echo $hdrurl; ?>"><?php echo $field_display; ?></a>
                             <?php $field_name_old = $field_display ."old" ?>
                          <input type="hidden" id="<?php echo $field_name_old; ?>" value="<?php echo $hdrurl; ?>" />
                      </span></th>
                  <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php $rowidx = 0; ?>
              <?php foreach ($alueet as $alue) { ?>
                  <tr>
                      <?php $rowidx++; ?>
                       <?php foreach ($display_fields as $field_name => $field_display) { ?>
                          <?php $field_id_data = $field_name . $rowidx ?>
                          <?php $field_name_data = $field_name . "[]" ?>
                          <?php $field_input_name_data = "input_". $field_name . $rowidx ?>
                          <?php if ($field_name == "lainassa") { ?>
                            <td id="<?php echo $field_id_data; ?>"> 
                              <?php 
                                echo form_input(array('id' => $field_input_name_data,
                                      'name' => $field_name_data, 
                                      'class'=> $field_name,
                                      'value' => set_value($field_name_data, $alue->$field_name))
                                );
                              ?> 
                            </td>
                          <?php } else if ($field_name == "alue_code") { ?>
                            <td id="<?php echo $field_id_data; ?>"> 
                              <?php 
                                 echo form_input(array('id' => $field_input_name_data,
                                    'name' => $field_name_data,
                                    'class'=> $field_name,
                                    'value' => set_value($field_name_data, $alue->$field_name))
                                );
                              ?> 
                            </td>
                          <?php } else { 
                              ?>
                            <td id="<?php echo $field_id_data; ?>"> 
                              <?php 
                                  echo form_input(array('id' => $field_input_name_data,
                                    'name' => $field_name_data,
                                    'class'=> $field_name,
                                    'value' => set_value($field_name_data, $alue->$field_name))
                                    );
                                ?> 
                            </td>
                          <?php } ?>
                      <?php } ?>
                  </tr>
               <?php } ?>
            </tbody>
          </table>
      </div><!-- tableWrap -->
        <?php 
          echo form_submit(array('id'=>'submit','value'=>'Edit')); 
          echo form_close(); 
        ?>
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
$.each($('.alue_detail'), function (index, valor) {
	var value_old = valor.value;
	$(this).change(function () {
		var value_new = $(this).val();
		var name_id = "input_name" + (index+1);
		var pname = document.getElementById(name_id).value;
      alert("Muutos:" + index + " [" + value_old + "]"  + " -> [" + value_new + "] " + name_id + pname);
		document.getElementById(name_id).value = "";
    });
});

function jsFunction() {
      var myselect = document.getElementById("borrowChkBoxChooser");
      document.getElementById("selChkBoxOld").value = myselect.options[myselect.selectedIndex].value;
      var newUrl = document.getElementById("displayBaseUrl").value;
      newUrl = newUrl + "\\" + document.getElementById("selChkBoxOld").value;
      newUrl = newUrl + "\\" + document.getElementById("selDateOld").value;
      newUrl = newUrl + "\\" + document.getElementById("filter_param").value;
      //alert(newUrl);
      location.replace(newUrl);
    }
    
function jsFunction2() {
      var myselect = document.getElementById("borrowDateChooser");
      document.getElementById("selDateOld").value = myselect.options[myselect.selectedIndex].value;
      var newUrl = document.getElementById("displayBaseUrl").value;
      newUrl = newUrl + "\\" + document.getElementById("selChkBoxOld").value;
      newUrl = newUrl + "\\" + document.getElementById("selDateOld").value;
      newUrl = newUrl + "\\" + document.getElementById("filter_param").value;
      //alert(newUrl);
      location.replace(newUrl);
    }

function jsFunction3(param) {
    var newUrl = param + "\\" + document.getElementById("filter_param").value;
      //alert(newUrl);
      location.replace(newUrl);
}
</script>
</html>
