<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://helloeveryone.me/
 * @since      1.0.0
 *
 * @package    Auto_Delete_Product_Category
 * @subpackage Auto_Delete_Product_Category/admin/partials
 * 
 */

function render_product_category_select()
{
    $args = array(
        'taxonomy'     => 'product_cat',
        'hide_empty'   => false,
        'orderby'      => 'name',
        'hierarchical' => true,
    );

    $categories = get_terms($args);
    $selected   = get_option('_adpc_wc_cat', '');
    if (empty($categories) || is_wp_error($categories)) {
        return;
    }
    echo '<select class="full-width" name="_adpc_wc_cat">';
    echo '<option value="">Seleccionar categoría</option>';
    render_category_options($categories, 0, 0, $selected);
    echo '</select>';
}

function render_category_options($categories, $parent = 0, $depth = 0, $selected = '')
{
    foreach ($categories as $category) {
        if ($category->parent != $parent) {
            continue;
        }
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $depth);
        $is_selected = selected($selected, $category->term_id, false);
        echo '<option value="' . esc_attr($category->term_id) . '" ' . $is_selected . '>' . $indent . esc_html($category->name) . '</option>';
        render_category_options($categories, $category->term_id, $depth + 1, $selected);
    }
}

$period             = get_option('_adpc_period', '');
$period_type        = get_option('_hours_days_months', '');
$category_id        = get_option('_adpc_wc_cat', '');
$batch_size         = get_option('_batch_size', '');
$cron_period        = get_option('_cron_period', '');   

?>

<div class=" flex-column gap-s bg-dark-grey-2 text-white padding-l">
    <img class="max-width-10" src="https://helloeveryone.me/wp-content/uploads/HelloEveryone-Logo-300x79.webp" alt="Logo Hello Everyone">
    <address class="text-xs flex-row items-middle gap-xs">
        Desarrollado por&nbsp;<a class="text-white transition-global" href="https://helloeveryone.me" rel="noreferrer" target="_blank">Hello Everyone</a>
    </address>
</div>

<?php
if (!defined('DISABLE_WP_CRON') || DISABLE_WP_CRON === false) { ?>
    <div class="notice notice-warning margin-left-zero margin-top-s">
        <p><strong>El CRON de WordPress no está habilitado.</strong></p>
        <p>Para poder utiliar el plugin es necesario que actives el CRON de WordPress modificando el archivo <code>wp-config</code>, cambiando la constante <code>DISABLE_WP_CRON</code> a <code>true</code></p>
    </div>
<?php } ?>

<div class="padding-l text-black">
    <h1 class="text-l font-700"> Opciones de <span class="underline">Auto Delete Product Category</span></h1>
    <p class="margin-top-s text-s">Si quieres que algunos productos solo permanezcan en una categoría solo despues de un cierto tiempo en el que fueron publicados, aquí puedes configurar los parametros para lograr ese resultado.</p>

    <hr class="margin-vertical-m">
    <?php if (class_exists('WooCommerce')) { ?>
        <form id="_hide_rest_api_form" method="post" action="options.php" class="columns-2 max-width-60 gap-s">
            <div class="flex-column gap-4xs">
                <label for="_adpc_period">Perído</label>
                <input style="max-width: 25rem;" step=".01" type="number" name="_adpc_period" id="_adpc_period" placeholder="Ingrese el período en días, meses u horas" value="<?php echo esc_attr($period); ?>">
            </div>
            <div class="flex-column gap-4xs">
                <label for="_days_or_months">Selecciona el tipo de período</label>
                <select name="_hours_days_months" id="_hours_days_months" class="full-width">
                    <option value="">Seleccionar un período</option>
                    <option value="_adpc_hours" <?php selected($period_type, '_adpc_hours'); ?>>Horas</option>
                    <option value="_adpc_days" <?php selected($period_type, '_adpc_days'); ?>>Días</option>
                    <option value="_adpc_months" <?php selected($period_type, '_adpc_months'); ?>>Meses</option>
                </select>
            </div>
            <div class="flex-column gap-4xs">
                <label for="_adpc_wc_cat">Selecciona la categoría del producto</label>
                <?php render_product_category_select(); ?>
            </div>
            <div class="flex-column gap-4xs">
                <label for="_cron_period">Ejecución del cron (valores por defecto de WordPress)</label>
                <select name="_cron_period" id="_cron_period" class="full-width">
                    <option>Seleccionar ejecución del cron</option>
                    <option value="_cron_hourly" <?php selected($cron_period, '_cron_hourly'); ?>>Por hora</option>
                    <option value="_cron_twicedaily" <?php selected($cron_period, '_cron_twicedaily'); ?>>Dos veces al día</option>
                    <option value="_cron_daily" <?php selected($cron_period, '_cron_daily'); ?>>Por día</option>
                </select>
            </div>
            <hr class="span-2 full-width">
            <div class="flex-column gap-4xs span-2">
                <label for="_batch_size">Baches</label>
                <input style="max-width: 25rem;" type="number" name="_batch_size" id="_batch_size" placeholder="Ingrese un valor para los baches" step="1" value="<?php echo esc_attr($batch_size); ?>">
                <div class="notice notice-warning margin-left-zero margin-top-s">
                    <p>El plugin recorrerá todos los productos, les quitará la categoría elegida y lo hará en baches para evitar sobrecargar el servidor. Coloca un valor que se ajuste a tus recursos.</p>
                </div>
            </div>
            <?php
            settings_fields('adpc_options_group');
            do_settings_sections('auto-delete-product-category');
            submit_button();
            ?>
        </form>

    <?php } else { ?>
        <div class="notice notice-warning margin-left-zero margin-top-s">
            <p><strong>Debes activar Woocommerce para que puedas utilizar este plugin</strong></p>
        </div>
    <?php
    }
    ?>
</div>

<style>
    #wpcontent {
        padding-left: 0;
    }
</style>