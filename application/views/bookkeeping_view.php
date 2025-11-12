<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Tapahtumat</title>
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
  <script src="<?php echo base_url('assets/javascript/resizableHeight.js'); ?>"></script>
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
    
    <div class="headerArea">
      <!-- Asetetaan sivun pääotsikko -->
      <h1>TerritoryServant - Alueiden kirjanpito</h1>
    </div>
        
    <div id="selector_area">
      <div class="selector-container right-only">
        <div class="selector-right">
          <span>Alkutilaan </span>
          <a href="<?php echo base_url("index.php/event_controller/display"); ?>" target="_parent" class="btn-clear">
            <button>CLR</button>
          </a>
        </div>
      </div>
    </div>
  
    <div id="content" class="contentResizable" style="<?php echo $saved_height ? 'height:'.$saved_height.'px;' : ''; ?>">
      <div class="scrollInner">
        <?php foreach ($bookkeeping as $bookkeeping_row) { ?>
        <table class="table3">
          <?php 
          foreach ($bookkeeping_row as $key => $value) {
          switch ($key) {
            case "event_headers":
              $event_headers = $value; ?>
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
              <?php    
              break;
            case "event_data":
              $event_data = $value; ?>
              <tbody class="table3body">
                <?php $rowidx = 0; ?>
                <?php foreach ($event_data as $row_key => $row_value) { ?>
                  <?php foreach ($row_value as $row_item_key => $row_item_value) { ?>
                    <tr>
                      <?php if ($row_item_key == "names") { ?>
                        <?php foreach ($row_item_value as $name_key => $name_value) { ?>
                          <td class="event_nimi<?php echo ($name_value == 'Alue poistettu') ? ' deleted' : ''; ?>" colspan="4">
                              <?php echo $name_value; ?>
                          </td>
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
                for ($j=$rowidx;$j < 24; $j++) { ?>
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
              <?php    
              break;
            } // switch
        } ?>  <!-- foreach $bookkeeping_row  -->
        </table> <!-- table3 -->
        <?php } ?> <!-- foreach $bookkeeping  -->
      </div> <!-- scrollInner -->
    </div><!-- content -->
    
    <div class="middleArea">
    </div>
    <div id="bottomArea" class="bottomArea">
      <table id="bottomtable">
        <tr>
          <td width="43%">
            <div id="totalcount">
            </div>
          </td>
          <td width="42%">
            <div id="sivutusotsake">
            </div>
          </td>
          <td width="15%">
            <div id="reportPrint">
              <input type="button" value="Raportti" class="btnAction" onclick="bookKeepingToPDF()" />
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div><!-- wrapper -->
</body>

<script>

document.addEventListener('DOMContentLoaded', function () {
	  enableResizableSave(
	    '.contentResizable',
	    '<?php echo base_url('index.php/Territory_controller/save_height'); ?>'
	  );
	});

</script>


</html>
