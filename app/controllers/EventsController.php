<?php

/**
 * Created by PhpStorm.
 * User: H3yden
 * Date: 10/12/2014
 * Time: 11:36
 */

namespace App\Controllers;

use App\Models\Admin;
use Core;
use Core\Controller;
use Core\Session;
use Core\Validation;
use Core\View;
use Core\Cookie;
use Core\Exceptions\NotFoundHTTPException;
use Exception;
use HTML;

/**
 * Events Controller
 *
 * @property mixed Events
 * @property mixed Users
 * @property mixed Events_medias
 */
class EventsController extends AppController
{

    /**
     * Datas for model
     *
     * @var string $name
     * @var string $description
     * @var string $starttime
     * @var string $endtime
     * @var float $price
     * @var int $user
     * @var string $token
     */
    private $name, $description, $address, $starttime, $endtime, $price, $user, $token;

    /**
     * Errors
     *
     * @var array $errors
     */
    private $errors = [];

    /**
     * Fields
     *
     * @var array $fields
     */
    private $fields = [];

    /**
     * Get Name
     *
     * @return mixed
     */
    private function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param mixed $name
     */
    private function setName($name)
    {
        $this->name = $name;
        $this->fields['name'] = $this->name;
    }

    /**
     * Get Description
     *
     * @return mixed
     */
    private function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Description
     *
     * @param mixed $description
     */
    private function setDescription($description)
    {
        $this->description = $description;
        $this->fields['description'] = $this->description;
    }

    /**
     * Get Start time
     *
     * @return mixed
     */
    private function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set Start time
     *
     * @param mixed $starttime
     */
    private function setStarttime($starttime)
    {
        if (Validation::validateDate($starttime, 'Y-m-d H:i')) {
            $this->starttime = $starttime;
            $this->fields['starttime'] = $this->starttime;
        } else {
            $this->errors['starttime'] = 'Not a datetime.';
        }
    }

    /**
     * Get End time
     *
     * @return mixed
     */
    private function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set End time
     *
     * @param mixed $endtime
     */
    private function setEndtime($endtime)
    {
        if (Validation::validateDate($endtime, 'Y-m-d H:i')) {
            $this->endtime = $endtime;
            $this->fields['endtime'] = $this->endtime;
        } else {
            $this->errors['endtime'] = 'Not a datetime.';
        }
    }

    /**
     * Get Price
     *
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set Price
     *
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
        $this->fields['price'] = $this->price;
    }

    /**
     * Get Address
     *
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set Address
     *
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get User ID
     *
     * @return mixed
     */
    private function getUser()
    {
        return $this->user;
    }

    /**
     * Set User ID
     *
     * @param mixed $user
     */
    private function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get Token
     *
     * @return mixed
     */
    private function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token
     *
     * @param $token
     */
    private function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Constructor
     *
     * @return void
     */
    function constructor()
    {
        if (isset($_SESSION['admin']) && $this->getPrefix() == 'admin') {
            $admin = Session::get('admin');
            if (!$this->getJSON($this->link('admin1259/is_admin/' . $admin->admin_username . '/' . $admin->admin_password))->admin) {
                if ($this->getPrefix() != false && $this->getPrefix() == 'admin') {
                    throw new NotFoundHTTPException('Non authorized address.');
                }
            }
        } else if ($this->getPrefix() != false && $this->getPrefix() == 'admin') {
            throw new NotFoundHTTPException('Non authorized address.');
        }
    }

