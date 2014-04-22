<?php

function feedback($msg, $type = false) {
  $feed = '';
  if($msg) {
    $feed .= '<div class="mra_feedback';
    if($type) {
      $feed .= ' feed_'.$type;
    }
    $feed .= '">'.$msg.'</div>';
  }
  return $feed;
}
