<?php
namespace Core;

use Core\Helper;
use Core\User;

class Controller
{
    public $app;
    public $auth = false;
    public $layout = "main";
    public $layoutFile;
    public $view;
    public $viewFile;
    public $js_header;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function middleware()
    {
        return [];
    }

    public function getLayout()
    {
        $layoutFile = APP_DIR . '/views/layouts/' . $this->layout . '.php';
        if (file_exists($layoutFile)) {
            $this->layoutFile = $layoutFile;
        } else {
            $error_message = 'file layout not found in ' . $layoutFile;
            require ERROR_PAGE;
            exit;
        }
    }

    public function getView($view)
    {
        $viewFile = APP_DIR . '/views/' . $this->app->controller['id'] . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            $this->viewFile = $viewFile;
        } else {
            $viewFile = APP_DIR . '/views/default/' . $view . '.php';
            if (file_exists($viewFile)) {
                $this->viewFile = $viewFile;
            } else {
                $error_message = 'file view not found in ' . $viewFile;
                require ERROR_PAGE;
                exit;
            }
        }
    }

    public function redirect($target, $params = [])
    {
        $redirect = BASE_URL;
        if (strpos('http', $target) > 0) {

        } else {
            // ['site/index']
            if (strpos($target, '/') > 0) {
                $redirect .= '/' . $target;
            }
            // ['index']
            else {
                $redirect .= $this->app->controller['id'] . '/' . $target;
            }
            if (!empty($params)) {
                $redirect .= '?' . http_build_query($params);
            }

        }
        header('Location: ' . $redirect); /* Redirect browser */
        exit;
    }

    public function render($object)
    {
        if (is_array($object)) {
            $this->getView($object[0]);
            ob_start();
            if (!empty($object[1])) {
                foreach ($object[1] as $num => $var) {
                    ${$num} = $var;
                }
            }
            $controller = $this;
            require_once $this->viewFile;
            $content = ob_get_contents();
            ob_end_clean();
            $js_header = $this->js_header;
            $this->getLayout();
            require_once $this->layoutFile;
        } else {
            if (is_string($object)) {
                echo $object;
            } else {
                require APP_CORE . '/404.php';
                exit;
            }
        }
    }

    public function actionLogin()
    {
        if (!empty($_POST)) {
            $username = Helper::safeText($_POST['username']);
            $password = $_POST['password'];
            $login = User::login($this->app->connection, $username, $password);
            if ($login->status) {
                $this->setMessage('success', $login->message);
                $this->redirect('site/index');
            } else {
                $this->setMessage('danger', $login->message);
            }
        }

        $this->render([
            'login',
        ]);
    }

    public function actionLogout()
    {
        User::logout();
        $this->setMessage('success', 'Logout success');
        $this->redirect('site/index');
    }

    public function setMessage($status, $text)
    {
        $message = [
            'status' => $status,
            'text' => $text,
        ];
        $_SESSION['message'] = $message;
    }

    public function getMessage()
    {
        $return = '';
        if (!empty($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $return = '<div class="notification is-' . $message['status'] . '">' .
                $message['text']
                . '</div>';
            unset($_SESSION['message']);
        }
        return $return;
    }

    public function getUser()
    {
        return User::get();
    }
}