    /**
     * API Get Action
     *
     * @param null $id
     *
     * @throws Exception
     * @throws NotFoundHTTPException
     */
    function api_get($id = null)
    {
        $this->loadModel('Events');

        if ($id != null) {
            $data['event'] = current($this->Events->select(array(
                'conditions'    => array(
                    'id'            => $id,
                )
            )));

            if (!empty($data['event'])) {
                $data['event']->user = current($this->getJSON($this->link('api/users/get/' . $data['event']->events_users_id)));

                $data['event']->events_medias = $this->Events->medias(array(
                    'conditions'     => array(
                        'id'            => $id,
                    ),
                ));

                $data['event']->events_like = current($this->getJSON($this->link('api/likes/get/' . $data['event']->events_id)));

                $data['event']->events_summary = HTML::summary($data['event']->events_description, 150);

                foreach ($data['event']->events_medias as $media) {
                    $mediafile = $this->getJSON($this->link('api/medias/get/' . $media->medias_id));
                    if ($mediafile->success) {
                        $mediafile = current($mediafile);
                        $media->medias_file = $mediafile->medias_file;
                        $media->medias_thumb50 = $mediafile->medias_thumb50;
                        $media->medias_thumb160 = $mediafile->medias_thumb160;
                    } else {
                        $this->errors['media' . $media->medias_id] = 'Media is missing.';
                    }
                }
            }
        } else {
            $nb = isset($_GET['limit']) && $_GET['limit'] != null ? $_GET['limit'] : 20;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $page = (($page - 1) * $nb);

            $req = [
                'order'     => 'desc',
                'orderby'   => 'id',
                'limit'     => array($page, $page + $nb),
            ];

            if (isset($_GET['search'])) {
                $req['likeor'] = [
                    'name'          => $_GET['search'],
                    'city'          => $_GET['search'],
                    'country'       => $_GET['search'],
                    'address'       => $_GET['search']
                ];
            }

            $data['events'] = $this->Events->select($req);

            if (!empty($data['events'])) {
                foreach ($data['events'] as $event) {
                    $event->user = current($this->getJSON($this->link('api/users/get/' . $event->events_users_id)));

                    $event->events_medias = $this->Events->medias(array(
                        'conditions'     => array(
                            'id'            => $event->events_id,
                        ),
                    ));

                    $event->events_like = current($this->getJSON($this->link('api/likes/get/' . $event->events_id)));

                    $event->events_summary = HTML::summary($event->events_description, 150);

                    foreach ($event->events_medias as $media) {
                        $mediafile = $this->getJSON($this->link('api/medias/get/' . $media->medias_id));
                        if ($mediafile->success) {
                            $mediafile = current($mediafile);
                            $media->medias_file = $mediafile->medias_file;
                            $media->medias_thumb50 = $mediafile->medias_thumb50;
                            $media->medias_thumb160 = $mediafile->medias_thumb160;
                        } else {
                            throw new Exception('Media file ' . $media->medias_id . ' is missing.');
                        }
                    }
                }
            }
        }

        View::make('api.index', json_encode($data), false, 'application/json');
    }

    /**
     * API Create
     *
     * @throws NotFoundHTTPException
     * @throws \Exception
     */
    function api_create()
    {
        if (!empty($_POST)) {
            if (isset($_POST['name']) && $_POST['name'] != null) {
                $this->setName($_POST['name']);
            } else {
                $this->errors['name'] = 'Empty name.';
            }

            if (isset($_POST['description']) && $_POST['description'] != null) {
                $this->setDescription($_POST['description']);
            }

            if (isset($_POST['address']) && $_POST['address'] != null) {
                $this->setAddress($_POST['address']);
            }

            if (isset($_POST['starttime']) && $_POST['starttime'] != null) {
                $this->setStarttime($_POST['starttime']);
            } else {
                $this->errors['starttime'] = 'Empty start time.';
            }

            if (isset($_POST['endtime']) && $_POST['endtime'] != null) {
                $this->setEndtime($_POST['endtime']);
            } else {
                $this->errors['endtime'] = 'Empty endtime.';
            }

            if (isset($_POST['price']) && !is_null($_POST['price'])) {
                $this->setPrice($_POST['price']);
            }

            if (isset($_POST['token']) && $_POST['token'] != null) {
                $this->setToken($_POST['token']);
            } else {
                $this->errors['token'] = 'Empty token.';
            }

            if (empty($this->errors)) {
                $this->loadModel('Events');
                $this->loadModel('Users');

                $user = $this->getJSON($this->link('api/users/checkToken/' . $this->getToken() . '/' . $_SERVER['REMOTE_ADDR']));
                if ($user->valid) {
                    $user_id = $user->user->tokens_users_id;
                } else {
                    $this->errors['user'] = $user->errors;
                }

                if (empty($this->errors)) {
                    $this->Events->save([
                        'name'          => $this->getName(),
                        'description'   => $this->getDescription(),
                        'address'       => $this->getAddress(),
                        'starttime'     => $this->getStarttime(),
                        'endtime'       => $this->getEndtime(),
                        'price'         => $this->getPrice(),
                        'users_id'      => $user_id
                    ]);

                    $data['event'] = $this->Events->lastInsertId;
                }
            }
        } else {
            $this->errors['POST'] = 'No POST received.';
        }

        $data['success'] = !empty($this->errors) ? false : true;
        $data['errors'] = $this->errors;

        View::make('api.index', json_encode($data), false, 'application/json');
    }

