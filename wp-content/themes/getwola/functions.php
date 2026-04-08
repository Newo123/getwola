<?php

function start_php_session()
{
  if (!session_id()) {
    session_start();
  }
}
add_action('init', 'start_php_session', 1);

function set_assets()
{
  $theme_version = wp_get_theme()->get('Version');
  $version_string = is_string($theme_version) ? $theme_version : false;

  wp_enqueue_style('style-main', get_template_directory_uri() . '/assets/styles/main.css', array(), $version_string);
  wp_enqueue_script('script-modal', get_template_directory_uri() . '/assets/js/modal.js', array(), $version_string, true);

  if (is_post_type_archive('blog')) {
    wp_enqueue_style('style-blog', get_template_directory_uri() . '/assets/styles/blog.css', array(), $version_string);
  }

  if (is_tax('categories')) {
    wp_enqueue_style('style-blog', get_template_directory_uri() . '/assets/styles/blog.css', array(), $version_string);
  }

  if (is_singular('blog')) {
    wp_enqueue_style('style-toastify', 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css', array(), true);
    wp_enqueue_style('style-post', get_template_directory_uri() . '/assets/styles/post.css', array(), $version_string);
    wp_enqueue_style('style-typography', get_template_directory_uri() . '/assets/styles/typography.css', array(), $version_string);
    wp_enqueue_script('script-toastify', 'https://cdn.jsdelivr.net/npm/toastify-js', [], null, true);
    wp_enqueue_script('script-post', get_template_directory_uri() . '/assets/js/post.js', array('script-toastify'), $version_string, true);


    wp_localize_script('script-post', 'vote_ajax_obj', [
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('script-post')
    ]);

  }
  wp_localize_script('script-modal', 'subscribe_ajax_obj', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('script-modal')
  ]);

}

add_action('wp_enqueue_scripts', 'set_assets');


add_action('init', function () {
  register_post_type('blog', array(
    'labels' => array(
      'name' => 'Блог',
      'singular_name' => 'Блог',
      'menu_name' => 'Блог',
    ),
    'public' => true,
    'show_in_rest' => true,
    'has_archive' => 'blog',
    'rewrite' => array('slug' => 'blog/%categories%', 'with_front' => false),
    'supports' => array('title', 'thumbnail', 'excerpt'),
  ));

  register_taxonomy('categories', 'blog', array(
    'labels' => array(
      'name' => 'Категории',
      'singular_name' => 'Категория',
    ),
    'hierarchical' => true,
    'show_admin_column' => true,
    'show_in_rest' => true,
    'rewrite' => array('slug' => 'blog', 'with_front' => false),
  ));

  register_taxonomy('tags', 'blog', array(
    'label' => 'Теги',
    'hierarchical' => false,
    'public' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => false,
    'rewrite' => false,
  ));

  add_rewrite_rule(
    '^blog/([^/]+)/page/([0-9]+)/?$',
    'index.php?categories=$matches[1]&paged=$matches[2]',
    'top'
  );

  add_rewrite_rule(
    '^blog/([^/]+)/?$',
    'index.php?categories=$matches[1]',
    'top'
  );
});

add_filter('post_type_link', function ($post_link, $post) {
  if ($post->post_type === 'blog') {
    $category_slug = 'uncategorized';

    if (class_exists('WPSEO_Primary_Term')) {
      $primary_term = new WPSEO_Primary_Term('categories', $post->ID);
      $primary_term_id = $primary_term->get_primary_term();
      $term = get_term($primary_term_id);
      if ($term && !is_wp_error($term)) {
        $category_slug = $term->slug;
      }
    }

    if ($category_slug === 'uncategorized') {
      $terms = wp_get_post_terms($post->ID, 'categories');
      if ($terms && !is_wp_error($terms)) {
        $category_slug = $terms[0]->slug;
      }
    }

    $post_link = str_replace('%categories%', $category_slug, $post_link);
  }

  return $post_link;
}, 10, 2);

add_action('pre_get_posts', function ($query) {
  if (!is_admin() && $query->is_main_query() && is_tax('categories')) {
    $query->set('post_type', 'blog');
    $query->is_archive = true;
    $query->is_single = false;
  }
});

add_filter('template_include', function ($template) {
  if (is_post_type_archive('blog') || is_tax('categories')) {
    $new_template = locate_template('archive-blog.php');
    if ($new_template)
      return $new_template;
  }
  return $template;
});

add_action('after_switch_theme', function () {
  flush_rewrite_rules();
});

add_action('save_post_blog', function ($post_id) {
  delete_transient('related_posts_pro_' . $post_id);
});



add_action('wp_ajax_vote_article', 'handle_vote_article');
add_action('wp_ajax_nopriv_vote_article', 'handle_vote_article');

