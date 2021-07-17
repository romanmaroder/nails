<?php

/* @var $count  \backend\controllers\SiteController*/

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">

	<?php echo '<pre>; ';var_dump($count) ;?>
	<div class="row">
		<div class="col-md-4">
			<!-- Widget: user widget style 2 -->
			<div class="card card-widget widget-user-2">
				<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="widget-user-header bg-warning">
					<div class="widget-user-image">
						<img class="img-circle elevation-2" src="../dist/img/user7-128x128.jpg" alt="User Avatar">
					</div>
					<!-- /.widget-user-image -->
					<h3 class="widget-user-username">Nadia Carmichael</h3>
					<h5 class="widget-user-desc">Lead Developer</h5>
				</div>
				<div class="card-footer p-0">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a href="#" class="nav-link">
								Projects <span class="float-right badge bg-primary">31</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link">
								Tasks <span class="float-right badge bg-info">5</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link">
								Completed Projects <span class="float-right badge bg-success">12</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link">
								Followers <span class="float-right badge bg-danger">842</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- /.widget-user -->
		</div>
		<!-- /.col -->
	</div>

    <!--<div class="row">
        <div class="col-lg-6">
            <?/*= \hail812\adminlte\widgets\Alert::widget([
                'type' => 'success',
                'body' => '<h3>Congratulations!</h3>',
            ]) */?>
            <?/*= \hail812\adminlte\widgets\Callout::widget([
                'type' => 'danger',
                'head' => 'I am a danger callout!',
                'body' => 'There is a problem that we need to fix. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.'
            ]) */?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?/*= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Messages',
                'number' => '1,410',
                'icon' => 'far fa-envelope',
            ]) */?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?/*= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Bookmarks',
                'number' => '410',
                 'theme' => 'success',
                'icon' => 'far fa-flag',
            ]) */?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?/*= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Uploads',
                'number' => '13,648',
                'theme' => 'gradient-warning',
                'icon' => 'far fa-copy',
            ]) */?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?/*= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Bookmarks',
                'number' => '41,410',
                'icon' => 'far fa-bookmark',
                'progress' => [
                    'width' => '70%',
                    'description' => '70% Increase in 30 Days'
                ]
            ]) */?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?php /*$infoBox = \hail812\adminlte\widgets\InfoBox::begin([
                'text' => 'Likes',
                'number' => '41,410',
                'theme' => 'success',
                'icon' => 'far fa-thumbs-up',
                'progress' => [
                    'width' => '70%',
                    'description' => '70% Increase in 30 Days'
                ]
            ]) */?>
            <?/*= \hail812\adminlte\widgets\Ribbon::widget([
                'id' => $infoBox->id.'-ribbon',
                'text' => 'Ribbon',
            ]) */?>
            <?php /*\hail812\adminlte\widgets\InfoBox::end() */?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?/*= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Events',
                'number' => '41,410',
                'theme' => 'gradient-warning',
                'icon' => 'far fa-calendar-alt',
                'progress' => [
                    'width' => '70%',
                    'description' => '70% Increase in 30 Days'
                ],
                'loadingStyle' => true
            ]) */?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?/*= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => '150',
                'text' => 'New Orders',
                'icon' => 'fas fa-shopping-cart',
            ]) */?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php /*$smallBox = \hail812\adminlte\widgets\SmallBox::begin([
                'title' => '150',
                'text' => 'New Orders',
                'icon' => 'fas fa-shopping-cart',
                'theme' => 'success'
            ]) */?>
            <?/*= \hail812\adminlte\widgets\Ribbon::widget([
                'id' => $smallBox->id.'-ribbon',
                'text' => 'Ribbon',
                'theme' => 'warning',
                'size' => 'lg',
                'textSize' => 'lg'
            ]) */?>
            <?php /*\hail812\adminlte\widgets\SmallBox::end() */?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?/*= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => '44',
                'text' => 'User Registrations',
                'icon' => 'fas fa-user-plus',
                'theme' => 'gradient-success',
                'loadingStyle' => true
            ]) */?>
        </div>
    </div>-->
</div>