    /**
     * API Edit
     *
     * @param null $id
     * @throws NotFoundHTTPException
     * @throws \Exception
     */
    function api_edit($id = null)
    {
        if ($id != null) {
            if (!empty($_POST)) {
                if (isset($_POST['name']) && $_POST['name'] != null) {
                    $this->setName($_POST['name']);
                }

                if (isset($_POST['description']) && $_POST['description'] != null) {
                    $this->setDescription($_POST['description']);
                }

                if (isset($_POST['starttime']) && $_POST['starttime'] != null) {
                    $this->setStarttime($_POST['starttime']);
                }

                if (isset($_POST['endtime']) && $_POST['endtime'] != null) {
                    $this->setEndtime($_POST['endtime']);
                }

                if (isset($_POST['price']) && !is_null($_POST['price'])) {
                    $this->setPrice($_POST['price']);
                }

                if (isset($_POST['token']) && $_POST['token'] != null) {
                    $this->setToken($_POST['token']);
                } else {
                    $this->errors['token'] = 'No token given.';
                }

                if (empty($this->errors)) {
                    $this->loadModel('Events');
                    $user = $this->getJSON($this->link('api/users/checkToken/' . $this->getToken() . '/' . $_SERVER['REMOTE_ADDR']));
                    if ($user->valid) {
                        $user_id = $user->user->tokens_users_id;
                    } else {
                        $this->errors['user'] = $user->errors;
                    }

                    $event = $this->Events->select([
                        'conditions'    => [
                            'id'            => $id
                        ]
                    ]);

                    if (current($event)->events_users_id != 1 && current($event)->events_users_id != $user_id) {
                        $this->errors['user'] = 'You\'re not the owner of this event.';
                    } else {
                        $this->fields['users_id'] = $user_id;
                    }

                    if (empty($this->errors) && count($event) == 1) {
                        $this->fields['id'] = $id;
                        $this->Events->save($this->fields);

                        $data['event'] = $id;
                    } else {
                        $this->errors['event'] = 'This event doesn\'t exists.';
                    }
                }
            } else {
                $this->errors['POST'] = 'No POST received.';
            }
        } else {
            $this->errors['id'] = 'No id given.';
        }

        $data['success'] = !empty($this->errors) ? false : true;
        $data['errors'] = $this->errors;

        View::make('api.index', json_encode($data), false, 'application/json');
    }

    /**
     * Index
     *
     * @throws NotFoundHTTPException
     */
    function index()
    {
        View::$title = 'All events';
        View::make('events.index', null, 'default');
    }

    /**
     * Show
     *
     * @param null $id
     *
     * @throws NotFoundHTTPException
     */
    function show($id = null)
    {
        $return = current($this->getJSON($this->link('api/events/get/' . $id)));
        if ($return != false) {
            $data = $return;
            View::$title = $return->events_name;
        } else {
            throw new NotFoundHTTPException('This event doesn\'t exists.');
        }

        View::make('events.show', $data, 'default');
    }

