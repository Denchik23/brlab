<?php

add_action( 'init', 'best_register_post_type_init' ); // Использовать функцию только внутри хука init
 
function best_register_post_type_init() {
	$labels = array(
		'name' => 'Cтавки',
		'singular_name' => 'Cтавки на спорт', // админ панель Добавить->Функцию
		'add_new' => 'Добавить ставку',
		'add_new_item' => 'Добавить новую ставки', // заголовок тега <title>
		'edit_item' => 'Редактировать ставку',
		'new_item' => 'Новая ставка',
		'all_items' => 'Все ставки',
		'view_item' => 'Просмотр ставок на сайте',
		'search_items' => 'Искать ставку',
		'not_found' =>  'Ставок не найдено.',
		'not_found_in_trash' => 'В корзине нет ставкок.',
		'menu_name' => 'Cтавки' // ссылка в меню в админке
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true, // показывать интерфейс в админке
		'has_archive' => true, 
		'menu_icon' => 'dashicons-image-filter', // иконка в меню
		'menu_position' => 20, // порядок в меню
		'supports' => array( 'title', 'editor', 'comments', 'author', 'custom-fields'),
                'capability_type' => 'best',
                'map_meta_cap' => true,
		'taxonomies' => array('bidtype')
	);
	register_post_type('best', $args);
}


// хук для регистрации
add_action( 'init', 'create_taxonomy_bidtype' );
function create_taxonomy_bidtype() {

    // список параметров:
    register_taxonomy( 'bidtype', [ 'best' ], [ 
        'label'                 => '', // определяется параметром $labels->name
        'labels'                => [
            'name'              => 'Тип ставки',
            'singular_name'     => 'Genre',
            'search_items'      => 'Search ставки',
            'all_items'         => 'All ставки',
            'view_item '        => 'View ставки',
            'parent_item'       => 'Parent ставки',
            'parent_item_colon' => 'Parent ставки:',
            'edit_item'         => 'Edit ставки',
            'update_item'       => 'Update ставки',
            'add_new_item'      => 'Add New ставки',
            'new_item_name'     => 'New Genre Name',
            'menu_name'         => 'Тип ставки',
        ],
        'description'           => '', // описание таксономии
        'public'                => true,
        // 'publicly_queryable'    => null, // равен аргументу public
        // 'show_in_nav_menus'     => true, // равен аргументу public
        // 'show_ui'               => true, // равен аргументу public
        // 'show_in_menu'          => true, // равен аргументу show_ui
        // 'show_tagcloud'         => true, // равен аргументу show_ui
        // 'show_in_quick_edit'    => null, // равен аргументу show_ui
        'hierarchical'          => false,

        'rewrite'               => true,
        //'query_var'             => $taxonomy, // название параметра запроса
        'capabilities'          => array('assign_terms' => 'edit_bests'),
        'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
        'show_in_rest'          => null, // добавить в REST API
        //'rest_base'             => null, // $taxonomy
        // '_builtin'              => false,
        //'update_count_callback' => '_update_post_term_count',
    ] );
}

