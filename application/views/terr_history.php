<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Aluen merkitseminen</title>
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
    

    <?php echo form_open('territory_controller/check_history/' . $terr_nbr . "/" . $main_display); ?>
    <table class="table_history">
      <tr>
        <td>
          <h1>Alueen <?php echo $terr_nbr; ?> historiatiedot</h1>
        </td>
      </tr>
     
      <tr>
        <td>
          <div class="tableWrap">
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
          </div>
        </td>
      </tr>
      
      <tr>
        <td>
           <table id="cardbuttons">
             <tr>
               <td width="35%">
                 <?php echo form_submit(array('id' => 'submit', 'name' => 'action', 'value' => 'Poista')); ?>
               </td>
               <td width="10%">
                 <?php echo form_submit(array('id' => 'submit', 'name' => 'action', 'value' => 'Undo')); ?>
                </td>
               <td width="25%">
                 <?php echo form_submit(array('id' => 'submit', 'name' => 'action', 'value' => 'Redo')); ?>
               </td>
               <td width="10%">
               </td>
               <td width="20%">
                 <?php echo form_submit(array('id' => 'submit', 'name' => 'action', 'value' => 'Paluu')); ?>
               </td>
             </tr>
             <tr>
               <td colspan="4">
                 <?php echo $this->session->flashdata("error");	?>
               </td>
             </tr>
           </table> <!-- cardbuttons -->
        </td>
      </tr>
    </table> <!-- table_history -->
    <?php echo form_close(); ?>           
    
  </div><!-- container -->
</body>


         
</html>
