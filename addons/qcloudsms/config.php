<?php

return array (
  0 => 
  array (
    'name' => 'appid',
    'title' => '应用AppID',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'tcentenid',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'appkey',
    'title' => '应用AppKEY',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'your appkey',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'sign',
    'title' => '签名',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'your sign',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  3 => 
  array (
    'name' => 'isVoice',
    'title' => '是否使用语音短信',
    'type' => 'radio',
    'content' => 
    array (
      0 => '否',
      1 => '是',
    ),
    'value' => '0',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  4 => 
  array (
    'name' => 'isTemplateSender',
    'title' => '是否使用短信模板发送',
    'type' => 'radio',
    'content' => 
    array (
      0 => '否',
      1 => '是',
    ),
    'value' => '1',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  5 => 
  array (
    'name' => 'template',
    'title' => '短信模板',
    'type' => 'array',
    'content' => 
    array (
    ),
    'value' => 
    array (
      'register' => '',
      'resetpwd' => '',
      'changepwd' => '',
      'profile' => '',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  6 => 
  array (
    'name' => 'voiceTemplate',
    'title' => '语音短信模板',
    'type' => 'array',
    'content' => 
    array (
    ),
    'value' => 
    array (
      'register' => '',
      'resetpwd' => '',
      'changepwd' => '',
      'profile' => '',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
);
