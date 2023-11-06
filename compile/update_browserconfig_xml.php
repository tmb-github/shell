<?php

echo 'Updating browserconfig.xml . . .' . PHP_EOL;

$absolute_root = $_SERVER['ABSOLUTE_ROOT'];

$browserconfig_xml = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
$browserconfig_xml .= '<browserconfig>' . PHP_EOL;
$browserconfig_xml .= '  <msapplication>' . PHP_EOL;
$browserconfig_xml .= '    <tile>' . PHP_EOL;
// Apparently need to use a full, qualified path:
$browserconfig_xml .= '      <square150x150logo src="' . $root_dir . $mstile_150x150_png . '"/>' . PHP_EOL;
$browserconfig_xml .= '      <TileColor>' . $browserconfig_tile_color . '</TileColor>' . PHP_EOL;
$browserconfig_xml .= '    </tile>' . PHP_EOL;
$browserconfig_xml .= '  </msapplication>' . PHP_EOL;
$browserconfig_xml .= '</browserconfig>' . PHP_EOL;

file_put_contents($absolute_root . 'assets/favicons/browserconfig.xml', $browserconfig_xml);