<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Historia</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/terr_mark.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/territory.css"); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/events.css"); ?>">
  
  <!--link jquery ui css-->
  <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.css'); ?>" />

  <!--load jquery-->
  <script src="<?php echo base_url('assets/javascript/jquery-1.10.2.js'); ?>"></script>
  <!--load jquery ui js file-->
  <script src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.js'); ?>"></script>

</head>

<body>

  <div id="container">

    <?php echo form_open('territory_controller/check_history/' . $terr_nbr . "/" . $main_display, ['class' => 'history_form_area']); ?>
    <div class="dialog-form-container">
  
    <h1>Alueen <?php echo $terr_nbr; ?> historiatiedot</h1>

      <div class="dialog-form-content">
        <div id="content" class="contentResizable" style="height: 340px">
          <div class="scrollInner">
            <table class="table3">
              <thead class="table3Header">
                <tr>
                  <th class="table3Hdr" colspan="20">Alueen käyntihistoria</th>
                </tr>
                <tr>
                  <th class="table3HdrRow" width="5%">
                    <div class="hdrtext">
                      Alue<br>nro
                    </div>
                  </th>
                  <th class="table3HdrRow" width="5%">
                    <div class="hdrnbr">
                      <?php echo $terr_nbr; ?>
                    </div>
                  </th>
                    <th class="table3HdrRow" width="5%"></th>
                    <th class="table3HdrRow" width="5%"></th>
                </tr>
              </thead>
              
              <tbody class="table3body">
                  <?php $rowidx = 0; ?>
                  <?php foreach ($event_data as $row_key => $row_value) { ?>
                    <?php foreach ($row_value as $row_item_key => $row_item_value) { ?>
                      <tr>
                        <?php if ($row_item_key == "names") { ?>
                          <?php foreach ($row_item_value as $name_key => $name_value) { ?>
                            <td class="event_nimi" colspan="4"><?php echo $name_value; ?></td>
                          <?php } ?>
                        <?php } else if ($row_item_key == "dates") { ?>
                          <?php $colidx = 0; ?>
                          <?php foreach ($row_item_value as $date_key => $date_value) {
                            if ($colidx % 2 == 0) { ?>
                              <td class="event_lainattu" colspan="2"><?php echo $date_value; ?></td>
                            <?php } else { ?>
                              <td class="event_palautettu" colspan="2"><?php echo $date_value; ?></td>
                            <?php }
                              $colidx++;
                            ?>
                         <?php } ?>
                       <?php } ?>
                    </tr>
                    <?php }
                    $rowidx++; ?>
                  <?php } ?>
              </tbody>
            </table>

            <?php
              // hidden input voidaan sijoittaa tänne tai formin sisään
              $data_hidden = [
                  'type'  => 'hidden',
                  'id'    => 'submit_action',
                  'name'  => 'action',
                  'value' => 'Update',
                  'class' => 'submit_action_btn'
              ];
              echo form_input($data_hidden);
            ?>
          </div>
        </div>
  
        <div class="footer-area">
          <div class="button-group">
            <div class="button-left">
               <?php          
               $data = [
                  'type'  => 'submit',
                  'id'    => 'submit_remove',
                  'name'  => 'action_btn',
                  'value' => 'Poista',
                  'class' => 'submit_btn'
               ];
                    
               $js = ['onClick' => 'jsFunction_remove(this);'];
               echo form_input($data,' ',$js);
               ?>
            </div>
 
            <div class="buttons-center">
                <?php if ($can_undo) {
                   $data = [
                       'type'  => 'image',
                       'id'    => 'submit_undo',
                       'name'  => 'action_btn',
                       'value' => 'Undo',
                       'class' => 'submit_undo',
                       'src' => base_url("assets/images/Undo.jpg")
                   ];
                     
                   $js = ['onClick' => 'jsFunction_undo(this);'];
                   echo form_submit($data,' ',$js);
                 } else {
                   $data = [
                       'type'  => 'image',
                       'id'    => 'submit_undo_disabled',
                       'name'  => 'action_btn_disabled',
                       'value' => 'Undo',
                       'class' => 'submit_btn_disabled',
                       'src' => base_url("assets/images/Undo_disabled.jpg"),
                       'disabled'  => 'true'
                   ];
                   echo form_submit($data);
                } ?>

                <?php if ($can_redo) {
                   $data = [
                       'type'  => 'image',
                       'id'    => 'submit_redo',
                       'name'  => 'action_btn',
                       'value' => 'Redo',
                       'class' => 'submit_undo',
                       'src' => base_url("assets/images/Redo.jpg")
                   ];
                     
                   $js = ['onClick' => 'jsFunction_redo(this);'];
                   echo form_submit($data,' ',$js);
                 } else {
                   $data = [
                       'type'  => 'image',
                       'id'    => 'submit_redo_disabled',
                       'name'  => 'action_btn_disabled',
                       'value' => 'Redo',
                       'class' => 'submit_btn_disabled',
                       'src' => base_url("assets/images/Redo_disabled.jpg"),
                       'disabled'  => 'true'
                   ];
                   echo form_submit($data);
                } ?>
            </div>

            <div class="button-right">
              <?php          
                  $data = [
                    'type'  => 'submit',
                    'id'    => 'submit_return_history',
                    'name'  => 'action_btn',
                    'value' => 'Paluu',
                    'class' => 'submit_btn'
                 ];
                    
                 $js = ['onClick' => 'jsFunction_return(this);'];
                 echo form_input($data,' ',$js);
                 ?>
            </div>
          </div><!-- button-group -->

          <div class="form-error">
            <?php echo $this->session->flashdata("error"); ?>
          </div>
        </div><!-- footer-area -->
         
      </div><!-- dialog-form-content -->
    </div><!-- dialog-form-container -->

    <?php echo form_close(); ?>
    
  </div><!-- container -->
</body>

<script>
function jsFunction_remove(me) {
	document.getElementById("submit_action").value = "Remove";
}

function jsFunction_return(me) {
	document.getElementById("submit_action").value = "Return";
}

function jsFunction_undo(me) {
	document.getElementById("submit_action").value = "Undo";
}

function jsFunction_redo(me) {
	document.getElementById("submit_action").value = "Redo";
}
</script>
         
</html>
