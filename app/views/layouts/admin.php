<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= self::$title ?> | EasyKit</title>
        <link rel="stylesheet" href="<?= HTML::link('admin/stylesheets/screen.css'); ?>">
        <script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
    </head>
    <body>
        <header class="head">
            <div class="title">
                <a href="<?= HTML::link('admin1259') ?>"><img src="<?= HTML::link('admin/images/logo.png'); ?>" alt="" width="247" height="70"></a>
            </div>
            <div class="user right">
                <a href="<?= HTML::link('admin1259/users/logout') ?>"><span class="fa fa-power-off"></span> Déconnexion</a>
            </div>
        </header>
        <aside class="sidebar">
            <nav class="menu">
                <h4>General</h4>
                <ul>
                    <li class="item">
                        <a href="<?= HTML::link('admin1259') ?>"><span class="fa fa-home"></span>Dashboard</a>
                    </li>
                    <li class="item">
                        <a href="#" data-subitem><span class="fa fa-heart"></span>Evénements</a>
                        <ul class="subitem">
                            <li class="item"><a href="<?= HTML::link('admin1259/events') ?>"><span class="fa fa-list"></span>Liste des événements</a></li>
                            <li class="item"><a href="<?= HTML::link('admin1259/events/create') ?>"><span class="fa fa-plus"></span>Ajouter un événement</a></li>
                        </ul>
                    </li>
                    <li class="item">
                        <a href="<?= HTML::link('admin1259/packs') ?>"><span class="fa fa-cube"></span>Packs</a>
                    </li>
                    <li class="item">
                        <a href="#" data-subitem><span class="fa fa-user"></span>Utilisateurs</a>
                        <ul class="subitem">
                            <li class="item"><a href="<?= HTML::link('admin1259/users') ?>"><span class="fa fa-list"></span>Liste des utilisateurs</a></li>
                            <li class="item"><a href="<?= HTML::link('admin1259/users/create') ?>"><span class="fa fa-plus"></span>Ajouter un utilisateur</a></li>
                        </ul>
                    </li>
                </ul>
                <h4>Administration</h4>
                <ul>
                    <li class="item">
                        <a href="#" data-subitem><span class="fa fa-envelope"></span>Messages<span class="label label-success">15</span></a>
                        <ul class="subitem">
                            <li class="item"><a href="#"><span class="fa fa-list"></span>Inbox<span class="label label-success">15</span></a></li>
                            <li class="item"><a href="#"><span class="fa fa-send"></span>Ecrire un message</a></li>
                        </ul>
                    </li>
                    <li class="item">
                        <a href="<?= HTML::link('admin1259/api') ?>"><span class="fa fa-flask"></span>Accès API</a>
                    </li>
                </ul>
            </nav>
        </aside>
        <section class="content">
            <?= $content_for_layout; ?>
        </section>
        <script src="<?= HTML::link('admin/scripts/menu.js'); ?>"></script>
    </body>
</html>