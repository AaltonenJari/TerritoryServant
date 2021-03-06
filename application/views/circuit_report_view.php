<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Seuranta</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">

  <script src="<?php echo base_url("assets/javascript/coReportToPDF.js"); ?>"></script> 

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
    <?php $sivu_tunnus = "2"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>
    
 	<div id="content">

    <!-- Asetetaan sivun pääotsikko -->
    <div class="headerArea">
    <?php if ($is_cw_coming) { ?>
      <h1>Raportti kierrosvalvojalle alueista <?php echo $report_date; ?></h1>
    <?php } else { ?>
      <h1>TerritoryServant - alueiden käyntitilanne <?php echo $report_date; ?></h1>
    <?php } ?>
    </div>
 
    <?php $terr_url = base_url("index.php/territory_controller/display/lainassa/asc/0/1/0"); ?>

    <h2 class="coReportSubHdr">
    <?php if ($is_cw_coming) { ?>
 	      Kierrosvalvojan vierailuaika: 
	      <span id="circuit_week_start_date"> <?php echo $circuit_week_start; ?></span>
	      -
	      <span id="circuit_week_end_date"> <?php echo $circuit_week_end; ?></span>
     <?php } else { ?>
 	      Tilasto vuoden käymättä olleista alueista 
     <?php } ?>
	 </h2>
    
    <h2 class="coReportSubHdr">Seurakunnan alueet, <?php echo $this->session->userdata('congregationName') ?></h2>
 
	  <div class="coReportRow">
 	    Aluekortteja yhteensä:
	    <span id="territory_total_count_id"> <?php echo $territory_total_count; ?></span>
	    kpl.
	  </div>
	  <div class="tyhja_rivi"> 
   	  </div>
	  <?php
	    $borrowed_total = 0;
	    if (count($lainaukset) > 0) {
	  ?>
	  <div class="coReportRow">
	    Lainattu seurakuntiin: 
	  </div>
	  <?php
	          foreach ($lainaukset as $lainaus) {
 	  ?>
	            <div class="coReportRow2">
	  <?php
 	              foreach ($lainaus as $key=>$value) {
 	                  switch ($key) {
 	                      case "person_lastname":
 	                          echo "  " . $value . ": ";
 	                          break;
 	                          
 	                      case "territory_count":
 	                          echo $value . " kpl";
 	                          $borrowed_total = $borrowed_total + $value;
 	                          break;
 	                  }
 	              } ?>
   	            </div>
 	   <?php } ?>
 	     <div class="coReportRow">
 	     Lainassa yhteensä: <?php echo $borrowed_total; ?> aluekorttia.
 	     </div>
 	     <div class="tyhja_rivi"> 
   	     </div>
	  <?php } else { ?>
	    <div class="coReportRow">
  	      Alueita ei ole lainattu toisiin seurakuntiin.
        </div>
	  <?php } ?>
	  <div class="coReportRow">
	    Liikealueita:
	    <span id="liikealue_count_id"> <?php echo $liikealue_count; ?> kpl.</span>
      </div>
	  <div class="tyhja_rivi"> 
   	  </div>
      <div class="coReportRow">
	    Seurakunnalla on käytettävissä
	    <?php $actual_count = $territory_total_count - $borrowed_total - $liikealue_count; ?>
	    <span id="actual_count_id"> <?php echo $actual_count; ?> aluekorttia.</span>
      </div>
      <div class="coReportRow">
 	    Toisin sanoen
 	    <?php if ($borrowed_total > 0) { ?>
	      <span id="actual_count_id"> <?php echo $territory_total_count; ?> - <?php echo $borrowed_total; ?> - <?php echo $liikealue_count; ?> eli
	      <?php echo $actual_count; ?> aluekorttia.</span>
        <?php } else { ?>
	      <span id="actual_count_id"> <?php echo $territory_total_count; ?> - <?php echo $liikealue_count; ?> eli
          <?php echo $actual_count; ?> aluekorttia.</span>
    	<?php } ?>
	  </div>
      <div class="tyhja_rivi"> 
   	  </div>
      <div class="coReportRow">
  	    Alueita, joita ei ole käyty vuoden aikana:
	    <span id="vuosi_kaikki_id"> <?php echo $vuosi_kaikki; ?> kpl.</span>
      </div>
      <div class="coReportRow">
	    Näistä aluepöydässä
	    <span id="vuosi_laatikossa_id"> <?php echo $vuosi_laatikossa; ?> kpl, </span>
	    loput
	    <span id="vuosi_lainassa_id"> <?php echo $vuosi_lainassa; ?> kpl lainassa julistajilla.</span>
      </div>

      <h2 class="coReportSubHdr">Yhteenveto</h2>
   	  <div class="coReportRow">
    	  Alueita yhteensä <b><?php echo $actual_count; ?></b> kpl.
      </div>
   	  <div class="coReportRow">
    	  Vuoden sisällä käymättä: <b><?php echo $vuosi_kaikki; ?></b> kpl.
      </div>
   	  <div class="coReportRow">
    	  Vuoden sisällä käyty: <b><?php echo $covered_total; ?></b> kpl.
      </div>
   	  <div class="coReportRow">
    	  Prosentti: <b><?php echo $covered_percent; ?></b> %
      </div>
      <div class="tyhja_rivi"> 
   	  </div>
      <p class="coReportSubHdr">
 	    Alueita hoitavat veljet
	  </p>
 	</div><!-- content -->

    <div class="middleArea">
    </div>

    <div id="bottomArea" class="bottomArea">
      <table id="bottomtable">
        <tr>
          <td width="60%">
          </td>
          <td width="15%">
		    <div id="reportPrint">
  	  		  <input type="button" value="Näytä" class="btnAction" onclick='jsFunction3("<?php echo $terr_url; ?>")'" />
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
function jsFunction3(param) {
	var newUrl = param;
	  //alert(newUrl);
	  location.replace(newUrl);
}
</script>
</html>
