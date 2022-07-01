<?php
namespace gamboamartin\template;
use gamboamartin\errores\errores;

class html{
    protected errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    /**
     * Valida los datos de un input sean correctos
     * @version 0.36.5
     * @param string $accion Accion a verificar
     * @param string $etiqueta Etiqueta a mostrar en el input
     * @param string $seccion Seccion en ejecucion
     * @param string $style Estilo css
     * @return bool|array
     */
    public function valida_input(string $accion, string $etiqueta, string $seccion, string $style): bool|array
    {
        $seccion = trim($seccion);
        if($seccion === ''){
            return $this->error->error(mensaje: 'Error la $seccion esta vacia', data: $seccion);
        }
        $accion = trim($accion);
        if($accion === ''){
            return $this->error->error(mensaje: 'Error la $accion esta vacia', data: $accion);
        }
        $style = trim($style);
        if($style === ''){
            return $this->error->error(mensaje: 'Error la $style esta vacia', data: $style);
        }
        $etiqueta = trim($etiqueta);
        if($etiqueta === ''){
            return $this->error->error(mensaje: 'Error la $etiqueta esta vacia', data: $etiqueta);
        }
        return true;
    }
}
