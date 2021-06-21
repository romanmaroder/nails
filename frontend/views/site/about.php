<?php
/* @var $this yii\web\View */

/* @var $master \frontend\controllers\SiteController */
/* @var $path\frontend\controllers\SiteController */

/*echo '<pre>';
var_dump($path);
die()*/

use yii\helpers\Html;

;
?>
<div class="row mt-4">
    <?php
    foreach ($master as $item) : ?>
		<div class="col-md-4">
			<!-- Widget: user widget style 1 -->
			<div class="card card-widget widget-user">
				<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="widget-user-header text-white"
					 style="background: url(<?php
                     echo $path; ?>) center center;">
					<h3 class="widget-user-username text-right"><?php
                        echo $item->username; ?></h3>
					<h5 class="widget-user-desc text-right">
                        <?php
                        echo implode(' ', $item->getRoles('description')); ?>
					</h5>

				</div>
                <?php
                echo Html::a(
                    '<img class="img-circle" src="'.$item->getPicture().'" alt="User Avatar">',
                    ['/site/view', 'id' => $item->id],
                    ['class' => 'widget-user-image']
                ); ?>

				<div class="card-footer">
					<div class="row justify-content-center">
                        <?php
                        if ($item->getCountCertificate($item->id)) : ?>

							<div class="col-sm-4 ">
								<div class="description-block ">
									<h5 class="description-header"><?php
                                        echo $item->getCountCertificate($item->id); ?></h5>
									<span class="description-text">СЕРТИФИКАТЫ</span>
								</div>
								<!-- /.description-block -->
							</div>
                        <?php
                        endif; ?>
						<!-- /.col -->
                        <?php
                        if ($item->getCountWorkMaster($item->id)) : ?>

							<div class="col-sm-4 ">
								<div class="description-block">
									<h5 class="description-header"><?php
                                        echo $item->getCountWorkMaster($item->id); ?></h5>
									<span class="description-text">РАБОТ</span>
								</div>
								<!-- /.description-block -->
							</div>
                        <?php
                        endif; ?>
						<!-- /.col -->
						<div class="col-sm-4">
							<div class="description-block">
								<h5 class="description-header">35</h5>
								<span class="description-text">PRODUCTS</span>
							</div>
							<!-- /.description-block -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div>
			</div>
			<!-- /.widget-user -->
		</div>
    <?php
    endforeach; ?>

	<!-- /.col -->
</div>