<?php
require get_template_directory() . '/inc/get_menu.php';

$menu_items = get_menu('header_menu');

$contacts = get_field('contacts', 'options');
?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <header class="header container">
    <button id="open-menu-btn" class="header-burger-btn">
      <svg width="32" height="32" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M3.5 6C3.5 5.44772 3.94772 5 4.5 5H20.5C21.0523 5 21.5 5.44772 21.5 6C21.5 6.55228 21.0523 7 20.5 7H4.5C3.94772 7 3.5 6.55228 3.5 6Z"
          fill="#E0E0E5"></path>
        <path
          d="M3.5 12C3.5 11.4477 3.94772 11 4.5 11H20.5C21.0523 11 21.5 11.4477 21.5 12C21.5 12.5523 21.0523 13 20.5 13H4.5C3.94772 13 3.5 12.5523 3.5 12Z"
          fill="#E0E0E5"></path>
        <path
          d="M3.5 18C3.5 17.4477 3.94772 17 4.5 17H20.5C21.0523 17 21.5 17.4477 21.5 18C21.5 18.5523 21.0523 19 20.5 19H4.5C3.94772 19 3.5 18.5523 3.5 18Z"
          fill="#E0E0E5"></path>
      </svg>
    </button>
    <a href="https://getwola.ru/">
      <img src="<?= get_template_directory_uri(); ?>/assets/images/logo.svg" alt="getwola" />
    </a>

    <?php if ($menu_items): ?>
      <nav id="nav-menu" class="header-nav">
        <div class="header-nav-close">
          <button id="close-menu-btn" class="header-nav-btn">
            <svg width="20" height="20" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg"
              style="color: rgb(247, 247, 250); margin-left: auto">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M6.91058 4.91107C7.23602 4.58563 7.76366 4.58563 8.08909 4.91107L13.0891 9.91107C13.4145 10.2365 13.4145 10.7641 13.0891 11.0896L8.08909 16.0896C7.76366 16.415 7.23602 16.415 6.91058 16.0896C6.58514 15.7641 6.58514 15.2365 6.91058 14.9111L11.3213 10.5003L6.91058 6.08958C6.58514 5.76414 6.58514 5.23651 6.91058 4.91107Z"
                fill="currentColor"></path>
            </svg>
          </button>
          <h2 class="header-nav-title">Меню</h2>
        </div>
        <ul class="header-nav-list">
          <?php foreach ($menu_items as $index => $item): ?>
            <li>
              <a class="<?= (strpos($item->url, 'blog') !== false) ? 'active' : ''; ?>" href="<?= $item->url; ?>">
                <?= $item->title; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    <?php endif; ?>
    <nav>
      <ul class="header-nav-links">
        <?php if ($contacts['max']): ?>
          <li>
            <a href="<?= $contacts['max']['url']; ?>" rel="noopener noreferrer nofollow" target="_blank"><svg width="29"
                height="29" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M8 16C12.4183 16 16 12.4183 16 8C16 3.58172 12.4183 0 8 0C3.58172 0 0 3.58172 0 8C0 12.4183 3.58172 16 8 16Z"
                  fill="#373737"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M8.15915 5.46756C6.85088 5.39869 5.82996 6.30998 5.60483 7.73631C5.41839 8.91748 5.74894 10.3567 6.03167 10.4292C6.15159 10.4599 6.44017 10.2383 6.65012 10.0396C6.68959 10.0022 6.74928 9.99594 6.79557 10.0244C7.12285 10.2253 7.4934 10.3762 7.90192 10.3977C9.24501 10.4684 10.4352 9.41323 10.5056 8.06486C10.5759 6.7165 9.50224 5.53827 8.15915 5.46756ZM5.96836 12.3151C5.91771 12.2791 5.84792 12.2889 5.80547 12.3343C5.23807 12.9418 3.78594 13.368 3.71942 12.5388C3.71942 11.8892 3.57407 11.3417 3.41403 10.739C3.21803 10.0008 3 9.17965 3 7.9862C3 5.14027 5.32507 3 8.08183 3C10.8386 3 13 5.24477 13 8.01384C13 10.7829 10.7698 12.9724 8.10791 12.9724C7.16355 12.9724 6.70527 12.8389 5.96836 12.3151Z"
                  fill="#E8E8E8"></path>
              </svg>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($contacts['telegram']): ?>
          <li>
            <a href="<?= $contacts['telegram']['url']; ?>" rel="opener noreferrer nofollow" target="_blank"><svg
                width="36" height="36" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M16 28.8002C23.0692 28.8002 28.7999 23.0694 28.7999 16.0002C28.7999 8.93095 23.0692 3.2002 16 3.2002C8.93071 3.2002 3.19995 8.93095 3.19995 16.0002C3.19995 23.0694 8.93071 28.8002 16 28.8002Z"
                  fill="#373737"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M8.99443 15.865C12.7259 14.2393 15.2141 13.1675 16.4591 12.6497C20.0138 11.1712 20.7524 10.9143 21.2339 10.9059C21.3397 10.904 21.5765 10.9302 21.7299 11.0547C21.8594 11.1597 21.895 11.3017 21.912 11.4013C21.9291 11.5009 21.9503 11.7279 21.9334 11.9052C21.7408 13.9292 20.9073 18.8409 20.4833 21.1077C20.3038 22.067 19.9505 22.3886 19.6085 22.42C18.8652 22.4885 18.3007 21.9288 17.5808 21.4569C16.4542 20.7184 15.8178 20.2587 14.7243 19.5381C13.4605 18.7053 14.2797 18.2476 15 17.4995C15.1884 17.3037 18.4635 14.3248 18.5269 14.0546C18.5348 14.0208 18.5422 13.8948 18.4674 13.8283C18.3925 13.7617 18.282 13.7845 18.2023 13.8026C18.0893 13.8282 16.2896 15.0178 12.8032 17.3712C12.2924 17.722 11.8297 17.8929 11.4151 17.8839C10.9581 17.8741 10.079 17.6255 9.42541 17.4131C8.62381 17.1525 7.98671 17.0148 8.04219 16.5722C8.07108 16.3417 8.38849 16.106 8.99443 15.865Z"
                  fill="white"></path>
              </svg>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($contacts['phone']): ?>

          <li>
            <a href="<?= $contacts['phone']['url']; ?>"><svg width="22" height="22" viewBox="0 0 16 16" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M0.809417 6.37763C2.67144 10.4355 5.9847 13.6617 10.104 15.4107L10.7643 15.7051C12.275 16.3786 14.0509 15.8669 14.9723 14.4927L15.8352 13.2058C16.1157 12.7874 16.0303 12.2238 15.6384 11.9075L12.712 9.54524C12.2822 9.19827 11.6501 9.27909 11.3213 9.72305L10.416 10.9454C8.09303 9.79881 6.20705 7.91172 5.06116 5.58737L6.28274 4.68155C6.72645 4.35254 6.80722 3.72011 6.46045 3.29002L4.09952 0.361814C3.78341 -0.0302555 3.22035 -0.115777 2.80219 0.164769L1.50717 1.03362C0.125177 1.96082 -0.383354 3.75243 0.305269 5.26804L0.808659 6.37598L0.809417 6.37763Z"
                  fill="#E0E0E5"></path>
              </svg>
            </a>
          </li>
        <?php endif; ?>

        <li>
          <a href="https://getwola.ru/profile"><svg width="32" height="32" viewBox="0 0 24 25" fill="currentColor"
              xmlns="http://www.w3.org/2000/svg" style="color: rgb(255, 255, 255)">
              <path opacity="0.3"
                d="M12 12.0294C9.92893 12.0294 8.25 10.3439 8.25 8.26471C8.25 6.18552 9.92893 4.5 12 4.5C14.0711 4.5 15.75 6.18552 15.75 8.26471C15.75 10.3439 14.0711 12.0294 12 12.0294Z">
              </path>
              <path
                d="M4.50054 19.7464C4.82355 15.2545 8.05159 12.9707 11.9861 12.9707C15.976 12.9707 19.2541 15.129 19.4983 19.7472C19.508 19.9311 19.4983 20.5001 18.8723 20.5001C15.7843 20.5001 11.1956 20.5001 5.10625 20.5001C4.89726 20.5001 4.48295 19.9911 4.50054 19.7464Z">
              </path>
            </svg>
          </a>
        </li>

      </ul>
    </nav>
  </header>