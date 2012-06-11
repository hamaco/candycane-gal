<?php

$pluginContainer = ClassRegistry::getObject('PluginContainer');
$pluginContainer->installed('cc_gal', '0.0.1');

App::uses('CakeEventManager', 'Event');

CakeEventManager::instance()->attach(function ($event)
  {
    $gals =(array) json_decode(file_get_contents(dirname(__FILE__) . '/gal.json'));

    $text = $event->data['text'];
    foreach ($gals as $search => $replace) {
      if (strpos($text, $search) !== false) {
        $text = str_replace($search, $replace[array_rand($replace)], $text);
      }
    }

    $event->result['text'] = $text;
  }, 'Helper.Candy.afterTextilizable');
