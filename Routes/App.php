<?php

namespace Routes;
use Routes\Exceptions\DensoExceptions;

class App
{

    private static $renderinfo;
    private static $content;
    private static $type = 'full';
    private static $viewData;
    public static function init(): void
    {
        $path = static::getPath(isset($_GET['path']) ? $_GET['path'] : '');
        static::$renderinfo = new Router($path);
        static::$content = static::$renderinfo->view();
        $renderview = true;
        if(static::$type == 'wrapper'){
            static::$viewData .= static::defaults('header');
            static::$viewData .= static::defaults('sidebar');
            static::$viewData .= static::$content;
            static::$viewData .= static::defaults('footer');
        }else{
            if (static::$type == 'json') {
                $renderview = false;
                self::jsonHeader();
            }elseif(static::$type == 'stream'){
                $renderview = false;
            }
            static::$viewData = static::$content;
        }
        if (true === $renderview) {
            static::render('index', static::$viewData);
        }else{
            echo static::$content;
        }
    }

    public static function render($view, $closure)
    {
        try {
            $path = PATH_FRONT . '/View/' . $view . '.php';
            if (static::$type != 'json') {
                //validamos si la vista existe
                static::validateView($path);
            }
            if (is_object($closure)) {
                $data = get_object_vars($closure);
            }
            if (!empty($data) && is_array($data)) {
                extract($data);
            }
            if (file_exists($path)) {
                include $path;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private static function validateView($path)
    {
        if (!file_exists($path)) {
            //throw new Exception(sprintf("La vista %s no existe", $path));
            throw new DensoExceptions("404");
        }
    }

    public static function defaults(string $default)
    {
        $path = PATH_FRONT . '/View/templates/' . $default . '.php';
        if (file_exists($path)) {
            ob_start();
            include $path;
            return ob_get_clean();
        } else {
            static::defaults('404');
        }
    }

    public static function component(): string
    {
        return static::$viewData;
    }

    private static function getPath(string $path): array
    {
        $uri = [
            'class' => 'inicio',
            'method' => 'index',
            'params' => []
        ];
        if(!empty($path)){
            $parts = explode('/', $path);
            $uri['class'] = array_shift($parts);
            if (isset($parts[0])) {
                $uri['method'] = array_shift($parts);
            }
            $lparams = count($parts);
            if ($lparams > 0) {
                $index = 0;
                $info = [];
                while ($lparams > $index) {
                    $info[] = $parts[$index];
                    $index++;
                }
                $uri[] = $info;
            }
        }
        return $uri;
    }

    public static function page(string $type)
    {
        static::$type = $type;
    }

    public static function router($path)
    {
        header("location: $path");
        exit();
    }

    private static function jsonHeader()
    {
        header("Content-Type: application/json");
    }

}
