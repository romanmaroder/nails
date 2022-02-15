<?php
/* @var $countEventTotal string */
/* @var $user  */

use common\models\User;
use hail812\adminlte\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="/" class="brand-link"
	   title="На сайт"
	   data-toggle="tooltip"
	   data-placement="auto">
		<?php echo Html::img('/img/AdminLTELogo.png',['alt'=>'AdminLTE Logo','class'=>'brand-image
		img-circle elevation-3','style'=>'opacity: .8']) ;?>
		<span class="brand-text font-weight-light"><?php
            echo Yii::$app->name; ?></span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<?php
                echo Html::img($user->getPicture(),['class'=>'img-circle
                                                elevation-2','alt'=>'User
                                                Image']) ;?>
			</div>
			<div class="info">
                <?php echo Html::a($user->username . ' (<span>'. Yii::$app->user->identity->role->description
                .'</span>)',Url::to(['/profile/account/index']), ['class'=>'d-block']) ;?>
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
            echo Menu::widget(
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
                            'badge' => '<span class="right badge badge-info">'.$countEventTotal.'</span>',
                            'iconClassAdded' => 'text-warning',
                            'url'   => ['/calendar/event/index'],
                        ],
                        [
                            'label' => 'Клиенты',
                            'icon'  => 'fas fa-address-book',
                            'badge' => '<span class="right badge badge-info">'.User::getUserTotalCount().'</span>',
                            'iconClassAdded' => 'text-primary',
                            'items' => [
                                [
                                    'label' => 'Список',
                                    'icon'  => 'fas fa-user-friends',
                                    'iconClassAdded' => 'text-secondary',
                                    'url'
                                            => ['/client/client/index'],
                                ],
                                [
                                    'label' => 'Новый',
                                    'icon'  => 'fas fa-user-plus',
                                    'iconClassAdded' => 'text-secondary',
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
                        [
                            'label' => 'Мастера',
                            'icon'  => 'fas fa-users',
                            'iconClassAdded' => 'text-primary',
                            'items' => [
                                [
                                    'label' => 'Список мастеров',
                                    'icon'  => 'fas fa-user-tag',
                                    'iconClassAdded' => 'text-secondary',
                                    'url'
                                            => ['/employees/master/index'],
                                ],
                                [
                                    'label' => 'Ставка мастера',
                                    'icon'  => 'fas fa-percent',
                                    'iconClassAdded' => 'text-secondary',
                                    'url'   => ['/service-user/index'],
                                ],
                            ]
                        ],
                        [
                            'label' => 'Блог',
                            'icon'  => 'far fa-newspaper',
                            /*'badge' => '<span class="right badge badge-info">'.User::getUserTotalCount().'</span>',*/
                            'iconClassAdded' => 'text-primary',
                            'items' => [
                                [
                                    'label' => 'Категории',
                                    'icon'  => 'fas fa-stream',
                                    'iconClassAdded' => 'text-info',
                                    'items' => [
                                        [
                                            'label' => 'Список',
                                            'icon'  => 'fas fa-list-ol',
                                            'iconClassAdded' => 'text-secondary',
                                            'url' => ['/category/index'],
                                        ],
                                        [
                                            'label' => 'Добавить',
                                            'icon'  => 'far fa-plus-square',
                                            'iconClassAdded' => 'text-secondary',
                                            'url'   => ['/category/create'],
                                        ],
									]
                                ],
                                [
                                    'label' => 'Статьи',
                                    'icon'  => 'far fa-newspaper',
                                    'iconClassAdded' => 'text-info',
                                    'items' => [
                                        [
                                            'label' => 'Список',
                                            'icon'  => 'fas fa-list-ol',
                                            'iconClassAdded' => 'text-secondary',
                                            'url' => ['/blog/post/index'],
                                        ],
                                        [
                                            'label' => 'Добавить статью',
                                            'icon'  => 'far fa-plus-square',
                                            'iconClassAdded' => 'text-secondary',
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
                        [
                            'label' => 'Услуги',
                            'icon'  => 'fas fa-wrench',
                            'iconClassAdded' => 'text-danger',
                            'items' => [
                                [
                                    'label' => 'Список услуг',
                                    'icon'  => 'fas fa-list',
                                    'iconClassAdded' => 'text-secondary',
                                    'url'
                                            => ['/service/index'],
                                ],
                                [
                                    'label' => 'Новая услуга',
                                    'icon'  => 'far fa-plus-square',
                                    'iconClassAdded' => 'text-secondary',
                                    'url'   => ['/service/create'],
                                ],
                            ]
                        ],
                        [
                            'label' => 'Расходы',
                            'icon'  => 'fas fa-dollar-sign',
                            'iconClassAdded' => 'text-success',
                            'items' => [
                                [
                                    'label' => 'Категории расходов',
                                    'icon'  => 'fas fa-list',
                                     'iconClassAdded' => 'text-secondary',
                                    'url'
                                            => ['/expenses/index'],
                                ],
                                [
                                    'label' => 'Добавить категорию',
                                    'icon'  => 'far fa-plus-square',
                                     'iconClassAdded' => 'text-secondary',
                                    'url'   => ['/expenses/create'],
                                ],
                                [
                                    'label' => 'Список затрат',
                                    'icon'  => 'fas fa-list',
                                     'iconClassAdded' => 'text-secondary',
                                    'url'
                                            => ['/expenseslist/index'],
                                ],
                                [
                                    'label' => 'Добавить затраты',
                                    'icon'  => 'far fa-plus-square',
                                    'iconClassAdded' => 'text-secondary',
                                    'url'   => ['/expenseslist/create'],
                                ],
                            ]
                        ],
                        //['label' => 'Ставки', 'icon' => 'fas fa-percent', 'url' => ['/service-user/index'],'iconClassAdded'=>'text-info'],
                        ['label' => 'Статистика', 'icon' => 'fas fa-chart-pie', 'url' => ['/statistic'],'iconClassAdded' =>'text-info'],
                        ['label' => 'Заметки', 'icon' => 'fa fa-list-ul', 'url' => ['/todo/todo/index'],'iconClassAdded' =>'text-info'],
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