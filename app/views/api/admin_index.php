<div class="breadcrumb">
    <ul>
        <li class="item"><strong><a href="<?= HTML::link('admin1259') ?>">DASHBOARD</a></strong></li>
        <li class="item"><a href="<?= HTML::link('admin1259/users') ?>">Accès API</a></li>
        <li class="item current">Liste des accès API</li>
    </ul>
</div>
<h3 class="page_title">Accès API</h3>
<div class="page_content">
    <table class="table">
        <thead>
            <tr>
                <th>Controller</th>
                <th>Nom</th>
                <th>Method</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="4"><strong>Users</strong></td>
            </tr>
            <tr>
                <td>Créer utilisateur</td>
                <td>POST</td>
                <td><a href="<?= HTML::link('api/users/create') ?>"><?= HTML::link('api/users/create') ?></a></td>
            </tr>
            <tr>
                <td>Authentification utilisateur</td>
                <td>GET</td>
                <td><a href="<?= HTML::link('api/users/auth') ?>"><?= HTML::link('api/users/auth') ?></a></td>
            </tr>
            <tr>
                <td>Authentification utilisateur: token existant</td>
                <td>GET</td>
                <td><a href="<?= HTML::link('api/users/auth/' . $token) ?>"><?= HTML::link('api/users/auth/' . $token) ?></a></td>
            </tr>
            <tr>
                <td rowspan="3"><strong>Events</strong></td>
            </tr>
            <tr>
                <td>Liste événements</td>
                <td>GET</td>
                <td><a href="<?= HTML::link('api/events') ?>"><?= HTML::link('api/events') ?></a></td>
            </tr>
            <tr>
                <td>Evénement spécifique par ID</td>
                <td>GET</td>
                <td><a href="<?= HTML::link('api/events/get/1') ?>"><?= HTML::link('api/events/get/1') ?></a></td>
            </tr>
        </tbody>
    </table>
</div>