<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Tietoja</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">
  
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
    <?php $sivu_tunnus = "7"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>

   <div class="headerArea">
      <!-- Asetetaan sivun pääotsikko -->
      <h1>TerritoryServant - Tietoja</h1>
    </div>
  
    <div id="content">
      <div class="version_desc">
        <span>Ohjelmaversio: <?php echo $version; ?></span>
	    <br/>
        <span>Versiopäivä: <?php echo $version_date; ?></span>
	    <br/>
        <br/>
      </div>
      <div class="about_desc">
        <div class="about-chapter">
    	  <span class="about-sub-hdr">TerritoryServant - alueidenhoito-ohjelma</span> on kehitetty helpottamaan alueiden hoitoa.
	      <br/>Ohjelman avulla voit kirjata alueiden lainaukset, palautukset ja merkkaukset.
	      Ohjelmalla voit seurata myös alueiden käyntiä ja kiertoa.
	      Seurantaa helpottavat myös erilaiset raportit, jotka voi tarvittaessa tulostaa.
        </div>

        <div class="about-chapter">
          Ohjelma on kehitetty Kankaanpäässä. Se käyttää olemassa olevaa tietokantaa,
          jota voi käyttää rinnakkain myös aikaisemman alueidenhoito-ohjelman kanssa.
        </div>

        <div class="about-sub-hdr">Toimintaympäristö</div>
        <ul class="about-list">
          <li>WAMP/LAMP web server version: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
          <li>Ohjelmistokehys: CodeIgniter version: <?php echo CI_VERSION; ?></li>
          <li>Tietokanta: MySQL version: <?php echo $mysql_version; ?></li>
        </ul>
      
        <div class="about-sub-hdr">Käyttöliittymä</div>
        <ul class="about-list">
          <li>Browser: <label id="browser_info"></label></li>
          <li>javaScript version: <label id="javascript_version"></label></li>
          <li>jQuery version: <label id="jquery_version"></label></li>
          <li>jQuery UI version: <label id="jquery_ui_version"></label> (kalenteri on suomalainen)</li>
        </ul>
        
        <div class="about-sub-hdr">Uutta versiossa 2.x:</div>
        <ul class="about-list">
          <li>Uudistettu käyttöliittymä ja pienet bugikorjaukset parantavat yleisilmettä</li>
          <li>Siistimpi PDF-raporttien ulkoasu</li>
          <li>Mahdollisuus merkitä alue poistetuksi ilman pysyvää poistamista</li>
          <li>Mahdollisuus poistaa vanhentuneita tapahtumia ja henkilötietoja</li>
          <li>Erillistoiminta luukutustapahtumille</li>
        </ul>

      </div><!-- about_desc -->

      <div class="version_desc">
  	    <span>Tekijä: <?php echo $author; ?></span>
      </div>
    </div><!-- content -->
  </div><!-- wrapper -->
</body>

<script type="text/javascript">
/* ===== SELAIN ===== */
function getBrowserInfo() {
    var ua = navigator.userAgent;
    var browser = "Unknown";
    var version = "Unknown";

    if (/MSIE/.test(ua)) {
        browser = "Internet Explorer";
        version = ua.match(/MSIE ([0-9.]+)/)[1];
    } else if (/Trident/.test(ua)) {
        browser = "Internet Explorer";
        version = ua.match(/rv:([0-9.]+)/)[1];
    } else if (/Edg/.test(ua)) {
        browser = "Edge";
        version = ua.match(/Edg\/([0-9.]+)/)[1];
    } else if (/Chrome/.test(ua)) {
        browser = "Chrome";
        version = ua.match(/Chrome\/([0-9.]+)/)[1];
    } else if (/Firefox/.test(ua)) {
        browser = "Firefox";
        version = ua.match(/Firefox\/([0-9.]+)/)[1];
    } else if (/Safari/.test(ua)) {
        browser = "Safari";
        version = ua.match(/Version\/([0-9.]+)/)[1];
    } else if (/OPR|Opera/.test(ua)) {
        browser = "Opera";
        var match = ua.match(/(OPR|Opera)\/([0-9.]+)/);
        version = match ? match[2] : "Unknown";
    }

    return browser + " " + version;
}

/* ===== JS FEATURE DETECTION ===== */
function detectJSVersion() {
    var version = "ES5";

    try { eval("() => {}"); version = "ES6"; } catch (e) {}
    try { eval("class A {}"); version = "ES6+"; } catch (e) {}
    try { eval("async function f() {}"); version = "ES2017"; } catch (e) {}
    try { eval("let x = 1 ?? 2;"); version = "ES2020"; } catch (e) {}

    return version;
}

/* ===== TULOSTUS ===== */
document.getElementById("browser_info").innerHTML = getBrowserInfo();
document.getElementById("javascript_version").innerHTML = detectJSVersion();

/* jQuery */
if (window.jQuery) {
    document.getElementById("jquery_version").innerHTML = jQuery.fn.jquery;
} else {
    document.getElementById("jquery_version").innerHTML = "Not loaded";
}

/* jQuery UI */
if (window.jQuery && jQuery.ui) {
    document.getElementById("jquery_ui_version").innerHTML = jQuery.ui.version;
} else {
    document.getElementById("jquery_ui_version").innerHTML = "Not loaded";
}
</script>

</html>
