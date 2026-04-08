<?php
$current_post_id = get_the_ID();
$cache_key = 'related_posts_pro_' . $current_post_id;

$related_posts = get_transient($cache_key);

if (!$related_posts) {

  $posts_per_page = 15;
  $collected_posts = [];

  // -----------------------------
  // 1. PRIMARY CATEGORY (Yoast)
  // -----------------------------
  $category = null;

  if (class_exists('WPSEO_Primary_Term')) {
    $primary = new WPSEO_Primary_Term('categories', $current_post_id);
    $primary_id = $primary->get_primary_term();
    $term = get_term($primary_id);
    if ($term && !is_wp_error($term)) {
      $category = $term;
    }
  }

  if (!$category) {
    $terms = get_the_terms($current_post_id, 'categories');
    if ($terms && !is_wp_error($terms)) {
      $category = $terms[0];
    }
  }

  // -----------------------------
  // 2. ПО КАТЕГОРИИ
  // -----------------------------
  if ($category) {
    $query = new WP_Query([
      'post_type' => 'blog',
      'posts_per_page' => $posts_per_page,
      'post__not_in' => [$current_post_id],
      'tax_query' => [
        [
          'taxonomy' => 'categories',
          'field' => 'term_id',
          'terms' => $category->term_id,
        ]
      ],
    ]);

    $collected_posts = $query->posts;
  }

  // -----------------------------
  // 3. ПО ТЕГАМ (если мало)
  // -----------------------------
  if (count($collected_posts) < $posts_per_page) {

    $tags = get_the_terms($current_post_id, 'tags');

    if ($tags && !is_wp_error($tags)) {

      $exclude_ids = array_merge(
        [$current_post_id],
        wp_list_pluck($collected_posts, 'ID')
      );

      $query = new WP_Query([
        'post_type' => 'blog',
        'posts_per_page' => $posts_per_page - count($collected_posts),
        'post__not_in' => $exclude_ids,
        'tax_query' => [
          [
            'taxonomy' => 'tags',
            'field' => 'term_id',
            'terms' => wp_list_pluck($tags, 'term_id'),
          ]
        ],
      ]);

      $collected_posts = array_merge($collected_posts, $query->posts);
    }
  }

  // -----------------------------
  // 4. ДОБИВКА ПОСЛЕДНИМИ
  // -----------------------------
  if (count($collected_posts) < $posts_per_page) {

    $exclude_ids = array_merge(
      [$current_post_id],
      wp_list_pluck($collected_posts, 'ID')
    );

    $query = new WP_Query([
      'post_type' => 'blog',
      'posts_per_page' => $posts_per_page - count($collected_posts),
      'post__not_in' => $exclude_ids,
    ]);

    $collected_posts = array_merge($collected_posts, $query->posts);
  }

  // кеш
  set_transient($cache_key, $collected_posts, 12 * HOUR_IN_SECONDS);

  $related_posts = $collected_posts;
}
?>

<div class="blog">
  <div class="blog-header">
    <h2 class="blog-title">Это интересно</h2>
    <div class="blog-controls">
      <button id="blog-slider-btn-prev" class="blog-button back" disabled title="Назад">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M8.58997 16.5901L13.17 12.0001L8.58997 7.41012L9.99997 6.00012L16 12.0001L9.99997 18.0001L8.58997 16.5901Z"
            fill="white" />
        </svg>
      </button>
      <button id="blog-slider-btn-next" class="blog-button" title="Вперед">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M8.58997 16.5901L13.17 12.0001L8.58997 7.41012L9.99997 6.00012L16 12.0001L9.99997 18.0001L8.58997 16.5901Z"
            fill="white" />
        </svg>
      </button>
    </div>
  </div>
  <div class="blog-list-wrapper">
    <ul id="blog-slider" class="blog-list">

      <?php if (!empty($related_posts)): ?>

        <?php foreach ($related_posts as $post):
          setup_postdata($post);

          $link = get_permalink();
          $title = get_the_title();
          $display_date = get_the_date('j F Y');
          $datetime_attr = get_the_date('c');
          $category = get_the_terms(get_the_ID(), 'categories')[0] ?? null;
          $tag = get_the_terms(get_the_ID(), 'tags')[0] ?? null;
          ?>

          <li class="blog-slide">
            <article itemscope itemtype="https://schema.org/Article">
              <link itemprop="url" href="<?= $link; ?>" />
              <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                <meta itemprop="name" content="getwola" />
              </div>

              <a class="blog-item" href="<?= $link; ?>">
                <div class="blog-item-image">
                  <?php if ($tag): ?>
                    <span class="blog-item-tag <?= $tag->slug; ?>">
                      <?= $tag->name; ?>
                    </span>
                  <?php endif; ?>

                  <img src="<?= get_field('image', get_the_ID())['sizes']['medium_large']; ?>"
                    alt="<?= esc_attr($title); ?>" itemprop="image" />
                </div>

                <h2 class="blog-item-title" itemprop="headline">
                  <?= $title; ?>
                </h2>

                <div class="blog-item-details">
                  <?php if ($category): ?>
                    <span class="blog-item-category" itemprop="articleSection">
                      <?= $category->name; ?>
                    </span>
                  <?php endif; ?>

                  <time datetime="<?= $datetime_attr; ?>" class="blog-item-date" itemprop="datePublished">
                    <?= $display_date; ?>
                  </time>
                </div>
              </a>
            </article>
          </li>

        <?php endforeach; ?>
        <?php wp_reset_postdata(); ?>

      <?php endif; ?>

    </ul>
  </div>
</div>