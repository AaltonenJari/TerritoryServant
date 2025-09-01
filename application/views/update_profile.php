<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>TerritoryServant - Käyttäjäprofiili</title>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/terr_mark.css"); ?>">

</head>

<body>

  <div id="container">
    <?php echo form_open('User_controller/check_profile'); ?>
    
    <h1>Käyttäjäprofiilin päivitys</h1>
    
    <table id="cardtable">
      <?php foreach ($display_fields as $field_name => $field_display) { ?>
      <tr>
        <td>
          <?php
            //Näytettävä kenttä
            $attributes = [
            'class' => "profile_desc"
            ];
            echo form_label($field_display.":", $field_name, $attributes);
          ?>
 
        </td>
        <td>
          <?php 
          switch ($field_name) {
              case "user_id":
                  //Näytettävä kenttä
                  $attributes = [
                      'class' => "profile_value_label"
                  ];
                  echo form_label($user[$field_name], $field_name, $attributes);

                  //Piilokenttä vanhaa arvoa varten
                  $data_hidden = [
                    'type'  => 'hidden',
                    'id'    => $field_display ."_hidden",
                    'name'  => $field_name,
                    'value' => $user[$field_name],
                    'class' => "profile_value_hidden"
                  ];
                  echo form_input($data_hidden);
                  break;
                  
              case "user_admin":
                  //Piilokenttä uutta arvoa varten
                  $data = [
                      'type'  => 'hidden',
                      'id'    => $field_display,
                      'name'  => $field_name,
                      'value' => $user[$field_name],
                      'class' => "profile_value_hidden"
                  ];
                  echo form_input($data);
                  
                  if (!empty($this->session->userdata('admin'))) {
                      //Editoitava kenttä
                      $field_input_editing_id = $field_display . "_dropdown";
                      $js = [
                          'id'       => $field_input_editing_id,
                          'class'    => "profile_value",
                          'onChange' => "jsDropdownChance(this, " . $field_display . ")"
                      ];
                      echo form_dropdown($field_input_editing_id, $userRoleOptions, $user[$field_name], $js);
                  } else {
                      //Näytettävä kenttä, ei editoitava
                      $data_readonly = [
                         'type'  => 'text',
                         'id'    => $field_display."_readonly",
                         'name'  => $field_name,
                         'value' => $userRoleOptions[$user[$field_name]],
                         'class' => 'enableadminonly',
                         'readonly' => 'readonly'
                         ];
                      echo form_input($data_readonly);
                  }
                  
                  //Piilokenttä vanhaa arvoa varten
                  $data_hidden = [
                      'type'  => 'hidden',
                      'id'    => $field_display ."_hidden",
                      'name'  => $field_name . "_old",
                      'value' => $user[$field_name],
                      'class' => "profile_value_hidden"
                  ];
                  echo form_input($data_hidden);
                  break;
              
              default:
                  //Editoitava kenttä
                  $data = [
                      'type'  => 'text',
                      'id'    => $field_display,
                      'name'  => $field_name,
                      'value' => $user[$field_name],
                      'class' => "profile_value"
                  ];
                  echo form_input($data);
                  break;
          }
          ?>
        </td>
      </tr>
      <?php } ?>
      <tr>
        <td>
        <?php 
          //Näytettävä kenttä
          $attributes = [
              'class' => "profile_value_label"
          ];
          echo form_label('Salasana:<br>', 'profile_value_label1', $attributes);
          echo form_label('(jätä tyhjäksi jos et muuta sitä)','profile_value_label2', $attributes);
          ?>
        </td>
        <td>
        <?php 
          //Editoitava kenttä
          $data = [
             'type'  => 'password',
             'id'    => 'salasana',
             'name'  => 'user_password',
             'value' => '',
             'class' => 'profile_value'
          ];
          echo form_input($data);
        ?>
        </td>
      </tr>
      <tr>
        <td>
        <?php 
          //Piilokenttä editointimoodia varten
          $data_hidden = [
             'type'  => 'hidden',
             'id'    => 'editing_mode_hidden',
             'name'  => 'editing_mode',
             'value' => $editing_mode,
             'class' => 'profile_value_hidden'
          ];
          echo form_input($data_hidden);
        ?>
        </td>
        <td>
          <span class="text-danger"></span>
         </td>
      </tr>
    </table> <!-- cardtable -->
	
    <table id="cardbuttons">
      <tr>
        <td width="30%">
          <?php echo form_submit(array('id' => 'submit', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Päivitä')); ?>
        </td>
        <td width="30%">
        </td>
        <td width="40%">
          <?php echo form_submit(array('id' => 'submit', 'class'=> 'submit_btn', 'name' => 'action', 'value' => 'Paluu')); ?>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <?php echo $this->session->flashdata("error");	?>
        </td>
      </tr>
    </table>
   
    <?php echo form_close(); ?>

  </div><!-- container -->
</body>

<script type="text/javascript">
function jsDropdownChance(selectObject,fieldObject) {
	var selectedValue = selectObject.value;
	document.getElementById(fieldObject.id).value = selectedValue;
}
</script>
</html>
