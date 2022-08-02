<?php
namespace gamboamartin\template;
use base\frontend\params_inputs;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class html{
    protected errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    /**
     * Genera un alert html boostrap con un mensaje incluido

     * @param string $mensaje Mensaje a mostrar
     * @return string|array Resultado en un html
     */
    public function alert_success(string $mensaje): string|array
    {
        $mensaje = trim($mensaje);
        if($mensaje === ''){
            return $this->error->error(mensaje: 'Error mensaje esta vacio', data: $mensaje);
        }
        return "<div class='alert alert-success' role='alert' ><strong>Muy bien!</strong> $mensaje.</div>";
    }

    /**
     * Genera un alert de tipo warning
     * @param string $mensaje Mensaje a mostrar en el warning
     * @return string|array
     */
    public function alert_warning(string $mensaje): string|array
    {
        $mensaje = trim($mensaje);
        if($mensaje === ''){
            return $this->error->error(mensaje: 'Error mensaje esta vacio', data: $mensaje);
        }
        return "<div class='alert alert-warning' role='alert' ><strong>Advertencia!</strong> $mensaje.</div>";
    }

    /**
     *
     * Funcion que genera un boton de tipo link con href
     * @version 0.11.0
     * @param string $accion Accion a ejecutar
     * @param string $etiqueta Etiqueta de boton
     * @param int $registro_id Registro a mandar transaccion
     * @param string $seccion Seccion a ejecutar
     * @param string $style Estilo del boton info,danger,warning etc
     * @return string|array
     */
    public function button_href(string $accion, string $etiqueta, int $registro_id, string $seccion,
                                string $style): string|array
    {

        $valida = $this->valida_input(accion: $accion,etiqueta:  $etiqueta, seccion: $seccion,style:  $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $session_id = (new generales())->session_id;

        if($session_id === ''){
            return $this->error->error(mensaje: 'Error la $session_id esta vacia', data: $session_id);
        }

        $link = "index.php?seccion=$seccion&accion=$accion&registro_id=$registro_id&session_id=$session_id";
        return "<a |role| href='$link' |class|>$etiqueta</a>";
    }

    /**
     * Genera un div con un label dentro del div
     * @param int $cols Numero de columnas css
     * @param string $contenido Contenido a integrar dentro del div
     * @return string|array
     * @version 0.50.1
     */
    protected function div_control_group_cols(int $cols, string $contenido): string|array
    {
        $valida = (new directivas(html:$this))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        $contenido = trim($contenido);

        $div_contenedor_ini = "<div class='control-group col-sm-$cols'>";
        $div_contenedor_fin = "</div>";

        return $div_contenedor_ini.$contenido.$div_contenedor_fin;
    }

    /**
     * @param int $cols Numero de columnas css
     * @param string $contenido Contenido a integrar dentro del div
     * @param string $label Etiqueta a mostrar
     * @param string $name
     * @return string
     */
    private function div_control_group_cols_label(int $cols, string $contenido, string $label, string $name): string
    {
        $label_html = $this->label(id_css:$name,place_holder: $label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label_html);
        }

        $html = $this->div_control_group_cols(cols: $cols,contenido: $label_html.$contenido);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $html);
        }

        return $html;
    }

    private function div_controls(string $contenido): string
    {
        $div_controls_ini = "<div class='controls'>";
        $div_controls_fin = "</div>";

        return $div_controls_ini.$contenido.$div_controls_fin;
    }



    /**
     * Integra un div group control-group col-sm-n_cols
     * @param int $cols Numero de columnas css
     * @param string $html Html a integrar en contendedor
     * @return string|array
     * @version 0.14.0
     */
    public function div_group(int $cols, string $html): string|array
    {
        $valida = (new directivas(html: $this))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        return "<div |class|>$html</div>";
    }

    /**
     * Genera un contenedor con label
     * @version 0.12.0
     * @param string $html Contenido del div
     * @param string $label Contenido de etiqueta
     * @return string
     */
    public function div_label(string $html, string $label): string
    {
        return $label."<div |class|>$html</div>";
    }

    /**
     * @param string $name Name input
     * @param string $options_html Options en html
     * @param bool $required si required integra requiren en select
     * @return array|string
     */
    protected function div_select(string $name, string $options_html, bool $required = false): array|string
    {
        $required_html = (new params_inputs())->required_html(required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'La asignacion de required es incorrecta', data: $required_html);
        }

        $select_in = "<select class='form-control selectpicker color-secondary $name' id='$name' name='$name' $required_html>";
        $select_fin = '</select>';
        return $select_in.$options_html.$select_fin;
    }

    /** Genera un input de tipo email
     * @version 0.31.1
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador de tipo css
     * @param string $name Nombre del input
     * @param string $place_holder Contenido a mostrar previo a la captura del input
     * @param bool $required Si required aplica required en html
     * @param mixed $value Valor de input
     * @return array|string
     */
    public function email(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                          mixed $value): array|string
    {
        $val = new validacion();

        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }
        if (!isset($val->patterns['correo_html5'])) {
            return $this->error->error(mensaje: 'No existe el regex para email', data: $params);
        }

        $html = "<input type=\"text\" name=\"$params->name\" value=\"$value\" |class| $params->disabled $params->required ";
        $html.= "id=\"$params->id_css\" placeholder=\"$params->place_holder\" pattern=\"{$val->patterns['correo_html5']}\" />";
        return $html;
    }

    private function extra_params(array $extra_params): array|string
    {
        $extra_params_html = '';
        foreach ($extra_params as $data=>$val){
            if(is_numeric($data)){
                return $this->error->error(mensaje: 'Error $data bede ser un texto valido', data: $extra_params);
            }

            $extra_params_html.= "data-$data = '$val'";
        }
        return $extra_params_html;
    }



    /**
     * Obtiene el html de una fecha
     * @version 0.31.1
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador de tipo css
     * @param string $name Nombre del input
     * @param string $place_holder Contenido a mostrar previo a la captura del input
     * @param bool $required
     * @param mixed $value
     * @return array|string
     */
    public function fecha(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                          mixed $value): array|string
    {
        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='date' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$params->id_css' placeholder='$params->place_holder' />";
        return $html;
    }

    /**
     * @param string $descripcion_select
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $options_html
     * @param mixed $value
     * @return array|string
     */
    private function integra_options_html(string $descripcion_select, mixed $id_selected, string $options_html,
                                          mixed $value): array|string
    {
        $option_html = $this->option_html(descripcion_select: $descripcion_select,id_selected: $id_selected,
            value: $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar option', data: $option_html);
        }

        $options_html.=$option_html;

        return $options_html;
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
     * Genera un option para un select
     * @param string $descripcion descripcion del option
     * @param bool $selected Si selected se anexa selected a option
     * @param mixed $value Value del option
     * @param array $extra_params Arreglo con datos para integrar un extra param
     * @return string|array
     */
    private function option(string $descripcion, bool $selected, int|string $value, array $extra_params = array()): string|array
    {
        $value = trim($value);
        if($value === ''){
            return $this->error->error(mensaje: 'Error value no puede venir vacio', data: $value);
        }
        $descripcion = trim($descripcion);
        if($descripcion === ''){
            return $this->error->error(mensaje: 'Error $descripcion no puede venir vacio', data: $descripcion);
        }
        $selected_html = '';
        if($selected){
            $selected_html = 'selected';
        }

        $extra_params_html = $this->extra_params(extra_params: $extra_params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar extra params', data: $extra_params_html);
        }

        if((int)$value === -1){
            $value = '';
        }
        return "<option value='$value' $selected_html $extra_params_html>$descripcion</option>";
    }

    /**
     * @param string $descripcion_select
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param mixed $value
     * @return array|string
     */
    private function option_html(string $descripcion_select, mixed $id_selected, mixed $value): array|string
    {
        $value = (int)$value;
        $selected = $this->selected(value: $value,id_selected: $id_selected);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al verificar selected', data: $selected);
        }

        $option_html = $this->option(descripcion: $descripcion_select,selected:  $selected, value: $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar option', data: $option_html);
        }
        return $option_html;
    }

    /**
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param array $values
     * @return array|string
     */
    private function options(mixed $id_selected, array $values): array|string
    {
        $options_html = $this->option(descripcion: 'Selecciona una opcion',selected:  false, value: -1);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar option', data: $options_html);
        }
        $options_html = $this->options_html_data(id_selected: $id_selected,options_html: $options_html,values: $values);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar options', data: $options_html);
        }
        return $options_html;
    }
    /**
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $options_html
     * @param array $values
     * @return array|string
     */
    private function options_html_data(mixed $id_selected, string $options_html, array $values): array|string
    {
        $options_html_ = $options_html;
        foreach ($values as $value=>$descripcion_select){

            $options_html_ = $this->integra_options_html(descripcion_select: $descripcion_select,
                id_selected: $id_selected,options_html: $options_html_,value: $value);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar option', data: $options_html_);
            }
        }
        return $options_html_;
    }


    /**
     * Genera y valida los parametros de in input tipo text
     * @version 0.28.0
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador de tipo css
     * @param string $name Nombre del input
     * @param string $place_holder Contenido a mostrar previo a la captura del input
     * @param bool $required Si required aplica required en html
     * @return array|stdClass
     */
    private function params_txt(bool $disabled, string $id_css, string $name, string $place_holder,
                                bool $required): array|stdClass
    {

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }


        $disabled_html = (new params_inputs())->disabled_html(disabled:$disabled);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $disabled_html', data: $disabled_html);
        }

        $required_html = (new params_inputs())->required_html(required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $required_html', data: $required_html);
        }

        $params = new stdClass();
        $params->name = $name;
        $params->id_css = $id_css;
        $params->place_holder = $place_holder;
        $params->disabled = $disabled_html;
        $params->required = $required_html;

        return $params;
    }

    /**
     * @param int $cols Numero de columnas css
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $label Etiqueta a mostrar
     * @param string $name Name input
     * @param array $values
     * @param bool $required
     * @return array|string
     */
    public function select(int $cols, int $id_selected, string $label,string $name, array $values, bool $required = false): array|string
    {

        $options_html = $this->options(id_selected: $id_selected,values: $values);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar options', data: $options_html);
        }

        $select = $this->select_html(cols: $cols, label: $label,name: $name,options_html: $options_html, required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }

        return $select;

    }

    /**
     * @param int $cols Numero de columnas css
     * @param string $label Etiqueta a mostrar
     * @param string $name Name input
     * @param string $options_html
     * @param bool $required
     * @return array|string
     */
    private function select_html(int $cols, string $label, string $name, string $options_html, bool $required = false): array|string
    {
        $select = $this->div_select(name: $name,options_html: $options_html, required:  $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }

        $select = $this->div_controls(contenido: $select);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }

        $select = $this->div_control_group_cols_label(cols: $cols,contenido: $select,label: $label,name: $name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }
        return $select;
    }
    /**
     * Verifica si el elemento debe ser selected o no
     * @param mixed $value valor del item del select
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @return bool
     */
    protected function selected(mixed $value, mixed $id_selected): bool
    {
        $selected = false;
        if((string)$value === (string)$id_selected){
            $selected = true;
        }
        return $selected;
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

        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$id_css' placeholder='$params->place_holder' />";
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

    private function valida_params_txt(string $id_css, string $name, string $place_holder): bool|array
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
        return true;

    }
}
