<?php

require_once(__DIR__ . '/inc/breadcrumbs.php');

global $wp_query;
$paged = get_query_var('paged') ?: 1;

?>

<?php get_header(); ?>

<main class="main">
  <div class="container">

    <?php blog_breadcrumbs(); ?>

    <section class="blog">
      <h1 class="blog-title">
        <?php
        $term = get_queried_object();

        if (is_tax('categories') && $term && !is_wp_error($term)) {
          $title = $term->name;
        } elseif (is_post_type_archive('blog')) {
          $title = 'Все публикации';
        } else {
          $title = get_the_title() ?: 'Блог';
        }
        echo $title;
        ?>
      </h1>
      <div class="blog-container">
        <div class="blog-list-wrapper">
          <?php if ($wp_query->have_posts()): ?>
            <ul class="blog-list">
              <?php while ($wp_query->have_posts()):
                $wp_query->the_post();

                $link = get_permalink();
                $title = get_the_title();
                $display_date = get_the_date('j F Y');
                $datetime_attr = get_the_date('c');
                $category = get_the_terms(get_the_ID(), 'categories')[0] ?? null;
                $tag = get_the_terms(get_the_ID(), 'tags')[0] ?? null;
                ?>
                <li>
                  <article itemscope itemtype="https://schema.org/Article">
                    <link itemprop="url" href="<?= $link; ?>" />
                    <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                      <meta itemprop="name" content="getwola" />
                    </div>
                    <a class="blog-item" href="<?= $link; ?>">
                      <div class="blog-item-image">
                        <?php if ($tag): ?>
                          <span class="blog-item-tag <?= $tag->slug; ?>"><?= $tag->name; ?></span>
                        <?php endif; ?>
                        <img src="<?= get_field('image', $post->ID)['sizes']['medium_large']; ?>" alt="<?= $title; ?>"
                          itemprop="image" />
                      </div>
                      <h2 class="blog-item-title" itemprop="headline">
                        <?= $title; ?>
                      </h2>
                      <div class="blog-item-details">
                        <?php if ($category): ?>
                          <span class="blog-item-category" itemprop="articleSection">
                            <?= $category->name; ?>
                          </span>
                        <?php endif ?>
                        <time datetime="<?= $datetime_attr; ?>" class="blog-item-date"
                          itemprop="datePublished"><?= $display_date; ?></time>
                      </div>
                    </a>
                  </article>
                </li>
              <?php endwhile; ?>
            </ul>
            <?php wp_reset_postdata(); ?>
          <?php endif; ?>
          <!-- Пагинация начало -->

          <?php
          $total_pages = $wp_query->max_num_pages;

          if ($total_pages > 1):

            $current_url = get_pagenum_link(1);
            ?>

            <nav class="blog-pagination" aria-label="Навигация по страницам блога">

              <!-- Назад -->
              <?php if ($paged > 1): ?>
                <a class="blog-pagination-button back disabled" href="<?= get_pagenum_link($paged - 1); ?>" tabindex="-1"
                  title="Назад"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M8.58997 16.5901L13.17 12.0001L8.58997 7.41012L9.99997 6.00012L16 12.0001L9.99997 18.0001L8.58997 16.5901Z"
                      fill="white" />
                  </svg>
                </a>
              <?php endif; ?>


              <!-- Номера страниц -->
              <ul class="blog-pagination-links">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                  <li>
                    <a href="<?= get_pagenum_link($i); ?>" class="<?= ($i == $paged) ? 'blog-pagination-current' : ''; ?>"
                      <?= ($i == $paged) ? 'aria-current="page"' : ''; ?>>
                      <?= $i; ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <li>
                  <span class="blog-pagination-total"> из <?= $total_pages; ?></span>
                </li>
              </ul>

              <!-- Вперёд -->
              <?php if ($paged < $total_pages): ?>
                <a class="blog-pagination-button" href="<?= get_pagenum_link($paged + 1); ?>" title="Вперёд">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M8.58997 16.5901L13.17 12.0001L8.58997 7.41012L9.99997 6.00012L16 12.0001L9.99997 18.0001L8.58997 16.5901Z"
                      fill="white" />
                  </svg>
                </a>
              <?php endif; ?>
            </nav>

          <?php endif; ?>
          <!-- Пагинация конец -->
        </div>

        <div class="blog-filters-wrapper">
          <!-- Фильтр начало -->
          <?php
          $terms = get_terms(array(
            'taxonomy' => 'categories',
            'hide_empty' => true,
          ));

          $blog_archive_link = get_post_type_archive_link('blog');

          $current_term = (is_tax('categories')) ? get_queried_object() : null;
          ?>

          <ul class="blog-filters">
            <!-- Все публикации -->
            <li>
              <a class="blog-filters-item <?= (!$current_term) ? 'active' : ''; ?>"
                href="<?= esc_url($blog_archive_link); ?>">
                Все публикации
                <span class="quantity">
                  <?= wp_count_posts('blog')->publish; ?>
                </span>
              </a>
            </li>

            <!-- Категории -->
            <?php foreach ($terms as $term): ?>
              <li>
                <a class="blog-filters-item <?= ($current_term && $current_term->term_id === $term->term_id) ? 'active' : ''; ?>"
                  href="<?= esc_url(get_term_link($term)); ?>">
                  <?= esc_html($term->name); ?>
                  <span class="quantity">
                    <?= $term->count; ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
          <!-- Фильтр конец -->

          <div id="open-modal-btn" role="button" class="subscribe">
            <svg aria-label="Подпишитесь" role="img" viewBox="0 0 800 200">
              <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="80" font-weight="600"
                fill="white" stroke="#2f6df6" stroke-width="49" stroke-linejoin="round" stroke-linecap="round"
                paint-order="stroke">
                Подпишитесь
              </text>
            </svg>

            <p class="subscribe-text">
              на рассылку <br />
              наших новостей <br />
              и акций
            </p>
            <button type="button" class="subscribe-button">
              Подписаться
            </button>
          </div>
        </div>


      </div>
    </section>
  </div>
</main>

<?php get_footer(); ?>