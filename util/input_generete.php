<?php


function generateInput($type = 'text',$question){
  switch ($type) {
    case 'text':
      return "<input name='text_$question->id' type='text' class='form-control' />";
      break;
    
    default:
      # code...
      break;
  }
  return `

  `;
}