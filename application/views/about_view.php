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
      <span>
  	    <b class="aboutSubHdr">TerritoryServant - alueidenhoito-ohjelma</b> on kehitetty helpottamaan alueiden hoitoa.
	    <br/>Ohjelman avulla voit kirjata alueiden lainaukset, palautukset ja merkkaukset.
	    <br/>Ohjelmalla voit seurata myös alueiden käyntiä ja kiertoa.
	    <br/>Seurantaa helpottavat myös erilaiset raportit, jotka voi tarvittaessa tulostaa.
      </span>
      <br/>
      <br/>
      <span>
        Ohjelma on kehitetty Kankaanpäässä. Se käyttää olemassaolevaa tietokantaa,
        <br/>jota voi käyttää rinnakkain myös aikaisemman alueidenhoito-ohjelman kanssa.
        <br/>Jatkossa ohjelmaa voidaan kehittää myös muihin vastaaviin tarpeisiin.	
      </span>
      <br/>
      <span>
        <h2 class="aboutSubHdr">Toimintaympäristö</h2>
        WAMP/LAMP web server version: <?php echo $_SERVER['SERVER_SOFTWARE']; ?> 
        <br/>
        Ohjelmistokehys: Codeigniter version: <?php echo CI_VERSION; ?>
        <br/>
        Tietokanta: MySQL version: <?php echo $mysql_version; ?>
        <br/>
      </span>
      <span>
        <h2 class="aboutSubHdr">Käyttöliittymä</h2>
        javaScript version:
        <label id="javascript_version"></label>
        <br/>
        jQuery version:
        <label id="jquery_version"></label>
        <br/>
        jQuery UI version:
        <label id="jquery_ui_version"></label>
        (kalenteri on suomalainen)
        <br/>
        HTML5, CSS3
        <br/>
      </span>
      </div><!-- about_desc -->
      <div class="version_desc">
        <br/>
        <br/>
  	    <span>Tekijä: <?php echo $author; ?></span>
      </div>
    </div><!-- content -->
  </div><!-- wrapper -->
</body>

<script type="text/javascript">
  var jsver = 1.0;
</script>
<script language="Javascript1.1">
  jsver = 1.1;
</script>
<script language="Javascript1.2">
  jsver = 1.2;
</script>
<script language="Javascript1.3">
  jsver = 1.3;
</script>
<script language="Javascript1.4">
  jsver = 1.4;
</script>
<script language="Javascript1.5">
  jsver = 1.5;
</script>
<script language="Javascript1.6">
  jsver = 1.6;
</script>
<script language="Javascript1.7">
  jsver = 1.7;
</script>
<script language="Javascript1.8">
  jsver = 1.8;
</script>
<script language="Javascript1.9">
  jsver = 1.9;
</script>


<script type="text/javascript">
document.getElementById("javascript_version").innerHTML = jsver; 

document.getElementById("jquery_version").innerHTML = $().jquery; // yields the string "1.4.2", for example
//jQuery.fn.jquery
document.getElementById("jquery_ui_version").innerHTML = $.ui.version;

//alert($.ui.version);

</script>


</html>
