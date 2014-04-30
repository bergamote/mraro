<?php require('session.php'); ?>
<div id="page-settings">
<b>Settings</b> <i class="small">(edit in mra.conf)</i>
<br><br>
<?php 
foreach ($mra as $key => $value) {
  if ($key == 'theme'){
    home_setting($mra);
  }else{
    regular_setting($key,$value);
  }
} 
function regular_setting($key,$value) {
  //echo ''.$key.' = <input name="'.$key.'" type="text" value="';
  //echo $value.'"><br>';
  echo $key.' = '.$value.'<br>';
}
function home_setting($mra) {
  echo 'theme = ';
  ?>
  <select id="theme-list">
    <option value="">none</option>
  <?php
  foreach(glob(THEME_DIR.'*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace(THEME_DIR, '', $dir);   
    echo '<option value="'.$dir.'" ';
    if($dir == $mra['theme']) {
      echo 'selected';
    }
    echo '>'.$dir.'</option>';
}
?>  
    
  </select><br>
  <?php
}
?>
<br>
<div id="cache-info">

<?php
if (count(glob(CACHE."/*")) != 0 ) {
  $cmd =  'du -h '.CACHE;
  $ca_size = `$cmd`;
  $ca_size = trim(str_replace(CACHE,'',$ca_size));
  echo "Cache: ".$ca_size;
  echo ' <a class="mra_button" href="./?action=clear-cache">Clear</a>';
}else{
  echo "Cache: empty";
}

?>
</div>
</div>