function handle_vote_article()
{
  $post_id = intval($_POST['post_id'] ?? 0);
  $vote = $_POST['vote'] ?? '';

  if (!$post_id || !in_array($vote, ['like', 'dislike'])) {
    wp_send_json_error('Неверные данные');
  }

  if (!isset($_SESSION['voted_posts'])) {
    $_SESSION['voted_posts'] = [];
  }

  if (in_array($post_id, $_SESSION['voted_posts'])) {
    wp_send_json_error('Вы уже голосовали за эту статью');
  }

  $likes = intval(get_post_meta($post_id, 'likes_count', true));
  $dislikes = intval(get_post_meta($post_id, 'dislikes_count', true));

  if ($vote === 'like') {
    $likes++;
  } else {
    $dislikes++;
  }

  update_post_meta($post_id, 'likes_count', $likes);
  update_post_meta($post_id, 'dislikes_count', $dislikes);

  $_SESSION['voted_posts'][] = $post_id;

  wp_send_json_success([
    'likes' => $likes,
    'dislikes' => $dislikes
  ]);
}



add_filter('manage_blog_posts_columns', function ($columns) {
  $columns['likes_count'] = 'Лайки';
  $columns['dislikes_count'] = 'Дизлайки';
  return $columns;
});


add_action('manage_blog_posts_custom_column', function ($column, $post_id) {
  if ($column === 'likes_count') {
    $likes = get_post_meta($post_id, 'likes_count', true);
    echo $likes !== '' ? intval($likes) : 0;
  } elseif ($column === 'dislikes_count') {
    $dislikes = get_post_meta($post_id, 'dislikes_count', true);
    echo $dislikes !== '' ? intval($dislikes) : 0;
  }
}, 10, 2);


add_filter('manage_edit-blog_sortable_columns', function ($columns) {
  $columns['likes_count'] = 'likes_count';
  $columns['dislikes_count'] = 'dislikes_count';
  return $columns;
});


add_action('pre_get_posts', function ($query) {
  if (!is_admin())
    return;

  $orderby = $query->get('orderby');

  if ($orderby === 'likes_count') {
    $query->set('meta_key', 'likes_count');
    $query->set('orderby', 'meta_value_num');
  } elseif ($orderby === 'dislikes_count') {
    $query->set('meta_key', 'dislikes_count');
    $query->set('orderby', 'meta_value_num');
  }
});

add_action('add_meta_boxes', function () {
  add_meta_box(
    'blog_votes_meta_box',
    'Лайки и Дизлайки',
    'render_blog_votes_meta_box',
    'blog',
    'side',
    'default'
  );
});

function render_blog_votes_meta_box($post)
{
  $likes = intval(get_post_meta($post->ID, 'likes_count', true));
  $dislikes = intval(get_post_meta($post->ID, 'dislikes_count', true));

  echo '<p><strong>Лайки:</strong> ' . $likes . '</p>';
  echo '<p><strong>Дизлайки:</strong> ' . $dislikes . '</p>';
}



add_action('wp_ajax_subscribe_user', 'handle_subscription');
add_action('wp_ajax_nopriv_subscribe_user', 'handle_subscription');

function handle_subscription()
{
  // Проверяем nonce
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'script-modal')) {
    wp_send_json_error(['message' => 'Неверный nonce']);
  }

  // Получаем данные
  $email = sanitize_email($_POST['email']);
  $checkbox = isset($_POST['checkbox']) && $_POST['checkbox'] === 'on';

  if (empty($email) || !is_email($email)) {
    wp_send_json_error(['message' => 'Введите корректный email']);
  }

  if (!$checkbox) {
    wp_send_json_error(['message' => 'Необходимо согласие на обработку данных']);
  }

  // Здесь ваша логика подписки
  // Например, сохранение в базу данных или отправка в сервис рассылок

  // $newsletter = Newsletter::instance();

  // $newsletter->save_user([
  //   'email' => $email,
  //   'status' => 'C',
  //   'ip' => $_SERVER['REMOTE_ADDR'],
  // ]);

  // Успешный ответ
  wp_send_json_success(['message' => 'Вы успешно подписались!']);
}


// add_action('transition_post_status', 'send_blog_newsletter_on_publish', 10, 3);

// function send_blog_newsletter_on_publish($new_status, $old_status, $post)
// {
//     if ($post->post_type !== 'blog') return;
//     if ($old_status === 'publish') return;
//     if ($new_status !== 'publish') return;
//     if (get_post_meta($post->ID, '_newsletter_sent', true)) return;
//     update_post_meta($post->ID, '_newsletter_sent', 1);
//     if (!class_exists('Newsletter')) return;

//     $newsletter = Newsletter::instance();

//     // Данные поста
//     $title = get_the_title($post->ID);
//     $link = get_permalink($post->ID);
//     $excerpt = wp_trim_words(strip_tags($post->post_content), 30);

//     // Ваш контент для шаблона
//     $custom_content = "
//         <h2>{$title}</h2>
//         <p>{$excerpt}</p>
//         <p><a href='{$link}'>Читать полностью</a></p>
//     ";

//     // ID шаблона Newsletter (берется из админки)
//     $template_id = 1; // замените на нужный ID
//     $template_html = $newsletter->

//     // Вставляем контент в шаблон
//     $message = str_replace('[[content]]', $custom_content, $template_html);

//     // Создаем письмо
//     $email = [
//         'subject' => 'Новая статья: ' . $title,
//         'message' => $message,
//         'type' => 'message',
//     ];

//     $email_id = $newsletter->save_email($email);

//     if ($email_id) {
//         $newsletter->send($email_id);
//     }
// }