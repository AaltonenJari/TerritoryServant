<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Alueet - seuranta ja merkitseminen</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/events.css"); ?>">

  <!--link jquery ui css-->
  <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.css'); ?>" />

  <!--load jquery-->
  <script src="<?php echo base_url('assets/javascript/jquery-1.10.2.js'); ?>"></script>
  <!--load jquery ui js file-->
  <script src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.js'); ?>"></script>
  
  <script src="<?php echo base_url("assets/javascript/territorysearch.js"); ?>"></script> 
  <script src="<?php echo base_url("assets/javascript/eventsToPDF.js"); ?>"></script> 
</head>

<body>

  <div id="wrapper">
    <!-- Asetetaan navigointipalkki ja tämä sivu aktiiviseksi -->
    <?php $sivu_tunnus = "3"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>
    
    <!-- Asetetaan sivun pääotsikko -->
    <h1>Alueet - Historia</h1>

    <div id="selector_area">

      <span>Valitse sivu:</span>
      <?php $display_baseurl = base_url("index.php/event_controller/display"); ?>
      <input type="hidden" id="displayBaseUrl" value="<?php echo $display_baseurl; ?>" />

      <input type="hidden" id="selCodeOld" value="<?php echo $code_sel; ?>" />
  	  <select name="terrCodeChkBoxChooser" id="terrCodeChkBoxChooser" onChange="jsFunction4()">
        
      <?php
      foreach ($sel_data as $terrgroup_selecion) {
        $sel_string = "";
        foreach ($terrgroup_selecion as $key => $value) {
            switch ($key) {
                case "code":
                    $sel_string = $value;
                    break;
                    
                case "first":
                    $sel_string .= $value;
                    break;
                    
                case "offset":
                    $sel_offset = $value;
                    break;
                
                case "last":
                    $sel_string .= "-" . $value;
                    break;
            }
        }?>
  		<option value="<?php echo $sel_string; ?>" page_offset="<?php echo $sel_offset; ?>" <?php if ($code_sel == $sel_string) { echo "selected='selected'"; } ?> ><?php echo $sel_string; ?></option>
        <?php 
      }
      ?>
      </select>
    </div>
         
    <div id="content">
      <div class="tableWrap">
        <table class="table3">
          <thead class="table3Header">
            <tr>
              <th class="table3Hdr" colspan="20">Aluekorttiluettelo</th>
            </tr>
            <tr>
              <?php foreach ($event_headers as $event_hdr) { ?>
                <?php foreach ($display_fields as $field_name => $field_display) { ?>
                  <th class="table3HdrRow" width="5%">
                    <div class="hdrtext">
                      Alue<br>nro
                    </div>
                  </th>
                  <th class="table3HdrRow" width="5%">
                    <div class="hdrnbr">
                      <?php echo $event_hdr->$field_name; ?>
                    </div>
                  </th>
                  <th class="table3HdrRow" width="5%"></th>
                  <th class="table3HdrRow" width="5%"></th>
                <?php } ?> <!-- foreach $display_fields  -->
              <?php } ?> <!-- foreach $event_headers  -->
              <?php if (count($event_headers) < 5) { ?>
                <?php for ($i=count($event_headers);$i < 5; $i++) {?>
                  <th class="table3HdrRow" width="5%"> 
                    <div class="hdrtext">
                      Alue<br>nro
                    </div>
                  </th>
                  <th class="table3HdrRow" width="5%"></th>
                  <th class="table3HdrRow" width="5%"></th>
                  <th class="table3HdrRow" width="5%"></th>
                <?php } ?> <!-- foreach empty header columns  -->
              <?php } ?> <!-- if empty header columns  exists -->
            </tr>
          </thead>

          <tbody class="table3body">
            <?php $rowidx = 0; ?>
            <?php foreach ($event_data as $row_key => $row_value) { ?>
              <?php foreach ($row_value as $row_item_key => $row_item_value) { ?>
                <tr>
                  <?php if ($row_item_key == "names") { ?>
                    <?php foreach ($row_item_value as $name_key => $name_value) { ?>
                      <td class="event_nimi" colspan="4"><?php echo $name_value; ?></td>
                    <?php } ?>
                    <?php if (count($event_headers) < 5) { ?>
                      <?php for ($i=count($event_headers);$i < 5; $i++) {?>
                        <td class="event_nimi" colspan="4"></td>
                      <?php } ?>
                     <?php } ?>
                  <?php } else if ($row_item_key == "dates") { ?>
                    <?php $colidx = 0; ?>
                    <?php foreach ($row_item_value as $date_key => $date_value) {
                      if ($colidx % 2 == 0) { ?>
                        <td class="event_lainattu" colspan="2"><?php echo $date_value; ?></td>
                      <?php } else { ?>
                        <td class="event_palautettu" colspan="2"><?php echo $date_value; ?></td>
                      <?php }
                        $colidx++;
                      ?>
                    <?php } ?>
                    <?php if (count($event_headers) < 5) { ?>
                      <?php for ($i=count($event_headers);$i < 5; $i++) {?>
                        <td class="event_lainattu" colspan="2"></td>
                        <td class="event_palautettu" colspan="2"></td>
                      <?php } ?>
                     <?php } ?>
                   <?php } ?>
                </tr>
              <?php } 
                $rowidx++; ?>
            <?php }
            for ($j=$rowidx;$j < 26; $j++) { ?>
            <tr class="tyhja_rivi" >
              <?php for ($i=0;$i < 5; $i++) {?>
                <td class="event_nimi" colspan="4"></td>
              <?php } ?>
            </tr>
            <tr class="tyhja_rivi" >
              <?php for ($i=0;$i < 5; $i++) {?>
                <td class="event_lainattu" colspan="2"></td>
                <td class="event_palautettu" colspan="2"></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
        </table> <!-- table3 -->
      </div> <!-- tableWrapEvent -->
    </div><!-- content -->
    <div class="middleArea">
    </div>
    <div id="bottomArea" class="bottomArea">
      <table id="bottomtable">
        <tr>
          <td width="43%">
            <div id="totalcount">
              <?php if ($num_headers == 1) {?>
                <span>Ryhmässä <?php echo substr($code_sel,0,1); ?> yhteensä: <span id="tableRowCount"> <?php echo $num_headers; ?></span> alue</span>
              <?php } else { ?>
                <span>Ryhmässä <?php echo substr($code_sel,0,1); ?> yhteensä: <span id="tableRowCount"> <?php echo $num_headers; ?></span> aluetta</span>
              <?php } ?>
            </div>
          </td>
          <td width="42%">
            <div id="sivutusotsake">
              <span>Valitse ryhmä ja sivu</span>
            </div>
          </td>
          <td width="15%">
            <div id="reportPrint">
              <input type="button" value="Raportti" id="btPrint" onclick="createPDF()" />
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div class="middleArea">
    </div>
    <div class="paginationArea">
      <?php print_r ($terr_codes) ?>
    
      <!-- Show pagination links -->
      <?php if (isset($links)) { ?>
          <?php echo $links ?>
      <?php } ?>
    </div>
  </div><!-- wrapper -->
</body>

<script>
function jsFunction4() {
	var myselect = document.getElementById("terrCodeChkBoxChooser");
	document.getElementById("selCodeOld").value = myselect.options[myselect.selectedIndex].value;

    var selString = document.getElementById("selCodeOld").value;
    var matchStrings = selString.match(/([A-Z])(\d+)-(\d+)/);
    var code = matchStrings[1];
    var offset = myselect.options[myselect.selectedIndex].getAttribute('page_offset');
    
    var newUrl = document.getElementById("displayBaseUrl").value;
    newUrl = newUrl + "\\" + code;
    newUrl = newUrl + "\\" + offset;
    
    //alert (newUrl);
    
	location.replace(newUrl);
}
</script>

</html>



