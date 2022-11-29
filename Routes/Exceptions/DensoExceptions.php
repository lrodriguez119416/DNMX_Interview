<?php
    namespace Routes\Exceptions;
    class DensoExceptions extends \Exception {
        public function showErrors(){
            // aqui hay que agregar el renderizado de las paginas
            return $this->getMessage();
        }
    }
?>