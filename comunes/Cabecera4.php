<?php
function nvl($value = null, $default = '') {
    return isset($value) && $value !== null ? $value : $default;
}
?>
<div id="header">
    <div id="header-tabs">
      <ul>
       
      <li<?php echo nvl($menu01 ?? ''); ?>><a href="#"><span>Colaboradores</span></a></li>
      <li<?php echo nvl($menu08 ?? ''); ?>><a href="#"><span>Menu</span></a></li>
      <li><?php echo nvl($menu09 ?? ''); ?><a href="../salir.php"><span>Salir</span></a></li>
        
   </ul>
  </div>
</div>
