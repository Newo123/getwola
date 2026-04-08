<?php
function get_post_toc($content_blocks = [])
{
  $toc = [];
  $tags = ['h2', 'h3'];

  foreach ($content_blocks as $block) {
    if (empty($block))
      continue;

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $block);
    libxml_clear_errors();

    foreach ($tags as $tag) {
      $nodes = $dom->getElementsByTagName($tag);
      foreach ($nodes as $node) {
        if ($node->getAttribute('data-toc') === 'false')
          continue;

        if (!$node->getAttribute('id')) {
          $id = sanitize_title($node->textContent);
          $node->setAttribute('id', $id);
        }

        $toc[] = [
          'text' => $node->textContent,
          'id' => $node->getAttribute('id'),
          'tag' => $tag
        ];
      }
    }
  }

  return $toc;
}