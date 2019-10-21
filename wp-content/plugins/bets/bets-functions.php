<?php

add_action( 'init', 'best_register_post_type_init' );

/*
 * Регистрируем новый тип постов
 */
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

/*
 * Регистрируем таксономию
 */
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
            'add_new_item'      => 'Add New ставку',
            'new_item_name'     => 'New Genre Name',
            'menu_name'         => 'Тип ставки',
        ],
        'description'           => '', 
        'public'                => true,
        'hierarchical'          => false,
        'rewrite'               => true,
        'capabilities'          => array('assign_terms' => 'edit_bests'),
        'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
        'show_in_rest'          => null, // добавить в REST API
    ] );
}

// Подключаем локализацию в самом конце подключаемых к выводу скриптов, чтобы скрипт
// к которому мы подключаемся, точно был добавлен в очередь на вывод.
add_action( 'wp_enqueue_scripts', 'btajax_data', 99 );
function btajax_data(){
    wp_localize_script( 'main.min', 'btajax', 
        array(
            'url' => admin_url('admin-ajax.php')
        )
    );  

}

if( wp_doing_ajax() ){
    add_action( 'wp_ajax_best_action', 'best_action_callback' );
    add_action('wp_ajax_nopriv_best_action', 'best_action_callback');
	
	//Только для авторизованных
    add_action('wp_ajax_bestadd_action', 'bestadd_action_callback');
}

/*
 * Запись данных в post meta bet_vote
 */
function best_action_callback() {
    
    $backArray = array();
    $bet_vote = (isset($_POST['betvote'])? $_POST['betvote']: 0);
    $bet_id =  (isset($_POST['betid'])? $_POST['betid']: 0);
    
    if (!is_numeric($bet_vote) || $bet_vote == 0) {
        $backArray['success'] = false;
        $backArray['html'] = '<div class="alert alert-danger" role="alert">Введено не верное значение!</div>';
    } elseif (empty($bet_id) || !is_numeric($bet_id) || $bet_id == "") {
        $backArray['success'] = false;
        $backArray['html'] = '<div class="alert alert-danger" role="alert">Не верный id поста!</div>';
    } else {
        $intBet_vote = (int) $bet_vote;
        if ($intBet_vote >= 100 && $intBet_vote <= 1000) {
            $outadd = update_post_meta($bet_id , 'bet_vote', $bet_vote);
            if ($outadd === true) {
                $backArray['success'] = true;
                $backArray['html'] = '<div class="alert alert-success" role="alert">Запись в post meta bet_vote обновлена!</div>';
            } elseif($outadd === false) {
                $backArray['success'] = false;
                $backArray['html'] = '<div class="alert alert-danger" role="alert">Запись не добавлена или передано такое же значение поля!</div>';
            } else {
                $backArray['success'] = true;
                $backArray['html'] = '<div class="alert alert-success" role="alert">Запись в post meta bet_vote - '.$outadd.' добавлена!</div>';
            }
        } else {
            $backArray['success'] = false;
            $backArray['html'] = '<div class="alert alert-danger" role="alert">Введите значения от 100 до 1000!</div>';
        }  
    }
    
    echo json_encode($backArray);
    
    wp_die();
}

/*
 * добавление ставки ajax
 */
