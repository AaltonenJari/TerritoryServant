<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Tapahtumat</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/terr_mark.css"); ?>">
  <!--link jquery ui css-->
  <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.css'); ?>" />

  <!--load jquery-->
  <script src="<?php echo base_url('assets/javascript/jquery-1.10.2.js'); ?>"></script>
  <!--load jquery ui js file-->
  <script src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.js'); ?>"></script>

</head>

<body>

<?php echo form_open("event_controller/event_delete_confirm_options/$selectedYears/$limit_date/$deletePersons", ['class' => 'confirm-form-area']); ?>

  <div id="confirmModal" class="confirm-dialog">
    
    <div class="confirm-dialog-container">
        <div class="confirm-text-header">Haluatko poistaa tapahtumia?</div>
        <div class="confirm-text">Poistetaan tapahtumat, joiden päiväys on vanhenpi kuin <span class="bold-area"><?php echo $limit_date ?></span>.</div>
        <?php if (!empty($deletePersons)) { ?>
           <div class="confirm-text">Poistetaan henkilöitä, joilla ei ole tapahtumia.</div>
        <?php } ?>

        <div class="confirm-button-area">
          <div class="confirm-button-group">
            <div class="confirm-button-left">
              <?php echo form_submit([
                'class' => 'submit_btn',
                'name'  => 'action',
                'value' => 'Kyllä'
              ]); ?>
            </div>
 
            <div class="confirm-button-right">
              <?php echo form_submit([
                'class' => 'submit_btn',
                'name'  => 'action',
                'value' => 'Ei'
              ]); ?>
            </div>
          </div>
        </div>
    </div>
  </div>
<?php echo form_close(); ?>

</body>
</html>