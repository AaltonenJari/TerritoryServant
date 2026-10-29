<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Seuranta</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/pie_chart.css"); ?>">

  <script src="<?php echo base_url("assets/javascript/pieChartToPDF.js"); ?>"></script> 

  <!--link jquery ui css-->
  <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.css'); ?>" />

  <!--load jquery-->
  <script src="<?php echo base_url('assets/javascript/jquery-1.10.2.js'); ?>"></script>
  <!--load jquery ui js file-->
  <script src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.js'); ?>"></script>
</head>

<body>

  <div class="wrapper">
    <!-- Asetetaan navigointipalkki ja tämä sivu aktiiviseksi -->
    <?php $sivu_tunnus = "2"; ?>
    <?php $session_data = array(
            'sivutunnus' => $sivu_tunnus
            );
          $this->session->set_userdata($session_data);
    ?>
    <?php $this->load->view('common/navbar.php')?>
    
 	<div id="content">

      <?php $terr_url = base_url("index.php/territory_controller/display"); ?>

      <!-- Asetetaan sivun pääotsikko -->
      <div class="headerArea">
        <h1>TerritoryServant - Vuosi käymättä, prosenttijakauma <?php echo $report_date; ?></h1>
      </div>
    
      <div class="statistic-area">
        <h3 class="pie-chart-title">
          Käymättömät alueet alueryhmittäin
        </h3>
        
        <div class="pie-chart-area">

          <div class="pie-chart"
             style="background: <?php echo $pie_background; ?>;"
             role="img"
             aria-label="Käymättömien alueiden prosenttijakauma alueryhmittäin">
          </div>

          <div class="pie-chart-legend">
            <?php foreach ($legend_items as $item): ?>
              <div class="pie-chart-legend-item">
                <span class="pie-chart-color"
                  style="background-color: <?php echo $item['color']; ?>;">
                </span>

                <span class="pie-chart-name">
                  <?php echo htmlspecialchars(
                        $item['name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>
                </span>

                <span class="pie-chart-count">
                  <?php echo $item['uncovered']; ?> kpl
                </span>

                <span class="pie-chart-percentage">
                  <?php echo number_format(
                        $item['percentage'],
                        1,
                        ',',
                        ' '
                    ); ?> %
                </span>

              </div><!-- pie-chart-legend-item -->
            <?php endforeach; ?>
          </div><!-- pie-chart-legend -->
        </div><!--  pie-chart-area -->    
      </div><!--  statistic-area -->
   	</div><!-- content -->
   	
    <div class="bottomArea">
      <div class="totalcount">
        <span>
            Vuosi käymättä:
            <span id="tableRowCount"><?php echo $num_results; ?></span>% alueista
        </span>
      </div>

      <div class="bottomButtons">
        <input type="button"
               value="Raportti"
               class="btnAction"
               onclick="pieChartToPDF()" />

        <input type="button"
               value="Paluu"
               class="btnAction"
               onclick="showTerritories('<?php echo $terr_url; ?>')" />
      </div>
    </div>
  
  </div><!-- wrapper -->
</body>
<script>
function showTerritories(param) {
	var newUrl = param;
	  //alert(newUrl);
	  location.replace(newUrl);
}
</script>

</html>
