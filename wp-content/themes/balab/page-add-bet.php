<?php
/*
Template Name: Best_add
*/
get_header();
the_post();
?>
<div class="container">
    <div class="row">
        <div class="col-12 mt-2">
            <h1 class="text-center mb-5"><?php the_title(); ?></h1>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div id="bet-container" class="col col-lg-6">
			<?php if (is_user_logged_in() && !is_feed()):?>		
			<form id="addbets_ajax">
				<div class="form-group">
				  <label for="titlebets">Заголовок</label>
				  <input type="text" name="titlebets" class="form-control" id="titlebets" placeholder="Введите название ставки">
				</div>
				<div class="form-group">
				  <label for="deskbets">Описание</label>
				  <textarea name="deskbets" class="form-control" id="deskbets" rows="3" placeholder="Введите описание"></textarea>
				</div>
				<div class="form-group">
					<label for="Select1Bidtype">Тип ставки</label>
					<select name="Select1Bidtype" class="form-control" id="Select1Bidtype">
					  <?php echo getBidtypeList(); ?>
					</select>
				</div>
				<button type="submit" class="btn btn-primary">Отправить</button>
			</form>
			<?php else:?>
			<div class="alert alert-warning" role="alert">
				Добавление ставок доступно только авторизованным пользователям.
			</div>
			<?php endif;?>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 text-center mt-5">
            <a href="/" class="btn btn-primary">Назад к списку</a>
        </div>
    </div>
</div>
<?php
get_footer();
