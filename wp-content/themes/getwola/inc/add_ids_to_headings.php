<?php
function add_ids_to_headings($content, $include_h_tags = ['h2', 'h3'])
{
  if (empty($content))
    return '';

  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
  libxml_clear_errors();

  foreach ($include_h_tags as $tag) {
    $nodes = $dom->getElementsByTagName($tag);
    foreach ($nodes as $node) {
      $classes = explode(' ', $node->getAttribute('class'));
      $exclude = $node->getAttribute('data-toc') === 'false' || in_array('no-toc', $classes);

      if (!$exclude && !$node->getAttribute('id')) {
        $id = sanitize_title($node->textContent);
        $node->setAttribute('id', $id);
      }
    }
  }

  $html = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));
  return preg_replace('~^<body>(.*)</body>$~s', '$1', $html);
}