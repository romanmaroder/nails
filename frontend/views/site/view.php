<?php

/* @var $master \frontend\controllers\SiteController */

/* @var $images\frontend\controllers\SiteController */

/* @var $certificat\frontend\controllers\SiteController */

use yii\bootstrap4\Carousel;

//echo '<pre>';
//print_r($certificate);
//print_r($master);
//die();

$this->title                   = $master['user']['username'];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3">
				<!-- Profile Image -->
				<div class="card card-primary card-outline">
					<div class="card-body box-profile">
						<div class="text-center">
							<img class="profile-user-img img-fluid img-circle"
								 src="<?php
                                 echo $master->user->getPicture(); ?>"
								 alt="User profile picture"
                                 title="User profile picture">
						</div>

						<h3 class="profile-username text-center"><?php
                            echo $master->user->username; ?></h3>

						<p class="text-muted text-center"><?php
                            echo implode(' ', $master->user->getRoles('description')); ?></p>

						<!--<ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Followers</b> <a class="float-right">1,322</a>
                            </li>
                            <li class="list-group-item">
                                <b>Following</b> <a class="float-right">543</a>
                            </li>
                            <li class="list-group-item">
                                <b>Friends</b> <a class="float-right">13,287</a>
                            </li>
                        </ul>

                        <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
				<!-- About Me Box -->
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">О себе</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
                        <?php
                        if ($master->education) : ?>
							<strong><i class="fas fa-book mr-1"></i> Образование</strong>
							<p class="text-muted">
                                <?php
                                echo $master->education; ?>
							</p>
							<hr>
                        <?php
                        endif; ?>

                        <?php
                        if ($master->user->address) : ?>
							<strong><i class="fas fa-map-marker-alt mr-1"></i> Место проживание</strong>
							<p class="text-muted"><?php
                                echo $master->user->address; ?></p>
                        <?php
                        endif; ?>
                        <?php
                        if ($master->skill) : ?>
							<hr>

							<strong><i class="fas fa-pencil-alt mr-1"></i> Навыки и умения</strong>

							<p class="text-muted">
								<span class="tag tag-info"><?php
                                    echo $master->skill; ?></span>
							</p>
                        <?php
                        endif; ?>
                        <?php
                        if ($master->notes) : ?>
							<hr>
							<strong><i class="far fa-file-alt mr-1"></i> Заметки</strong>
							<p class="text-muted"><?php
                                echo $master->notes; ?></p>
                        <?php
                        endif; ?>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
			<div class="col-md-9">
				<div class="card">
					<div class="card-header p-2">
						<ul class="nav nav-pills">
							<li class="nav-item"><a class="nav-link active" href="#works"
													data-toggle="tab">Работы</a></li>
							<li class="nav-item"><a class="nav-link" href="#certificate"
													data-toggle="tab">Сертификаты</a></li>
						</ul>
					</div><!-- /.card-header -->
					<div class="card-body">
						<div class="tab-content">
							<div class="active tab-pane" id="works">
								<?php if (!empty($images)) :?>
									<!-- Works -->
									<div class="col-md-6">
                                        <?php  echo Carousel::widget(
                                            [
                                                'items' => $images,
                                            ]
                                        ); ?>
									</div>
									<!-- /.works -->
								<?php else: ?>
								<div class="col-md-6">
									<p class="text-muted"> Мастер еще не добавил свои работы</p>
								</div>
								<?php endif ;?>


							</div>
							<!-- /.tab-pane -->
							<div class="tab-pane" id="certificate">
								<?php if (!empty($certificat)) :?>
									<!-- The certificate -->
									<div class="col-md-6">
                                        <?php
                                        echo Carousel::widget(
                                            [
                                                'items' => $certificat,
                                            ]
                                        ); ?>
									</div>
									<!-- /.certificate -->
							<?php else: ?>
								<div class="col-md-6">
									<p class="text-muted">
										Мастер еще не добавил сертификаты
									</p>
								</div>
								<?php endif; ?>

							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div><!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
