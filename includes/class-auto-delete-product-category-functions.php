<?php

if (!defined('ABSPATH')) {
    exit;
}

class Auto_Delete_Product_Category_Functions
{
    public function __construct()
    {
        add_action('init', [$this, 'maybe_schedule_event']);
        add_action('remove_old_new_in_products', [$this, 'remove_old_new_in_products_function']);
    }

    public function maybe_schedule_event()
    {
        $cron_period = get_option('_cron_period');

        switch ($cron_period) {
            case '_cron_hourly':
                $cron_value = 'hourly';
                break;
            case '_cron_twicedaily':
                $cron_value = 'twicedaily';
                break;
            case '_cron_daily':
                $cron_value = 'daily';
                break;
            default:
                return;
        }

        $timestamp = wp_next_scheduled('remove_old_new_in_products');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'remove_old_new_in_products');
        }

        wp_schedule_event(time(), $cron_value, 'remove_old_new_in_products');
    }

    private function remove_old_new_in_products_function()
    {
        $period       = (int) get_option('_adpc_period');
        $period_type  = get_option('_hours_days_months');
        $category_id  = (int) get_option('_adpc_wc_cat');
        $batch_size   = (int) get_option('_batch_size');
        $paged        = 1;

        if (!$period || !$category_id || !$batch_size || !$period_type) {
            return;
        }

        $term = get_term_by('id', $category_id, 'product_cat');
        if (!$term || is_wp_error($term)) {
            return;
        }

        switch ($period_type) {
            case '_adpc_hours':
                $period_in_seconds = HOUR_IN_SECONDS * $period;
                break;
            case '_adpc_days':
                $period_in_seconds = DAY_IN_SECONDS * $period;
                break;
            case '_adpc_months':
                $period_in_seconds = DAY_IN_SECONDS * 30 * $period;
                break;
            default:
                return;
        }

        do {
            $args = [
                'post_type'      => 'product',
                'posts_per_page' => $batch_size,
                'paged'          => $paged,
                'post_status'    => 'any',
                'tax_query'      => [
                    [
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $term->slug,
                    ],
                ],
            ];

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $product_id = get_the_ID();

                    $publish_timestamp = get_post_time('U', false, $product_id);
                    $current_timestamp = current_time('timestamp');

                    if (($current_timestamp - $publish_timestamp) > $period_in_seconds) {
                        if (has_term($term->term_id, 'product_cat', $product_id)) {
                            wp_remove_object_terms($product_id, $term->term_id, 'product_cat');
                        }
                    }
                }
                wp_reset_postdata();
            }

            $paged++;
        } while ($query->max_num_pages >= $paged);
    }
}

new Auto_Delete_Product_Category_Functions();