    /**
     * Create
     *
     * @throws NotFoundHTTPException
     */
    function create()
    {
        if (!empty($_POST)) {
            $post['name'] = $_POST['name'];
            $post['description'] = $_POST['description'];
            $post['starttime'] = $_POST['starttime'];
            $post['endtime'] = $_POST['endtime'];
            $post['token'] = $_POST['token'];
            if (!empty($_POST['id'])) {
                $post['users_id'] = $_POST['users_id'];
                $return = $this->postCURL($this->link('api/events/create'), $post);

                $created = $return->success;
            } else {
                $created = true;
            }

            if ($created) {
                $return = $this->postCURL($this->link('api/packs/create'), $post);

                if (!$return->success) {
                    $this->errors = $return->errors;
                }
            } else {
                $this->errors = $return->errors;
            }

        } else {
            $this->errors['post'] = 'No POST received.';
        }

        $data['success'] = empty($this->errors) ? true : false;
        $data['errors'] = $this->errors;

        View::make('api.index', json_encode($data), false, 'application/json');
    }

    function api_image($id)
    {
        $return = json_decode($this->postCURL($this->link('api/medias/send'), [], $_FILES), true);

        if ($return['success']) {
            foreach ($return['upload'] as $upload) {
                if ($upload['success']) {
                    $this->loadModel('Events_medias');
                    $this->Events_medias->save([
                        'events_id' => $id,
                        'medias_id' => $upload['medias_id']
                    ]);
                } else {
                    $this->errors = $return['errors'];
                }
            }
        } else {
            $this->errors = $return['errors'];
        }

        $data['success'] = empty($this->errors) ? true : false;
        $data['errors'] = $this->errors;

        View::make('api.index', json_encode($data), false, 'application/json');
    }

    /**
     * Admin Index Action
     *
     * @return void
     */
    function admin_index()
    {
        View::$title = 'Événements';

        $this->loadModel('Events');

        $data['count'] = $this->Events->select(array('count' => true));

        $data['events'] = current($this->getJSON($this->link('api/events')));

        View::make('events.admin_index', $data, 'admin');
    }

    /**
     * Admin show
     *
     * @throws NotFoundHTTPException
     */
    function admin_show($id = null)
    {
        $this->loadModel('Events');

        if ($id != null) {
            $data['event'] = current($this->getJSON($this->link('api/events/get/' . $id)));
            if (!empty($data['event'])) {
                View::$title = $data['event']->events_name;
                View::make('events.admin_show', $data, 'admin');
            } else {
                throw new NotFoundHTTPException('This event doesn\'t exists.', 1, 'admin');
            }
        } else {
            throw new NotFoundHTTPException('You haven\'t specified any id.', 1, 'admin');
        }
    }

    /**
     * Admin create
     *
     * @throws NotFoundHTTPException
     */
    function admin_create()
    {
        View::$title = 'Création d\'un événement';
        View::make('events.admin_create', null, 'admin');
    }

    /**
     * Admin store
     */
    function admin_store() {
        if (!empty($_POST)) {
            $return = json_decode($this->postCURL($this->link('api/events/create'), $_POST), false);
            if (empty($return->errors)) {
                $this->redirect('admin1259/events/edit/' . $return->event);
            } else {
                Session::setFlash('danger', 'L\'événement n\'a pas pu être créé.');
                $this->redirect('admin1259/events/create');
            }
        }
    }

    /**
     * Admin Count Action
     *
     * @return void
     */
    function admin_count()
    {
        $this->loadModel('Events');

        $data['count'] = $this->Events->select(array('count' => true));

        View::make('api.index', json_encode($data), false, 'application/json');
    }

    /**
     * Delete an event
     *
     * @param int $id
     * @throws NotFoundHTTPException
     * @throws \Exception
     */
    function admin_delete($id = null)
    {
        if ($id != null) {
            $this->loadModel('Events');
            $this->Events->delete(['id' => $id]);
        } else {
            $this->errors['id'] = 'No id given.';
        }

        $data['success'] = empty($this->errors) ? true : false;
        $data['errors'] = $this->errors;

        View::make('api.index', json_encode($data), false, 'application/json');
    }
}