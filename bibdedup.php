<?php
require_once 'vendor/autoload.php';

$parser = new RenanBr\BibTexParser\Parser();
$listener = new RenanBr\BibTexParser\Listener();
$parser->addListener($listener);
$parser->parseFile('bibexport.bib');
$entries = $listener->export();

function normalizeTitle($title) {
  $title = preg_replace('/\s/', ' ', $title);
  $title = preg_replace('/[^a-z ]/i', '', $title);
  $title = preg_replace('/\s+/', ' ', $title);
  return substr(strtolower($title), 0, 255);
}

$titles = array();
foreach ($entries as $entry) {
  $titles[$entry['citation-key']] = normalizeTitle($entry['title']);
}
count($titles) > 0 or die('No BibTeX entry found.');

$matrix = array();
foreach ($titles as $k1=>$t1) {
  foreach ($titles as $k2=>$t2) {
    if (strcmp($k1, $k2) >= 0) {
      continue;
    }
    $matrix[$k1."\n".$k2] = levenshtein($t1, $t2);
  }
}

asort($matrix);
foreach ($matrix as $keys=>$cost) {
  list($k1, $k2) = explode("\n", $keys);
  printf("%4d %s => %s\n     %s => %s\n\n", $cost, $k1, $titles[$k1], $k2, $titles[$k2]);
}
?>