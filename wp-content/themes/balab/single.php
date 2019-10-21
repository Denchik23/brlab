<?php
get_header();
the_post();
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-5">Играть ставку</h1>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col col-lg-5">
            <div class="card">
                <div class="card-body" id="bet-container">
                    <h4 class="card-title"><?php the_title(); ?></h4>
                    <h5 class="card-subtitle mb-2 text-muted">Тип ставки: <span class="badge badge-secondary"><?=getbets_bidtype($post);?></span></h5>
                    <p class="card-text"><?php the_content(); ?></p>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control value_vote" placeholder="значения от 100 до 1000" aria-describedby="button-add_bet_vote">
                      <div class="input-group-append">
                          <button class="btn btn-outline-secondary " bet_id="<?php the_ID(); ?>" type="button" id="add_bet_vote">Ставка пройдет!</button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mt-5">
            <a href="/" class="btn btn-primary">Назад к списку</a>
        </div>
    </div>
</div>
<?php
get_footer();
