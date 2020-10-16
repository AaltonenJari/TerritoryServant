<nav class="alue_navbar">
  <?php $sivu_tunnus = $this->session->userdata('sivutunnus');  ?>
  <?php $dropbtn_classes_base = "dropbtn" ?>

  <?php if (empty($this->session->userdata('initialized'))) {
      //echo "Userdata empty";
      $territory_view_defaults = array(
          'sort_by'         => 'alue_lastdate',
          'sort_order'      => 'asc',
          'chkbox_sel'      => '1', //Aluepöydässä
          'date_sel'        => '2', //yli 4 kk käymättä
          'code_sel'        => '0', //Kaikki
          'filter'          => '',
          'sivutunnus'      => '1', //Etusivu
          'initialized'     => 'K',
          'limit_date_sw'   => '0' //Jos 1, käytetään rajauksissa kv-viikon alkupäivää 
      );
      $this->session->set_userdata($territory_view_defaults);
      
      //default settings
      $settings_data = array(
          'congregationName' => 'Kankaanpää',
          'congregationNumber' => '38703',
          'useSignIn' => '0', //Kirjautuminen ei käytössä
          'terrCodePresentation' => 'X999',
          'useTerritoryDetaiTable' => '0', //Alue_detail-taulu ei käytössä
          'namePresentation'  => '1',  //0 = firstname lsatname, 1 = lastmame, firstname; (default)
          'eventOrder' => 'DESC',
          'archiveYears' => '12',
          'btSwitch' => '0',  //Liikealueet: 0 = ei näytetä (default), 1 = näytetään
          'eventSaveSwitch' => '0', //Vain lainaukset ja palautukset
          'circuitWeekStart' => '8.12.2020',
          'circuitWeekEnd' => '13.12.2020'
      );
      $this->session->set_userdata($settings_data);

      //default user
      $users_data = array(
          'user' => "alue",
          'admin' => "0"
      );
      $this->session->set_userdata($users_data);
      
  } else {
      //print_r($this->session->userdata);
      //default user
      $users_data = array(
      'user' => "alue",
      'admin' => "0"
          );
      $this->session->set_userdata($users_data);
      
  }?>
 
  <?php $base_url = base_url("index.php/territory_controller") ?>
  <input type="hidden" id="baseUrl" value="<?php echo $base_url; ?>" />
  
  <?php 
  $user = $this->session->userdata('user');
  $admin = $this->session->userdata('admin'); 
  ?>
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
                <li><a href="<?php echo base_url("index.php/territory_controller/display_mark_exhort"); ?>">Merkitsemiskehotuslistat</a></li>
                <li><a href="<?php echo base_url("index.php/territory_controller/display_return_exhort"); ?>">Palauttamiskehotuslistat</a></li>
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
          <a href="<?php echo base_url("index.php/maintenance_controller/display"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">
              <div class="tooltip">Ylläpito
                <span class="tooltiptext">Alueet - Ylläpito</span>
              </div>
            </button>
          </a>
          <div class="dropdown-content">
            <div class="nav-sub">
              <ul>
                <li><a href="<?php echo base_url("index.php/Person_controller/display"); ?>">Henkilötiedot</a></li>
                <li><a href="<?php echo base_url("index.php/Group_controller/display"); ?>">Ryhmät</a></li>
              </ul>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "5") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/Settings_controller/display"); ?>" >
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
          <a href="<?php echo base_url("index.php/User_controller/display"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">
              <div class="tooltip">Käyttäjät
                <span class="tooltiptext">Käyttäjähallinta</span>
              </div>
            </button>
          </a>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "7") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="#" >
            <button class="<?php echo $dropbtn_classes; ?>">Tietoja</button>
          </a>
        </div>
      </li>
    <li>
      <div class="dropdown">
          <button class="user_info">Käyttäjä: <?php echo $user; ?></button>
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
