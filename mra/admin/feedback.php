<?php

function feedback($msg = false, $type = false) {
  $feed = '';
  if($msg) {
    $feed .= '<div class="mra_feedback_wrap">';
    $feed .= '<div class="mra_feedback';
    if($type) {
      $feed .= ' feed_'.$type;
    }
    $feed .= '">'.$msg.'</div></div>';
  }
  return $feed;
}
