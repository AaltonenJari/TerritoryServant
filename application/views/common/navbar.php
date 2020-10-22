<nav class="alue_navbar">
  <?php $sivu_tunnus = $this->session->userdata('sivutunnus');  ?>
  <?php $dropbtn_classes_base = "dropbtn" ?>

  <?php if (!empty($this->session->userdata('username'))) {?>
 
    <?php $base_url = base_url("index.php/territory_controller") ?>
    <input type="hidden" id="baseUrl" value="<?php echo $base_url; ?>" />
    
    <?php 
    $user = $this->session->userdata('username');
    $userid = $this->session->userdata('user_id');
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
        <?php if (!empty($this->session->userdata('useSignIn')) || $user != "guest") { ?>
        <li>
          <div class="dropdown">
            <?php if ($sivu_tunnus == "6") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
            <a href="<?php echo base_url("index.php/User_controller/update_profile/own/" . $userid); ?>" >
              <button class="<?php echo $dropbtn_classes; ?>">
                <div class="tooltip">Käyttäjät
                  <span class="tooltiptext">Oman käyttäjäprofiilin päivitys</span>
                </div>
              </button>
            </a>
            <?php if (!empty($this->session->userdata('admin'))) { //Käyttäjähallinta vain pääkäyttäjille ?>
            <div class="dropdown-content">
              <div class="nav-sub">
                <ul>
                  <li><a href="<?php echo base_url("index.php/User_controller/display"); ?>">Käyttäjähallinta</a></li>
                </ul>
              </div>
            </div>
            <?php }?>
          </div>
        </li> 
        <?php }?>
        <li>
          <div class="dropdown">
            <?php if ($sivu_tunnus == "7") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
            <a href="#" >
              <button class="<?php echo $dropbtn_classes; ?>">Tietoja</button>
            </a>
          </div>
        </li>
        <?php if (!empty($this->session->userdata('useSignIn')) || $user != "guest") { ?>
        <li>
          <div class="dropdown">
            <button class="user_info">Käyttäjä: <?php echo $user; ?>
			  <a href='<?php echo base_url("index.php/LoginController/logout"); ?>'><span class="user_out">ULOS</span></a>
            </button>
          </div>
        </li>
        <?php }?>
      </ul>
    </div>
<?php } else {
    $new_url = base_url("index.php/LoginController");
    header('Location: ' . $new_url);
    exit;
    //redirect($new_url);
  } ?>
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
