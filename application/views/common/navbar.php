<nav class="alue_navbar">
  <?php $sivu_tunnus = $this->session->userdata('sivutunnus');  ?>
  <?php $dropbtn_classes_base = "dropbtn" ?>

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
                <li><a href="#">Merkitsemiskehotuslistat</a></li>
                <li><a href="#">KV-raportti</a></li>
                <li><a href="#">Match Manager</a></li>
              </ul>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "3") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/event_controller/display"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">Tapahtumat</button>
          </a>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "4") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <a href="<?php echo base_url("index.php/maintenance_controller/maintain"); ?>" >
            <button class="<?php echo $dropbtn_classes; ?>">Ylläpito</button>
          </a>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "5") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <button class="<?php echo $dropbtn_classes; ?>">Asetukset</button>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <?php if ($sivu_tunnus == "6") { $dropbtn_classes = $dropbtn_classes_base . " active"; } else { $dropbtn_classes = $dropbtn_classes_base; } ?>
          <button class="<?php echo $dropbtn_classes; ?>">Tietoja</button>
        </div>
      </li>
    </ul>
  </div>
</nav>
