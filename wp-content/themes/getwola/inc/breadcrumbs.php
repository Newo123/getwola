<?php
function blog_breadcrumbs()
{
  $home = '/';
  $separator = '';

  echo '<nav><ul class="breadcrumbs">';

  // Главная
  echo '<li><a href="' . $home . '">Главная</a></li>';

  // Архив блога
  $blog_link = get_post_type_archive_link('blog');
  echo '<li><a href="' . $blog_link . '">Блог</a></li>';

  // Категория
  if (is_tax('categories')) {
    $term = get_queried_object();
    if ($term && !is_wp_error($term)) {
      echo '<li>' . esc_html($term->name) . '</li>';
    }
  }

  // Пост
  elseif (is_singular('blog')) {

    $category = null;

    // Yoast Primary Category
    if (class_exists('WPSEO_Primary_Term')) {
      $primary = new WPSEO_Primary_Term('categories', get_the_ID());
      $primary_id = $primary->get_primary_term();
      $term = get_term($primary_id);
      if ($term && !is_wp_error($term)) {
        $category = $term;
      }
    }

    // fallback
    if (!$category) {
      $terms = get_the_terms(get_the_ID(), 'categories');
      if ($terms && !is_wp_error($terms)) {
        $category = $terms[0];
      }
    }

    // вывод категории
    if ($category) {
      echo '<li><a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a></li>';
    }

    // текущий пост
    echo '<li>' . get_the_title() . '</li>';
  }

  echo '</ul></nav>';
}
?>