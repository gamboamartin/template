<?php
namespace gamboamartin\template;
use base\frontend\params_inputs;
use gamboamartin\errores\errores;

class html{
    protected errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    /**
     * Genera un label html
     * @version 0.7.0
     * @param string $id_css id de css
     * @param string $place_holder Etiqueta a mostrar
     * @return string|array string Salida html de label
     */
    public function label(string $id_css, string $place_holder): string|array
    {
        $id_css = trim($id_css);
        if($id_css === ''){
            return $this->error->error(mensaje: 'Error el $id_css esta vacio', data: $id_css);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error el $place_holder esta vacio', data: $place_holder);
        }

       return "";
    }

    /**
     * Genera um input text basado en los parametros enviados
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador css
     * @param string $name Name input html
     * @param string $place_holder Muestra elemento en input
     * @param bool $required indica si es requerido o no
     * @param mixed $value Valor en caso de que exista
     * @return string|array Html en forma de input text
     * @version 0.9.0
     */
    public function text(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                         mixed $value): string|array
    {

        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name es necesario', data: $name);
        }
        $id_css = trim($id_css);
        if($id_css === ''){
            return $this->error->error(mensaje: 'Error $id_css es necesario', data: $id_css);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder es necesario', data: $place_holder);
        }

        $disabled_html = (new params_inputs())->disabled_html(disabled:$disabled);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $disabled_html', data: $disabled_html);
        }

        $required_html = (new params_inputs())->required_html(required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $required_html', data: $required_html);
        }

        $html = "<input type='text' name='$name' value='$value' |class| $disabled_html $required_html ";
        $html.= "id='$id_css' placeholder='$place_holder' />";
        return $html;
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
