<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Alueet - seuranta ja merkitseminen</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">
  
  <script src="<?php echo base_url("assets/javascript/markExhortToPDF.js"); ?>"></script> 
</head>

<body>

  <div id="wrapper">
    <!-- Asetetaan navigointipalkki ja tämä sivu aktiiviseksi -->
    <?php $sivu_tunnus = $this->session->userdata('sivutunnus');?>
    <?php $this->load->view('common/navbar.php')?>

    <!-- Asetetaan sivun pääotsikko -->
    <h1>Alueet - merkitsemiskehotukset</h1>

	<div id="content">
	  <div class="tableWrap">
    	<?php $idx = 0;
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
   		                  <?php if (count($publisher['territories']) == 1) { 
   		                      echo "merkitsehän seuraava alue aluepöydässä:";
   		                  } else { 
   		                      echo "merkitsehän seuraavat alueet aluepöydässä:";
   		                  } ?>
   		                  </td>
   		                </tr>
   		               	<tr>
   		                  <td class="tyhja_rivi" colspan="4"> 
   		                  </td>
   		                </tr>
   		                <?php $idx++;
   		                break;
   		            
   		            case "territories":
   		                $territories = $value; ?>
    		              <tr>
    		                <td class="otsikko_numero">Numero</td>
    		                <td class="otsikko_nimi">Nimi</td>
    		                <td class="otsikko_otettu">Otettu</td>
    		                <td class="otsikko_kayty">Käyty viimeksi</td>
    		              </tr>
    		              <tr>
   		                    <?php foreach ($territories as $territory) {  
   		                      foreach ($territory as $key1=>$value1) {
   		                        switch ($key1) {
   		                            case "alue_number": 
   		                            case "alue_name":
   		                            case "event_date":
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
   		      if ($idx > 0) { //Taulukkoon lopuksi selite ?>
   		        <tr>
   		          <td class="kehotus_selite" colspan="4">
   		            Alue tulisi käydä läpi neljässä kuukaudessa. Sitten siitä tulisi ilmoittaa aluepöytään, jotta alue voidaan merkitä käydyksi (km 4/07 s. 8, od s. 98).
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
          <td width="85%">
	 	    <div id="totalcount">
  		      <span>Löytyi: <span id="tableRowCount"> <?php echo $num_results; ?></span> aluetta</span>
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


  </div><!-- wrapper -->
</body>
</html>
