<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Alueet - seuranta ja merkitseminen</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">
  
  <script src="<?php echo base_url("assets/javascript/markExhortToPDF.js"); ?>"></script> 
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
 
    <!-- Asetetaan sivun pääotsikko -->
    <?php 
    if ($exhort == "RETURN") {
        $hdr_string = "Alueet - palautuskehotukset";
        $explainer = "Alue saa olla lainassa samalla julistajalla korkeintaan vuoden.";
        $terr_url = base_url("index.php/territory_controller/display/event_last_date/asc/2/4/0");
        
    } else {
        $hdr_string = "Alueet - merkitsemiskehotukset";
        $explainer = "Alue tulisi käydä läpi neljässä kuukaudessa. Sitten siitä tulisi ilmoittaa aluepöytään, jotta alue voidaan merkitä käydyksi (km 4/07 s. 8, od s. 98).";
        $terr_url = base_url("index.php/territory_controller/display/name/asc/2/5/0");
    }
    ?>
    <div id="listhdr">
      <h1><?php echo $hdr_string; ?></h1>
    </div>

	<div id="content">
	  <div class="tableWrap">
    	<?php $rowidx = 0;
    	foreach ($terr_mark_list as $publisher) { ?>
    	  <table class="table4">
    		<thead>
    		</thead>
    		<tbody>
    		  <?php 
   		      foreach ($publisher as $key=>$value) {
   		        switch ($key) {
   		            case "name": ?>
   		                <tr>
   		                  <td class="julistaja" colspan="3"> 
   		                    <?php echo $value; ?>
   		                  </td>
   		                  <td class="otsikko_kayty"> <?php echo date("j.n.Y"); ?></td>
   		                </tr>
   		               	<tr>
   		                  <td class="ohjeteksti" colspan="4"> 
   		                  <?php 
   		                  if ($exhort == "RETURN") {
   		                      if (count($publisher['territories']) == 1) {
   		                          echo "palauta seuraava alue aluepöytään:";
   		                      } else {
   		                          echo "palauta seuraavat alueet aluepöytään:";
   		                      }
   		                  } else {
   		                      if (count($publisher['territories']) == 1) {
   		                          echo "merkitsehän seuraava alue aluepöydässä:";
   		                      } else {
   		                          echo "merkitsehän seuraavat alueet aluepöydässä:";
   		                      }
   		                  }
    		                  ?>
   		                  </td>
   		                </tr>
   		               	<tr>
   		                  <td class="tyhja_rivi" colspan="4"> 
   		                  </td>
   		                </tr>
   		                <?php $rowidx++;
   		                break;
   		            
   		            case "territories":
   		                $territories = $value; ?>
    		              <tr>
    		                <td class="otsikko_numero">Numero</td>
    		                <td class="otsikko_nimi">Nimi</td>
    		                <?php if ($exhort == "RETURN") { ?>
     		                  <td class="otsikko_otettu">Lainattu</td>
    		                <?php } else { ?>
    		                  <td class="otsikko_otettu">Otettu</td>
    		                  <td class="otsikko_kayty">Käyty viimeksi</td>
    		                <?php } ?>
    		              </tr>
   		                  <?php foreach ($territories as $territory) { ?>
   		                     <tr>
   		                      <?php foreach ($territory as $key1=>$value1) {
   		                        switch ($key1) {
   		                            case "alue_number": 
   		                            case "alue_name":
   		                            case "event_last_date":
   		                            case "alue_lastdate": ?>
   		                              <td class="<?php echo $key1; ?>">
   		                                <?php echo $value1;  ?>
   		                              </td>
   		                                <?php break;
   		                                
   		                            default:
   		                                break;
   		                        } // switch
   		                      }  //territory ?>
   		                     </tr>
   		                  <?php }  //territories
   		                break;
   		                
   		            default:
   		                break;
   		        } // switch
   		      } 
   		      if ($rowidx > 0) { //Taulukkoon lopuksi selite ?>
   		        <tr>
   		          <td class="kehotus_selite" colspan="4">
   		            <?php echo $explainer;?>
   		          </td>
   		        </tr>
              <?php } ?>
    		</tbody>
          </table>
    	<?php }?>
 	  </div><!-- tableMarkTerr -->
	</div><!-- content -->

    <div class="middleArea">
    </div>

    <div id="bottomArea" class="bottomArea">
      <table id="bottomtable">
        <tr>
          <td width="60%">
	 	    <div id="totalcount">
  		      <span>Löytyi: <span id="tableRowCount"> <?php echo $num_results; ?></span> aluetta</span>
    	    </div>
          </td>
          <td width="15%">
		    <div id="reportShow">
  	  		  <input type="button" value="Näytä" class="btnAction" onclick="jsFunction3('<?php echo $terr_url; ?>')" />
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
