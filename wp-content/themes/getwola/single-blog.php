<?php

require_once(__DIR__ . '/inc/breadcrumbs.php');
require_once(__DIR__ . '/inc/get_post_toc.php');
require_once(__DIR__ . '/inc/add_ids_to_headings.php');


$title = get_the_title();
$display_date = get_the_date('j F Y');
$datetime_attr = get_the_date('c');
$description = get_the_excerpt();
$time = get_field('read_time', $post->ID);
$image = get_field('image', $post->ID);
$content_1 = get_field('content_1', $post->ID);
$content_2 = get_field('content_2', $post->ID);
$content_1_with_ids = add_ids_to_headings($content_1);
$content_2_with_ids = add_ids_to_headings($content_2);

$contents = array_filter([$content_1_with_ids, $content_2_with_ids]);

$toc = get_post_toc($contents);

?>

<?php get_header(); ?>

<main class="main">
  <div class="container">
    <?php blog_breadcrumbs(); ?>

    <section class="post">
      <article itemscope itemtype="https://schema.org/Article">
        <div class="post-container">
          <div class="post-header">
            <link itemprop="mainEntityOfPage" href="https://example.com/blog/voss-premium-water" />
            <link itemprop="url" href="https://example.com/blog/voss-premium-water" />
            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
              <meta itemprop="name" content="getwola" />
            </div>
            <meta itemprop="articleSection" content="Статьи о воде" />
            <div class="post-details">
              <?php if ($time): ?>
                <span class="post-reading-time">Время прочтения ≈ <?= $time; ?></span>
              <?php endif; ?>
              <div class="post-share-wrap">
                <button type="button" id="post-share-btn" class="post-share" title="Поделиться" aria-haspopup="true"
                  aria-expanded="false" aria-controls="post-share-popup">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                      d="M5.83244 5.00021C4.45173 5.00021 3.33244 6.1195 3.33244 7.50021L3.33244 14.1669C3.33244 15.5476 4.45173 16.6669 5.83244 16.6669H12.4991C13.8798 16.6669 14.9991 15.5476 14.9991 14.1669V10.8335C14.9991 10.3733 15.3722 10.0002 15.8324 10.0002C16.2927 10.0002 16.6658 10.3733 16.6658 10.8335V14.1669C16.6658 16.4681 14.8003 18.3335 12.4991 18.3335H5.83244C3.53125 18.3335 1.66577 16.4681 1.66577 14.1669L1.66577 7.50021C1.66577 5.19902 3.53125 3.33354 5.83244 3.33354L9.16577 3.33354C9.62601 3.33354 9.99911 3.70664 9.99911 4.16688C9.99911 4.62711 9.62601 5.00021 9.16577 5.00021L5.83244 5.00021Z"
                      fill="#9C9EB7" />
                    <path
                      d="M13.9534 4.92288L8.56605 10.3099C8.25565 10.6203 8.25565 11.1235 8.56605 11.4339C8.87645 11.7443 9.37971 11.7443 9.69011 11.4339L15.0769 6.04742V7.50006C15.0769 7.939 15.4328 8.29483 15.8718 8.29483C16.3107 8.29483 16.6666 7.939 16.6666 7.50006V4.1281C16.6666 3.68916 16.3107 3.33333 15.8718 3.33333L12.4996 3.33333C12.0606 3.33333 11.7048 3.68916 11.7048 4.1281C11.7048 4.56705 12.0606 4.92288 12.4996 4.92288L13.9534 4.92288Z"
                      fill="#5B5C70" />
                  </svg>
                </button>
                <div id="post-share-popup" class="post-share-popup" role="dialog" aria-label="Поделиться в соцсетях"
                  aria-hidden="true">
                  <ul>
                    <li>
                      <a target="_blank" href="https://telegram.me/share/url?url=<?= get_permalink($post->ID); ?>">
                        <img src="<?= get_template_directory_uri(); ?>/assets/images/telegram.svg" alt="telegram"
                          width="24" height="24" />
                        Telegram
                      </a>
                    </li>
                    <li>
                      <a target="_blank" href="https://vk.com/share.php?url=<?= get_permalink($post->ID); ?>">
                        <img src="<?= get_template_directory_uri(); ?>/assets/images/vk.svg" alt="vk" width="24"
                          height="24" />
                        VK
                      </a>
                    </li>
                    <li>
                      <a target="_blank" href="https://max.ru/:share?url=<?= get_permalink($post->ID); ?>">
                        <img src="<?= get_template_directory_uri(); ?>/assets/images/max.svg" alt="max" width="24"
                          height="24" />
                        MAX
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <h1 class="post-title" itemprop="headline">
              <?= $title; ?>
            </h1>
            <time datetime="<?= $datetime_attr; ?>" class="post-date"
              itemprop="datePublished"><?= $display_date; ?></time>
            <?php if ($description): ?>
              <p class="post-description" itemprop="description">
                <?= $description; ?>
              </p>
            <?php endif; ?>
            <div class="post-image">
              <img itemprop="image" src="<?= get_field('image', $post->ID)['url']; ?>" alt="voss" />
            </div>
          </div>

          <div class="post-content">
            <div class="post-content-container">
              <button id="post-content-btn" class="post-content-title active">
                Содержание
              </button>
              <?php if ($toc): ?>
                <nav aria-label="Содержание" class="post-content-list-wrapper">
                  <ul class="post-content-list">
                    <?php foreach ($toc as $item): ?>
                      <li>
                        <a href="#<?= $item['id'] ?>"><?= $item['text'] ?></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </nav>
              <?php endif; ?>
            </div>
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

          <div class="post-body">
            <?php if ($content_1_with_ids): ?>
              <div class="post-block">
                <?= $content_1_with_ids; ?>
              </div>
            <?php endif; ?>
            <?= get_template_part('partials/products'); ?>
            <?php if ($content_2_with_ids): ?>

              <div class="post-block">
                <?= $content_2_with_ids; ?>
              </div>
            <?php endif; ?>

            <?= get_template_part('partials/interesting'); ?>
            <?= get_template_part('partials/vote'); ?>

          </div>
        </div>
      </article>
    </section>
  </div>
</main>
<?php get_footer(); ?>