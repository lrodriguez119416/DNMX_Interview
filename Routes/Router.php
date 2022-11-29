<?php 
namespace Routes;
use Routes\Exceptions\DensoExceptions;
class Router{
    
    private $path = [];
    private $pathcomponent = PATH_FRONT.'/Controller/';
    
    public function __construct(array $path){
        $this->path = $path;
        error_log(var_export($this->path,1));
    }

    private function verifyContent(): object {
        $component = $this->pathcomponent.$this->path['class'].'.php';
        if(file_exists($component)){
            require $component;
            $clase = $this->path['class'];
            if(class_exists($clase)){
                $context = new $clase();
                if(!method_exists($context,$this->path['method'])){
                    throw new DensoExceptions("404");
                }
            }else{
                throw new DensoExceptions("404");
            }
        }else{
            throw new DensoExceptions("404");
        }
        return $context;
    }
    
    public function view(): string {
        ob_start();
        try {
            $context = $this->verifyContent();
            $data = call_user_func_array([$context, $this->path['method']], $this->path['params']);
            App::render($this->path['class'].'/'.$this->path['method'], $data);
        } catch (DensoExceptions $e){
            echo $e->showErrors();
        }
        return ob_get_clean();
    }
} 

?>