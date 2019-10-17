<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Alueet - seuranta ja merkitseminen</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/terr_maintenance.css"); ?>">
  
  <script src="<?php echo base_url("assets/javascript/terrUpdSearch.js"); ?>"></script> 
  <script src="<?php echo base_url("assets/javascript/territoriesToPDF.js"); ?>"></script> 
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
          <th width="20%">Etsi / Rajaa</th>
          <th width="60%"></th>
          <th width="20%">Alkutilaan</th>
        </tr>
  		<tr>
          <td>
 		    <input type="search" id="filterString" class="light-table-filter" data-table="order-table" placeholder="Filter">

	  		<?php $display_baseurl = base_url("index.php/maintenance_controller/maintain") . "/" . $sort_by . "/" . $sort_order; ?>
      		<input type="hidden" id="displayBaseUrl" value="<?php echo $display_baseurl; ?>" />

	        <input type="hidden" id="filter_param" value="<?php echo $filter; ?>"/>
          </td>
          <td>
            <a href="<?php echo base_url("index.php/maintenance_controller/insert"); ?>" target="_parent" class="btn-add"><button>Lisää uusi</button></a>
          </td>
          <td>
            <a href="<?php echo base_url("index.php/maintenance_controller/maintain"); ?>" target="_parent" class="btn-clear"><button>CLR</button></a>
          </td>
  		</tr>
      </table>
    </div>

	<div id="content">
	  <div class="tableWrap">
        <table id="table2" class="order-table table">
          <thead>
            <tr>
    			<?php foreach ($display_fields as $field_name => $field_display) { ?>
    			    <th <?php if ($sort_by == $field_name) echo "class=\"sort_" . $sort_order . "\"" ?>><span>
						<?php $hdrurl = base_url("index.php/maintenance_controller/maintain") . "/" . $field_name . "/" .
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
		    <?php $idx = 0; ?>
       		<?php foreach ($alueet as $alue) { ?>
               	<tr>
               		<?php $idx++; ?>
	    			<?php foreach ($display_fields as $field_name => $field_display) { ?>
	    				<?php $field_name_data = $field_display . $idx ?>
   			    	    <?php $field_input_name_data = $field_display . "input". $idx ?>
 	    				<?php if ($field_name == "alue_code") { ?>
    			    	  <td id="<?php echo $field_name_data; ?>"> 
    			    	      	<a id="<?php echo $alue->$field_name; ?>" href="<?php echo base_url("index.php/maintenance_controller/update") . "/"  . $alue->$field_name; ?>">
    			    	    <?php echo $alue->$field_name; ?> 
   				    	   </a>
    			    	  
    			    	  </td>
 	    				<?php } else { ?>
    			    	  <td id="<?php echo $field_name_data; ?>"> <?php echo $alue->$field_name; ?> </td>
    			    	<?php } ?>
   					<?php } ?>
       			</tr>
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
          <td width="90%">
	 	    <div id="totalcount">
  		      <span>Löytyi: <span id="tableRowCount"> <?php echo $num_results; ?></span> aluetta</span>
    	    </div>
          </td>
          <td width="10%">
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