function bestadd_action_callback() {
    
    $backArray = array();
    $title = (isset($_POST['title'])? $_POST['title']: "");
    $deskbets =  (isset($_POST['deskbets'])? $_POST['deskbets']: "");
    $select1Bidtype =  (isset($_POST['select1Bidtype'])? $_POST['select1Bidtype']: "");
	
	$cur_user_id = get_current_user_id();
    
    if ($title == "") {
        $backArray['success'] = false;
        $backArray['html'] = '<div class="alert alert-danger" role="alert">Введите название ставки</div>';
    } elseif (empty($select1Bidtype) || $select1Bidtype == "") {
        $backArray['success'] = false;
        $backArray['html'] = '<div class="alert alert-danger" role="alert">Выберите тип ставки</div>';
	} elseif($cur_user_id == 0) {
		$backArray['success'] = false;
        $backArray['html'] = '<div class="alert alert-danger" role="alert">Не верный '.$cur_user_id.' пользователя</div>';
	} else {
        $post_data = array(
			'post_title'    => wp_strip_all_tags($title),
			'post_content'  => $deskbets,
			'post_type'		=> 'best',
			'post_status'   => 'publish',
			'post_author'   => $cur_user_id,
			'tax_input'		=>  array('bidtype' => array($select1Bidtype)),
		);
		//Добавляем фильтр
		$post_data = apply_filters('filter_add_best', $post_data);
		$best_id = wp_insert_post($post_data, false);
		if ($best_id == 0) {
			$backArray['success'] = false;
			$backArray['html'] = '<div class="alert alert-danger" role="alert">Ошибка при записи</div>';
		} else {
			$backArray['success'] = true;
			$backArray['html'] = '<div class="alert alert-success" role="alert">Ставка с id  - '.$best_id.' добавлена!</div>';
			//Хук действий после записи поста
			do_action('afte_add_best', $best_id);
		}  
    }
    
    echo json_encode($backArray);
	
    wp_die();
}

add_action('wp_footer', 'best_action_javascript', 99); // для фронта
function best_action_javascript() {
    ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {
        var data = {
                action: 'best_action',
                betvote: 0,
                betid: 0
        };
        var bet_buttom = document.getElementById('add_bet_vote');
        if (bet_buttom != null) {
            bet_buttom.onclick = function() {
                var bet_container = document.querySelector("#bet-container");
                var bet_input = bet_container.querySelector(".value_vote");
                var bet_vote = bet_input.value;
                var bet_id = this.getAttribute('bet_id');

                if (bet_vote ===  "" || isNaN(bet_vote)) {
                    alert('Введите значения от 100 до 1000');
                    bet_input.focus();
                    return;
                }
                let intBet_vote = Number(bet_vote);
                if (!(intBet_vote >= 100 && intBet_vote <= 1000)) {
                    alert('Введите значения от 100 до 1000');
                    bet_input.focus();
                    return;
                }
                data.betvote = bet_vote;
                data.betid = bet_id;
                //ajax
                //console.log(data);
                jQuery.post( btajax.url, data, function(response) {
                    //удаляем все alerts
                    clearAlert();
                    if (response.success) {
                        //Делаем кнопку недоступной
                        bet_buttom.setAttribute('disabled',false);
                    }
                    bet_container.insertAdjacentHTML('afterbegin', response.html);

                },'json');
            };
        }
		var bet_addForm = document.getElementById('addbets_ajax');
        if (bet_addForm != null) {
			bet_addForm.addEventListener("submit", function(event){
				event.preventDefault();    //stop form from submitting
				var bet_container = document.querySelector("#bet-container");
				
				let titlebets = this.querySelector("#titlebets");
				let deskbets = this.querySelector("#deskbets");
				let select1Bidtype = this.querySelector("#Select1Bidtype");
				
				if (titlebets.value ===  "") {
                    alert('Введите название ставки');
                    titlebets.focus();
                    return;
                }
				if (select1Bidtype.value ===  "0") {
                    alert('Выберите тип ставки');
                    select1Bidtype.focus();
                    return;
                }
				
				data.action = 'bestadd_action';
                data.title = titlebets.value;
                data.deskbets = deskbets.value;
                data.select1Bidtype = select1Bidtype.value;
				
				//ajax
                //console.log(data);
                jQuery.post( btajax.url, data, function(response) {
                    //удаляем все alerts
					clearAlert();
					bet_container.insertAdjacentHTML('afterbegin', response.html);
                },'json');
			});
		} 
		
		function clearAlert() {
			let alertDiv = document.querySelector('.alert');
            if (alertDiv !== null) alertDiv.remove();
		}
        
    });
    </script>
    <?php
}