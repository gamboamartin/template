<?php
namespace gamboamartin\template;
use gamboamartin\errores\errores;

class directivas{
    private errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    /**
     * Verifica los datos de entrada de un label
     * @version 0.1.0
     * @param string $name Nombre del input
     * @param string $place_holder Dato a mostrar dentro del input de manera inicial
     * @return bool|array
     */
    protected function valida_data_label(string $name, string $place_holder): bool|array
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error $name debe tener info', data: $name);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder debe tener info', data: $place_holder);
        }
        return true;
    }
}
