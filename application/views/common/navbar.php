<nav class="alue_navbar">
  <?php $sivu_tunnus = $this->session->userdata('sivutunnus');  ?>
  <?php $dropbtn_classes_base = "dropbtn" ?>

  <?php if (empty($this->session->userdata('initialized'))) {
      //echo "Userdata empty";
      $session_data_terr = array(
          'sort_by'         => 'alue_lastdate',
          'sort_order'      => 'asc',
          'chkbox_sel'      => '1',
          'date_sel'        => '2',
          'filter'          => '',
          'sivutunnus'      => '1',
          'initialized'     => 'K',
      );
      $this->session->set_userdata($session_data_terr);
      
      //echo "Userdata empty";
      $setting_data_terr = array(
          'event_date_order' => "DESC",
          'archive_time' => "12",
          'name_presentation'  => '1',  //0 = firstname lsatname, 1 = lastmame, firstname; (default)
          'bt_switch' => '0',  //0 = ei näytetä (default), 1 = näytetään 
          'circuit_week_start' => "30.6.2020",
          'circuit_week_end' => "5.7.2020",
          'limit_date_sw' => "0"
              );
      $this->session->set_userdata($setting_data_terr);
  } else {
      //print_r($this->session->userdata);
  }?>
 
  <?php $base_url = base_url("index.php/territory_controller") ?>
  <input type="hidden" id="baseUrl" value="<?php echo $base_url; ?>" />
  
  <div class="naw_wrapper">
    <ul>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "1") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/territory_controller/display_frontpage"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">
              <div class="tooltip">Etusivu
                <span class="tooltiptext">Tervetuloa käyttämään alueohjelmaa!</span>
              </div>
            </button>
          </a>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "2") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/territory_controller/display"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">
              <div class="tooltip">Seuranta
                <span class="tooltiptext">Alueet - seuranta ja merkitseminen</span>
              </div>
            </button>
          </a>
          <div class="dropdown-content">
            <div class="nav-sub">
              <ul>
                <li><a href="<?php echo base_url("index.php/territory_controller/display/name/asc/2/2/0"); ?>">Alueet, saa merkitä</a></li>
                <li><a href="<?php echo base_url("index.php/territory_controller/display_marklist"); ?>">Merkitsemiskehotuslistat</a></li>
                <li><a href="<?php echo base_url("index.php/territory_controller/display_co_report"); ?>">KV-raportti</a></li>
                <li><a href="#" class="link"><input type="checkbox" id="cweek_check_id" name="cweek_check" value="<?php echo $this->session->userdata('limit_date_sw'); ?>" <?php if (!empty($this->session->userdata('limit_date_sw'))) { ?>checked <?php } ?>>Kierrosviikon alusta</a></li>
              </ul>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "3") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/event_controller/display"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">
              <div class="tooltip">Tapahtumat
                <span class="tooltiptext">Alueet - Kirjanpito</span>
              </div>
            </button>
          </a>
          <div class="dropdown-content">
            <div class="nav-sub">
              <ul>
                <li><a href="<?php echo base_url("index.php/event_controller/display_bookkeeping"); ?>">Koko kirjanpito</a></li>
              </ul>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "4") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/maintenance_controller/maintain"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">
              <div class="tooltip">Ylläpito
                <span class="tooltiptext">Alueet - Ylläpito</span>
              </div>
            </button>
          </a>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "5") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/settings_controller/settings"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">
              <div class="tooltip">Asetukset
                <span class="tooltiptext">Ohjelman asetukset</span>
              </div>
            </button>
          </a>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "6") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="#" >
            <button class="<?php echo $dropbtn_classes; ?>">Tietoja</button>
          </a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<script type="text/javascript">
$(document).ready(function()
		{
		  $('#cweek_check_id').change(function() 
		  {
			if (!this.checked) {
			   $('#cweek_check_id').val('0');
			} else {
			   $('#cweek_check_id').val('1');
		    }
		    //Linkin asetus checkboxin tilan muutoksen jälkeen
		    var val = document.getElementById("cweek_check_id").value;
			var str = document.getElementById("baseUrl").value;
			str = str + "/kierrosviikon_alusta/" + val;
			$(".link").attr('href', str);
		  });   
		  //Linkin asetus alussa
		  var val = document.getElementById("cweek_check_id").value;
		  var str = document.getElementById("baseUrl").value;
		  str = str + "/kierrosviikon_alusta/" + val;
		  $(".link").attr('href', str);
		});
</script>
