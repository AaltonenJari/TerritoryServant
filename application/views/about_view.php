<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - tietoja</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">
  
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

    <!-- Asetetaan sivun pääotsikko -->
    <h1>TerritoryServant - Alueidenhoito-ohjelma</h1>


    <div id="content">
    </div><!-- content -->
    <span>Ohjelmaversio: <?php echo $version; ?></span>
	<br/>
    <span>Versiopäivä: <?php echo $version_date; ?></span>
	<br/>
    <br/>
    <div class="about_desc">
    <span>
  	  <b>TerritoryServant - alueidenhoito-ohjelma</b> on kehitetty helpottamaan seurakunnan alueiden hoitoa.
	  Ohjelman avulla voit kirjata alueiden lainaukset, palautukset ja merkkaukset.
	  Ohjelmalla voit seurata myös alueiden käyntiä ja kiertoa.
	  Seurantaa helpottavat myös erilaiset raportit, jotka voi tarvittaessa tulostaa.
    </span>
    <br/>
    <br/>
    <span>
      Ohjelma on kehitetty Kankaanpään seurakunnan alueiden hoitoon. Se käyttää olemassaolevaa tietokantaa,
      jota voi käyttää rinnakkain myös aikaisemman alueidenhoito-ohjelman kanssa.
      Jatkossa ohjelmaa voidaan kehittää myös muiden seurakuntien tarpeeseen.	
    </span>
    <br/>
    <br/>
    <span>
      Toimintaympäristö: WAMP/LAMP Server, Codeigniter, PHP 7.3, MySQL.	
    </span>
    </div>
    <br/>
    <br/>
    <br/>
	<span>Tekijä: <?php echo $author; ?></span>
  </div><!-- wrapper -->
</body>


</html>
