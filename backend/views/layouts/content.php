<?php
/* @var $content string */

use yii\bootstrap4\Breadcrumbs;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
				<div class="col-sm-6 col-md-12">
                    <?php
                    echo Breadcrumbs::widget([
                                                 'links' => $this->params['breadcrumbs'] ?? [],
                                                 'activeItemTemplate'=>"<!--noindex--><li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n<!--/noindex-->",
                                                 'options' => [
                                                     'class' => 'breadcrumb float-md-right'
                                                 ]
                                             ]);
                    ?>
				</div><!-- /.col -->
                <!--<div class="col-sm-6">
                    <h3 class="m-0">
                        <?php
/*                        if (!is_null($this->title)) {
                            echo \yii\helpers\Html::encode($this->title);
                        }
                        */?>
                    </h3>
                </div>-->
				<!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>