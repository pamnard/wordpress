<?php
/**
 * Plugin Name: Fixed Dashboard Columns
 * Description: Фиксирует количество колонок на дашборде WordPress.
 * Version: 1.0
 * Author: Pamnard
 */

// Фиксируем количество колонок на дашборде
function fixed_dashboard_columns( $columns ) {
    $columns['dashboard'] = 3; // Устанавливаем количество колонок (например, 3)
    return $columns;
}
add_filter( 'screen_layout_columns', 'fixed_dashboard_columns' );

// Устанавливаем количество колонок для текущего пользователя
function set_dashboard_layout() {
    add_filter( 'get_user_option_screen_layout_dashboard', function() { return 3; } ); // Устанавливаем 3 колонки
}
add_action( 'wp_dashboard_setup', 'set_dashboard_layout' );
