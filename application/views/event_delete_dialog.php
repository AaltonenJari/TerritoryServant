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

  <div id="container">
     <?php echo form_open('event_controller/check_delete_options', ['class' => 'delete_event_area']); ?>

    <h1>Tapahtumien poisto</h1>
    
    <div class="delete_event_subhdr">
    Poista vanhat tapahtumamerkinnät
    </div>
    
    <div class="delete_settings">
      <div class="delete_setting">
        Poista tapahtumamerkinnät, jotka on tehty
        <?php 
          echo form_dropdown(
            'archive_years',            // name-attribuutti
            $archiveYearsOptions,       // vaihtoehdot
            $selectedYear,              // oletusvalinta
           ['class' => 'year_dropdown'] // HTML-luokka (valinnainen)
          ); 
        ?>
        sitten.
      </div>

      <div class="delete_setting">
        Poista myös henkilöt, joilla ei ole merkittyjä alueita:
        <?php 
          echo form_checkbox([
            'name'    => 'delete_persons',
            'id'      => 'delete_persons',
            'value'   => '1',
            'checked' => $deletePersons,
            'class'   => 'delete_checkbox'
          ]); 
        ?>
      </div>
    </div>

    <?php if (isset($deleted_event_count)) : ?>
      <div class="delete_feedback">
        <p>Poistettiin <?php echo $deleted_event_count; ?> tapahtumaa.</p>

        <?php if (!empty($deletePersons)) : ?>
          <p>Poistettiin <?php echo $deleted_person_count; ?> henkilöä, joilla ei ole ollut alueita.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="form-footer">
      <div class="button-group">
        <div class="button-left">
          <?php echo form_submit([
            'class' => 'submit_btn',
            'name'  => 'action',
            'value' => 'Poista'
          ]); ?>
        </div>
 
        <div class="button-right">
          <?php echo form_submit([
            'class' => 'submit_btn',
            'name'  => 'action',
            'value' => 'Paluu'
          ]); ?>
        </div>
      </div>

      <div class="form-error">
        <?php echo $this->session->flashdata("error"); ?>
      </div>
    </div>

    <?php echo form_close(); ?>
  </div><!-- container -->
</body>
</html>