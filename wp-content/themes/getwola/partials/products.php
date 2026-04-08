<?php

$products = get_field('products', $post->ID);

if (!empty($products)):
  ?>
  <div class="products">
    <div class="products-header">
      <h2 class="products-title">Товары</h2>
      <div class="products-controls">
        <button id="products-slider-btn-prev" class="products-button back" disabled title="Назад">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M8.58997 16.5901L13.17 12.0001L8.58997 7.41012L9.99997 6.00012L16 12.0001L9.99997 18.0001L8.58997 16.5901Z"
              fill="white" />
          </svg>
        </button>
        <button id="products-slider-btn-next" class="products-button" title="Вперед">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M8.58997 16.5901L13.17 12.0001L8.58997 7.41012L9.99997 6.00012L16 12.0001L9.99997 18.0001L8.58997 16.5901Z"
              fill="white" />
          </svg>
        </button>
      </div>
    </div>
    <div class="products-list-wrapper">
      <ul id="products-slider" class="products-list">
        <?php foreach ($products as $product):
          $discount_percent = '';
          if (!empty($product['sale_price']) && !empty($product['price'])) {
            $price = floatval(preg_replace('/[^0-9.]/', '', $product['price']));
            $sale_price = floatval(preg_replace('/[^0-9.]/', '', $product['sale_price']));

            if ($price > 0 && $sale_price > 0) {
              $discount_percent = round((1 - $sale_price / $price) * 100);
            }
          }
          ?>
          <li class="products-slide">
            <a class="products-item" href="<?= $product['link']['url']; ?>" target="<?= $product['link']['target']; ?>">
              <?php if ($product['balls']): ?>
                <p class="products-item-bonus"><?= $product['balls']; ?> <span>Б</span></p>
              <?php endif; ?>
              <div class="products-item-image">
                <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>" />
              </div>
              <div class="products-item-content">
                <div class="products-item-price">
                  <div class="products-item-price-wrapper">
                    <p class="products-item-oldprice">
                      <?= $product['price']; ?> <span>₽/шт</span>
                    </p>
                    <?php if ($discount_percent): ?>
                      <span class="products-item-sale">−<?= $discount_percent; ?>%</span>
                    <?php endif; ?>
                  </div>
                  <?php if (!empty($product['sale_price'])): ?>
                    <p class="products-item-newprice">
                      <?= $product['sale_price']; ?> <span> ₽/шт</span>
                    </p>
                  <?php endif; ?>
                </div>
                <p class="products-item-name">
                  <?= $product['name']; ?>
                  <?php if ($product['value']): ?>
                    <span class="products-item-volume"><?= $product['value']; ?></span>
                  <?php endif; ?>
                </p>
                <?php if ($product['date']): ?>
                  <span class="products-item-time">
                    <?= $product['date']; ?>
                  </span>
                <?php endif; ?>
                <div class="products-item-controls">
                  <button class="products-item-btn-decr" title="уменьшить">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="24" height="24" rx="4" fill="#F7F7FA" />
                      <path
                        d="M5.33325 12.0001C5.33325 11.5398 6.84945 11.1667 7.48622 11.1667H18.5136C19.1504 11.1667 19.6666 11.5398 19.6666 12.0001C19.6666 12.4603 19.1504 12.8334 18.5136 12.8334H7.48622C6.84945 12.8334 5.33325 12.4603 5.33325 12.0001Z"
                        fill="#5B5C70" />
                    </svg>
                  </button>
                  <span class="products-item-quantity">0 шт.</span>
                  <button class="products-item-btn-incr" title="прибавить">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="24" height="24" rx="4" fill="#F7F7FA" />
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12.0001 6.16675C12.4603 6.16675 12.8334 6.53984 12.8334 7.00008V11.1667H17.0001C17.4603 11.1667 17.8334 11.5398 17.8334 12.0001C17.8334 12.4603 17.4603 12.8334 17.0001 12.8334H12.8334V17.0001C12.8334 17.4603 12.4603 17.8334 12.0001 17.8334C11.5398 17.8334 11.1667 17.4603 11.1667 17.0001V12.8334H7.00008C6.53984 12.8334 6.16675 12.4603 6.16675 12.0001C6.16675 11.5398 6.53984 11.1667 7.00008 11.1667H11.1667V7.00008C11.1667 6.53984 11.5398 6.16675 12.0001 6.16675Z"
                        fill="#5B5C70" />
                    </svg>
                  </button>
                </div>
              </div>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>