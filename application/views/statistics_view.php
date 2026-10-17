<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Seuranta</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/navbar.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/statistics.css"); ?>">

  <script src="<?php echo base_url("assets/javascript/statisticToPDF.js"); ?>"></script> 

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
        <h1>TerritoryServant - Vuosi käymättä <?php echo $report_date; ?></h1>
      </div>
    
      <div class="statistic-area">
        <?php foreach ($statistic_data as $row): ?>
          <?php
          $code = htmlspecialchars($row['code'], ENT_QUOTES, 'UTF-8');
          $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
          $uncovered = (int)$row['uncovered'];
          $total = (int)$row['total'];
          $uncovered_pc = (int)$row['uncovered_pc'];

          // Prosentuaaliset leveydet suhteessa suurimpaan total-arvoon.
          $uncovered_width = ($max_total > 0)
            ? $uncovered / $max_total * 100
            : 0;

          $total_width = ($max_total > 0)
            ? $total / $max_total * 100
            : 0;
          ?>

		  <div class="statistic-row">
		    <div class="statistic-title">
              <?= $code ?> – <?= $name ?>
            </div>
		  
            <div class="statistic-bar-row">
              <div class="statistic-bar uncovered-bar"
                style="width: <?= $uncovered_width ?>%;">
                <?php if ($uncovered_width >= $text_width_limit): ?>
                  <span class="bar-text-inside">
                    Käymättä <?= $uncovered ?> kpl. <?= $uncovered_pc ?>% alueista.
                  </span>
                <?php endif; ?>
              </div>

              <?php if ($uncovered_width < $text_width_limit): ?>
                <span class="bar-text-outside">
                  Käymättä <?= $uncovered ?> kpl. <?= $uncovered_pc ?>% alueista.
                </span>
              <?php endif; ?>
            </div>

            <div class="statistic-bar-row">
              <div class="statistic-bar total-bar"
                style="width: <?= $total_width ?>%;">
                <?php if ($total_width >= $text_width_limit): ?>
                  <span class="bar-text-inside">
                    Yhteensä <?= $total ?> kpl.
                  </span>
                <?php endif; ?>
              </div>

              <?php if ($total_width < $text_width_limit): ?>
                <span class="bar-text-outside">
                  Yhteensä <?= $total ?> kpl.
                </span>
              <?php endif; ?>
            </div>
	      </div>
        <?php endforeach; ?>
      </div> <!-- statistic-area -->
    
   	</div><!-- content -->
   	
    <div class="bottomArea">
      <div class="totalcount">
        <span>
            Vuosi käymättä:
            <span id="tableRowCount"><?php echo $num_results; ?></span>% alueista
        </span>
      </div>

      <div class="bottomButtons">
        <div id="reportPrint">
            <input type="button"
                   value="Raportti"
                   class="btnAction"
                   onclick="statisticToPDF()" />
        </div>

        <div id="returnToMain">
            <input type="button"
                   value="Paluu"
                   class="btnAction"
                   onclick="showTerritories('<?php echo $terr_url; ?>')" />
        </div>
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
