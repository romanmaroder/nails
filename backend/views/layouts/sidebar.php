<?php

use common\models\Event as EventAlias;
use common\models\User;

/*@var $assetDir backend\views\layouts */
//$assetDir                      = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="/" class="brand-link"
	   title="На сайт"
	   data-toggle="tooltip"
	   data-placement="auto">
		<img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
			 style="opacity: .8">
		<span class="brand-text font-weight-light"><?php
            echo Yii::$app->name; ?></span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?php
                echo
				$user->getPicture();
				?>"
					 class="img-circle
				elevation-2"
					 alt="User
				Image">
			</div>
			<div class="info">
				<a href="/admin/profile/account/" class="d-block"><?php
                    echo $user->username; ?>
					(<span><?php
                        echo Yii::$app->user->identity->role->description ?></span>)
				</a>
			</div>
		</div>

		<!-- SidebarSearch Form -->
		<!-- href be escaped -->
		<!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

		<!-- Sidebar Menu -->
		<nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget(
                [
                    'options' => [
                        'class'          => 'nav nav-pills nav-sidebar flex-column nav-child-indent',
                        'data-widget'    => 'treeview',
                        'role'           => 'menu',
                        'data-accordion' => 'false'
                    ],
                    'items'   => [
                        /*[
                            'label' => 'Starter Pages',
                            'icon'  => 'tachometer-alt',
                            'badge' => '<span class="right badge badge-info">2</span>',
                            'items' => [
                                ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                                ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                            ]
                        ],*/
                        /*[
                            'label' => 'Simple Link',
                            'icon'  => 'th',
                            'badge' => '<span class="right badge badge-danger">New</span>'
                        ],*/
                        /*['label' => 'Yii2 PROVIDED', 'header' => true],
                        [
                            'label'   => 'Login',
                            'url'     => ['site/login'],
                            'icon'    => 'sign-in-alt',
                            'visible' => Yii::$app->user->isGuest
                        ],*/
                        /*['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                        ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],*/
                        ['label' => 'Меню', 'header' => true],
                        [
                            'label' => 'Календарь',
                            'icon'  => 'calendar-alt',
                            'badge' => '<span class="right badge badge-info">'.EventAlias::countEventTotal().'</span>',
                            'iconClassAdded' => 'text-warning',
                            'url'   => ['/event/index'],
                        ],
                        [
                            'label' => 'Клиенты',
                            'icon'  => 'fas fa-users',
                            'badge' => '<span class="right badge badge-info">'.User::getUserTotalCount().'</span>',
                            'iconClassAdded' => 'text-danger',
                            'items' => [
                                [
                                    'label' => 'Список',
                                    'icon'  => 'fas fa-user-friends',
                                    'url'
                                            => ['/client/client/index'],
                                ],
                                [
                                    'label' => 'Новый',
                                    'icon'  => 'fas fa-user-plus',
                                    'url'   => ['/client/client/create'],
                                ],
                                /*[
                                    'label'     => 'Level2',
                                    'iconStyle' => 'far',
                                    'items'     => [
                                        ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                        ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                        ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                                    ]
                                ],
                                ['label' => 'Level2', 'iconStyle' => 'far']*/
                            ]
                        ],
                        ['label' => 'Мастера', 'icon' => 'fas fa-user-tag', 'url' => ['/employees/master/index'],'iconClassAdded' => 'text-info'],
                        [
                            'label' => 'Блог',
                            'icon'  => 'far fa-newspaper',
                            /*'badge' => '<span class="right badge badge-info">'.User::getUserTotalCount().'</span>',*/
                            'iconClassAdded' => 'text-info',
                            'items' => [
                                [
                                    'label' => 'Категории',
                                    'icon'  => 'fas fa-stream',
                                    'items' => [
                                        [
                                            'label' => 'Список',
                                            'icon'  => 'fas fa-list-ol',
                                            'url' => ['/category/index'],
                                        ],
                                        [
                                            'label' => 'Добавить',
                                            'icon'  => 'far fa-plus-square',
                                            'url'   => ['/category/create'],
                                        ],
									]
                                ],
                                [
                                    'label' => 'Статьи',
                                    'icon'  => 'far fa-newspaper',
                                    'items' => [
                                        [
                                            'label' => 'Список',
                                            'icon'  => 'fas fa-list-ol',
                                            'url' => ['/blog/post/index'],
                                        ],
                                        [
                                            'label' => 'Добавить статью',
                                            'icon'  => 'far fa-plus-square',
                                            'url'   => ['/blog/post/create'],
                                        ],
                                    ]
                                ],

                                /*[
                                    'label'     => 'Level2',
                                    'iconStyle' => 'far',
                                    'items'     => [
                                        ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                        ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                        ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                                    ]
                                ],
                                ['label' => 'Level2', 'iconStyle' => 'far']*/
                            ]
                        ],

                        /*['label' => 'LABELS', 'header' => true],
                        ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
                        ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
                        ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],*/
                    ],
                ]
            );
            ?>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>