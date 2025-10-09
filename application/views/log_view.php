<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Tapahtumaloki</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/logview.css"); ?>">
  
  <script src="<?php echo base_url("assets/javascript/logsearch.js"); ?>"></script> 
  <script src="<?php echo base_url("assets/javascript/logToPDF.js"); ?>"></script> 
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
    <?php $sivu_tunnus = "3"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>

    <div class="headerArea">
      <!-- Asetetaan sivun pääotsikko -->
      <h1>TerritoryServant - Tapahtumaloki</h1>
    </div>

    <div id="filterArea" class="filterArea">
      <table id="selectortable">
        <tr>
          <th width="15%">Etsi / Rajaa</th>
          <th width="35%">Käyttäjä</th>
          <th width="10%"></th>
          <th width="25%">Loki pvm</th>
          <th width="15%">Perustilaan</th>
        </tr>
  		<tr>
          <td>
 		    <input type="search" id="filterString" class="light-table-filter" data-table="order-table" placeholder="Filter">

	  		<?php $display_baseurl = base_url("index.php/Log_controller/display") . "/" . $sort_by . "/" . $sort_order; ?>
      		<input type="hidden" id="displayBaseUrl" value="<?php echo $display_baseurl; ?>" />

	        <input type="hidden" id="filter_param" value="<?php echo $filter; ?>"/>
          </td>
		  <td>
		    <input type="hidden" id="selUserLimitOld" value="<?php echo $user_sel; ?>" />
      		<select name="userLimitChkBoxChooser" id="userLimitChkBoxChooser" onChange="jsFunction4()">
			  <?php 
			  foreach ($userOptions as $key=>$value) { ?>
			      <option value="<?php echo $value; ?>" <?php if ($user_sel == $value) echo "selected=\"selected\""?> ><?php echo $key; ?></option>
              <?php }
              ?>
      	    </select> 
		  </td>
          <td>
          </td>
          <td>
 		    <input type="hidden" id="selDateLimitOld" value="<?php echo $date_sel; ?>" />
      		<select name="dateLimitChkBoxChooser" id="dateLimitChkBoxChooser" onChange="jsFunction5()">
			  <?php 
			  foreach ($logDateOptions as $key=>$value) { ?>
			      <option value="<?php echo $value; ?>" <?php if ($date_sel == $value) echo "selected=\"selected\""?> ><?php echo $key; ?></option>
              <?php }
              ?>
      	    </select> 
          </td>
          <td>
            <a href="<?php echo base_url("index.php/Log_controller/display"); ?>" target="_parent" class="btn-clear"><button>CLR</button></a>
          </td>
  		</tr>
      </table>
    </div>

	<div id="content" class="contentResizable" style="<?php echo $saved_height ? 'height:'.$saved_height.'px;' : ''; ?>">
	  <div class="scrollInner">
        <table id="logtable" class="order-table table">
          <thead>
            <tr>
    			<?php foreach ($display_fields as $field_name => $field_display) { ?>
    			    <th <?php if ($sort_by == $field_name) echo "class=\"sort_" . $sort_order . "\"" ?>><span>
						<?php $hdrurl = base_url("index.php/Log_controller/display") . "/" . $field_name . "/" .
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
       		<?php foreach ($log_rows as $log_row) { ?>
               	<tr>
               		<?php $rowidx++; ?>
	    			<?php foreach ($display_fields as $field_name => $field_display) { ?>
	    				<?php $field_name_data = $field_display . $rowidx ?>
   			    	    <?php $field_input_name_id = $field_display . "input". $rowidx;

                        switch ($field_name) {
                            case "log_id": ?>
                                <td id="<?php echo $field_name_data; ?>" class="<?php echo $field_display; ?>">
                                <?php echo $log_row->$field_name; ?>
                                </td>
                                <?php
                                break;

                            default: ?>
                                <td id="<?php echo $field_name_data; ?>" class="<?php echo $field_display; ?>">
                                <?php echo $log_row->$field_name; ?>
                                </td>
                                <?php
                                break;
                        } // switch
                      } //foreach display_fields ?>
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
          <td width="85%">
	 	    <div id="totalcount">
  		      <span>Löytyi: <span id="tableRowCount"> <?php echo $num_results; ?></span> riviä</span>
    	    </div>
          </td>
          <td width="15%">
		    <div id="reportPrint">
  	  		  <input type="button" value="Raportti" class="btnAction" onclick="logToPDF()" />
    		</div>
          </td>
        </tr>
      </table>
    </div>


  </div><!-- wrapper -->
</body>
<script>


function jsFunction4() {
	var myselect = document.getElementById("userLimitChkBoxChooser");
	document.getElementById("selUserLimitOld").value = myselect.options[myselect.selectedIndex].value;
    var newUrl = document.getElementById("displayBaseUrl").value;
    newUrl = newUrl + "\\" + document.getElementById("selUserLimitOld").value;
    newUrl = newUrl + "\\" + document.getElementById("selDateLimitOld").value;
    newUrl = newUrl + "\\" + document.getElementById("filter_param").value;
	location.replace(newUrl);
}

function jsFunction5() {
	var myselect = document.getElementById("dateLimitChkBoxChooser");
	document.getElementById("selDateLimitOld").value = myselect.options[myselect.selectedIndex].value;
    var newUrl = document.getElementById("displayBaseUrl").value;
    newUrl = newUrl + "\\" + document.getElementById("selUserLimitOld").value;
    newUrl = newUrl + "\\" + document.getElementById("selDateLimitOld").value;
    newUrl = newUrl + "\\" + document.getElementById("filter_param").value;
	location.replace(newUrl);
}

document.addEventListener('DOMContentLoaded', function () {
	  enableResizableSave(
	    '.contentResizable',
	    '<?php echo base_url('index.php/Territory_controller/save_height'); ?>'
	  );
	});


</script>
</html>
