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
        
	<div id="content">
	  <div class="tableWrapEvent">
        <table id="table3">
          <thead>
		    <tr>
              <?php foreach ($event_headers as $event_hdr) { ?>
	    		<?php foreach ($display_fields as $field_name => $field_display) { ?>
	    	      <th width="5%">
	    	        <div class="hdrtext">
	    	          Alue<br>nro
	    	        </div>
	    	      </th>
	    	      <th width="5%">
	    	        <div class="hdrnbr">
  			    	  <?php echo $event_hdr->$field_name; ?>
	    	        </div>
	    	      </th>
	    	      <th width="5%"> 
	    	      </th>
	    	      <th width="5%"> 
	    	      </th>
    		    <?php } ?>
    		  <?php } ?>
    		  <?php if (count($event_headers) < 5) { ?>
    		    <?php for ($i=count($event_headers);$i < 5; $i++) {?>
	    	      <th width="5%"> 
	    	        <div class="hdrtext">
  	    	          Alue<br>nro
	    	        </div>
	    	      </th>
    		      <th width="5%"> 
	    	      </th>
	    	      <th width="5%"> 
	    	      </th>
	    	      <th width="5%"> 
	    	      </th>
      		    <?php } ?>
    		  <?php } ?>
 			</tr>
          </thead>
          <tbody>
		    <?php $idx = 0; ?>
       		<?php foreach ($event_data as $row_key => $row_value) { ?>
        	  <?php foreach ($row_value as $row_item_key => $row_item_value) { ?>
              	<tr>
              	  <?php if ($row_item_key == "names") { ?>
              	    <?php foreach ($row_item_value as $name_key => $name_value) { ?>
  			    	  <td colspan="4"><?php echo $name_value; ?>
   			    	  </td>
                    <?php } ?>
                    <?php if (count($event_headers) < 5) { ?>
    		          <?php for ($i=count($event_headers);$i < 5; $i++) {?>
    		            <td colspan="4"> 
	    	            </td>
      		          <?php } ?>
           		     <?php } ?>
                  <?php } else if ($row_item_key == "dates") { ?>
              	    <?php foreach ($row_item_value as $date_key => $date_value) { ?>
  			    	  <td colspan="2"><?php echo $date_value; ?>
   			    	  </td>
                    <?php } ?>
                    <?php if (count($event_headers) < 5) { ?>
      		          <?php for ($i=count($event_headers);$i < 5; $i++) {?>
    		            <td colspan="2"></td>
    		            <td colspan="2"></td>
      		          <?php } ?>
           		     <?php } ?>
                   <?php } ?>
       			</tr>
              <?php } ?>
   			<?php } ?>
	      </tbody>
        </table>
      </div>
	</div><!-- content -->
    <div class="middleArea">
    </div>
    <div id="bottomArea" class="bottomArea">
      <table id="bottomtable">
        <tr>
          <td width="43%">
	 	    <div id="totalcount">
  		      <span>Löytyi: <span id="tableRowCount"> <?php echo $num_headers; ?></span> aluetta</span>
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
</html>
