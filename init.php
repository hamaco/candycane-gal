<?php

$pluginContainer = ClassRegistry::getObject('PluginContainer');
$pluginContainer->installed('cc_gal', '0.0.1');

App::uses('CakeEventManager', 'Event');

CakeEventManager::instance()->attach(function ($event)
  {
    $gals =(array) json_decode(file_get_contents(dirname(__FILE__) . '/gal.json'));

    $replaced_tags = array();
    $i = 1;

    $text = $event->data['text'];
    $text = preg_replace_callback("/<[^>]+>/", function ($matches) use(&$i, &$replaced_tags) {
      $replaced_tags["[".$i."]"] = $matches[0];

      return "[" . $i++ . "]";
    }, $text);

    foreach ($gals as $search => $replace) {
      if (empty($replace) === false && strpos($text, $search) !== false) {
        $text = str_replace($search, $replace[array_rand($replace)], $text);
      }
    }
    $text = str_replace(array_keys($replaced_tags), array_values($replaced_tags), $text);

    $event->result['text'] = $text;
  }, 'Helper.Candy.afterTextilizable');
