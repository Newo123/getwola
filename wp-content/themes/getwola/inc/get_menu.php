<?php

/**
 * Функция для получения меню 
 * 
 * @param $menu_name - slug меню
 */
function get_menu($menu_name)
{
  $locations = get_nav_menu_locations();
  $menu_items = wp_get_nav_menu_items($locations[$menu_name]);


  foreach ($menu_items as $i => $item) {
    if (!empty($item->menu_item_parent) && $item->menu_item_parent > 0) {
      foreach ($menu_items as $j => $parent_item) {
        if ($parent_item->ID == $item->menu_item_parent) {
          if (empty($menu_items[$j]->children)) {
            $menu_items[$j]->children = [];
          }
          $menu_items[$j]->children[] = $item;
          unset($menu_items[$i]);
          break;
        }
      }
    }
  }
  if (is_array($menu_items)) {
    $menu_items = array_values($menu_items);
  } else {
    $menu_items = [];
  }

  return $menu_items;
}