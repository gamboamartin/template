<?php
namespace gamboamartin\template;
use base\frontend\params_inputs;
use config\views;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class directivas{
    protected errores $error;
    public html $html;
    public function __construct(html $html){
        $this->error = new errores();
        $this->html = $html;
    }

    /**
     * @url https://github.com/gamboamartin/template/wiki/template-src-directivas#m%C3%A9todo-btn---clase-directivas
     * Genera un botón HTML dinámicamente.
     *
     * @param array $ids_css        - Array de identificadores CSS que se asignarán al botón.
     * @param array $clases_css     - Array de clases CSS que se asignarán al botón.
     * @param array $extra_params   - Array de atributos adicionales que se asignarán al botón.
     * @param string $label         - Texto que se mostrará en el botón.
     * @param string $name          - Nombre del botón.
     * @param string $value         - Valor del botón.
     * @param int $cols             - Columnas que ocupará el botón (Bootstrap Grid).
     * @param string $style         - Estilo del botón (Bootstrap button style).
     * @param string $type          - Tipo del botón ('button', 'submit', etc.).
     *                                 El valor predeterminado es 'button'.
     *
     * @return array|string         - Devuelve una cadena que representa el código HTML del botón si
     *                                no hay errores.
     *                                Si hay algún error durante la validación,
     *                                devolverá un array de errores.
     *
     */
    final public function btn(array $ids_css, array $clases_css, array $extra_params, string $label, string $name,
                              string $value, int $cols = 6 , string $style = 'info',
                              string $type='button'): array|string
    {

        $label = trim($label);
        $name = trim($name);
        if($label === ''){
            $label = $name;
            $label = str_replace('_', ' ', $label);
            $label = ucwords($label);
        }

        $valida = $this->valida_btn_next(label: $label,style:  $style,type:  $type,value:  $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }
        $ids_css_html = '';
        foreach ($ids_css as $id_css){
            $ids_css_html.=' '.$id_css;
        }
        $clases_css_html = '';
        foreach ($clases_css as $class_css){
            $clases_css_html.=' '.$class_css;
        }

        $extra_params_data = '';
        foreach ($extra_params as $key=>$value_param){
            $extra_params_data = " data-$key='$value_param' ";
        }

        $btn = "<button type='$type' class='btn btn-$style btn-guarda col-md-$cols $clases_css_html' id='$ids_css_html' ";
        $btn .= "name='$name' value='$value' $extra_params_data>$label</button>";
        return $btn;
    }


    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Genera un botón de formulario HTML.
     *
     * @param string $label La etiqueta visible para el botón.
     * @param string $value El valor que se envía cuando se hace clic en el botón.
     * @param string $style Estilo css del botón, predeterminado a 'info'.
     * @param string $type El tipo de botón, por defecto 'submit'.
     * @return string|array Devuelve una cadena que representa el marcado html para el botón.
     *                      Si ocurre un error durante la validación, devuelve un array que contiene
     *                      información sobre el error.
     *
     * @throws errores Si la validación de los datos falla.
     * @version 16.4.0
     */
    private function btn_action_next(string $label,string $value, string $style = 'info',
                                     string $type='submit'): string|array
    {
        $valida = $this->valida_btn_next(label: $label,style:  $style,type:  $type,value:  $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }


        $btn = "<button type='$type' class='btn btn-$style btn-guarda col-md-12' ";
        $btn .= "name='btn_action_next' value='$value'>$label</button>";
        return $btn;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Genera un botón contenido dentro de un div con las especificaciones propias asignadas.
     *
     * @param string $label Etiqueta del botón.
     * @param string $value Valor del botón. Se usa como atributo value en el código HTML.
     * @param int $cols Número de columnas que ocupará el botón en un diseño con Bootstrap. Valor por defecto es 6.
     * @param string $style Estilo de color del botón. Configura la clase de botón en Bootstrap. Valor por defecto es 'info'.
     * @param string $type Tipo del botón. Configura el atributo 'type' en el botón HTML. Valor por defecto es 'submit'.
     *
     * @return array|string Devuelve un error si las validaciones en los datos de entrada o en las operaciones internas fallan.
     *                      En caso contrario, devuelve una cadena con el código HTML del botón dentro de un elemento div.
     *
     * @final
     * @version 16.5.0
     */
    final public function btn_action_next_div(string $label,string $value, int $cols = 6, string $style = 'info',
                                        string $type='submit'): array|string
    {
        $valida = $this->valida_btn_next(label: $label,style:  $style,type:  $type,value:  $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        $btn = $this->btn_action_next(label: $label,value:  $value, style: $style, type: $type);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar btn datos ', data: $btn);
        }

        return "<div class='col-md-$cols'>$btn</div>";
    }

    /**
     * Genera un boton tipo link
     * @param string $accion Accion a ejecutar
     * @param string $etiqueta Etiqueta de boton
     * @param string $name Nombre para ser aplicado a for
     * @param string $place_holder Etiqueta a mostrar
     * @param int $registro_id Registro a mandar transaccion
     * @param string $seccion Seccion a ejecutar
     * @param string $style Estilo del boton info,danger,warning etc
     * @return array|string
     */
    final protected function button_href(string $accion, string $etiqueta, string $name, string $place_holder,
                                         int $registro_id, string $seccion, string $style): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $valida = $this->html->valida_input(accion: $accion,etiqueta:  $etiqueta, seccion: $seccion,style:  $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $label = $this->label_input(name: $name,place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder debe tener info', data: $place_holder,
                es_final: true);
        }
        $html= $this->html->button_href(accion: $accion,etiqueta:  $etiqueta, registro_id: $registro_id,
            seccion:  $seccion, style: $style);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar html', data: $html);
        }

        $div = $this->html->div_label(html: $html,label:  $label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un boton de tipo link para transaccionar status
     * @param int $cols Columnas en formato css de 1 a 12
     * @param int $registro_id Registro id a mandar transaccion
     * @param string $seccion Seccion a ejecutar
     * @param string $status debe ser activo inactivo
     * @return array|string
     */
    final public function button_href_status(int $cols, int $registro_id, string $seccion, string $status): array|string
    {

        $seccion = trim($seccion);
        if($seccion === ''){
            return $this->error->error(mensaje: 'Error la $seccion esta vacia', data: $seccion, es_final: true);
        }
        $status = trim($status);
        if($status === ''){
            return $this->error->error(mensaje: 'Error el $status esta vacio', data: $status, es_final: true);
        }
        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $style = 'danger';
        if($status === 'activo'){
            $style = 'info';
        }

        $html = $this->button_href(accion: 'status',etiqueta: $status,name: 'status',
            place_holder: 'Status',registro_id: $registro_id,seccion: $seccion, style: $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $html);
        }

        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     *
     * Integra el elemento checked del radio predeterminado
     * @param int $checked_default Numero de input predeterminado
     * @return stdClass|array
     * @version 8.15.0
     */
    private function checked_default(int $checked_default): stdClass|array
    {
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default);
        }
        $checked_default_v1 = '';
        $checked_default_v2 = '';

        if($checked_default === 1){
            $checked_default_v1 = 'checked';
        }
        if($checked_default === 2){
            $checked_default_v2 = 'checked';
        }

        $data = new stdClass();
        $data->checked_default_v1 = $checked_default_v1;
        $data->checked_default_v2 = $checked_default_v2;
        return $data;
    }

    /**
     * Genera un conjunto de class para input radio
     * @param array $class_label Clase label precargada
     * @return array|string
     * @version 8.7.0
     */
    private function class_label_html(array $class_label): array|string
    {
        $class_label[] = 'form-check-label';

        $class_label_html = (new params_inputs())->class_html(class_css: $class_label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_label', data: $class_label_html);
        }
        return str_replace('  ', ' ', $class_label_html);
    }

    /**
     * Genera las clases css para input radio
     * @param array $class_radio Clases precargadas
     * @return array|string
     * @version 8.11.0
     */
    private function class_radio_html(array $class_radio): array|string
    {
        $class_radio[] = 'form-check-input';
        $class_radio_html = (new params_inputs())->class_html(class_css: $class_radio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_radio_html', data: $class_radio_html);
        }
        return str_replace('  ', ' ', $class_radio_html);

    }


    /**
     * Genera un div con label integrado
     * @param string $html Html previo
     * @param string $name Name input
     * @param string $place_holder Tag input
     * @return array|string
     */
    private function div_label(string $html, string $name, string $place_holder): array|string
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error el name esta vacio', data: $name);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error el $place_holder esta vacio', data: $place_holder);
        }

        $label = $this->html->label(id_css: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        $div = $this->html->div_label(html:  $html,label:$label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        $html_r = (new html())->limpia_salida(html: $div);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }

    /**
     * Integra en un div u radio
     * @param int $cols N columnas css
     * @param stdClass $inputs Inputs a integrar
     * @param string $label_html Label de input
     * @return string|array
     * @version 8.19.0
     */
    private function div_radio(int $cols, stdClass $inputs, string $label_html): string|array
    {
        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error validar cols', data: $valida);
        }

        $keys = array('label_input_v1','label_input_v2');

        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $inputs);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error validar inputs', data: $valida);
        }

        $label_html = trim($label_html);

        return "<div class='control-group col-sm-$cols'>
            $label_html
            $inputs->label_input_v1
            $inputs->label_input_v2
        </div>";
    }

    /**
     * Genera un input de tipo email como required
     * @param bool $disabled Si disabled el input queda inhabilitado
     * @param string $name Name del input
     * @param string $place_holder Muestra el contenido en el input
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si valor vacio el value lo deja vacio
     * @return array|string
     * @version 0.99.4
     * @finalrev
     */
    public function email_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                   bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->email(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->row_upd->$name);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera input de tipo fecha como required
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $disabled si disabled retorna el input como disabled
     * @param string $name Usado para identificador css name input y place holder
     * @param string $place_holder Texto a mostrar en el input
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     * @version 0.102.4
     */
    final public function fecha_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                   bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $data_init = $this->init_text(name: $name, place_holder: $place_holder,row_upd:  $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar row_upd div', data: $data_init);
        }

        $html= $this->html->fecha(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $data_init->row_upd->$name);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar input fecha', data: $html);
        }

        $div = $this->html->div_label(html:  $html,label:$data_init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un input de tipo fecha
     * @param bool $disabled si disabled retorna el input como disabled
     * @param string $name Usado para identificador css name input y place holder
     * @param string $place_holder Texto a mostrar en el input
     * @param bool $required Integra el atributo requerido en el input
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si el valor esta vacio no integra datos
     * @return array|string
     * @version 7.12.0
     */
    final public function fecha(bool $disabled, string $name, string $place_holder, bool $required, stdClass $row_upd,
                                   bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $data_init = $this->init_text(name: $name, place_holder: $place_holder,row_upd:  $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar row_upd div', data: $data_init);
        }

        $html= $this->html->fecha(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $data_init->row_upd->$name);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar input fecha', data: $html);
        }

        $div = $this->html->div_label(html:  $html,label:$data_init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Integra los id para inputs radio
     * @param array $ids_css Identificadores a integrar
     * @return string|array
     * @version 8.13.0
     */
    private function ids_html(array $ids_css): string|array
    {
        $ids_html = '';
        foreach ($ids_css as $id_css){
            $ids_html = trim($ids_html);
            if($id_css === ''){
                return $this->error->error(mensaje: 'Error ids_html', data: $id_css);
            }
            $ids_html.=" $id_css ";
        }
        $ids_html = trim($ids_html);

        if($ids_html!==''){
            $ids_html = "id='$ids_html'";
        }

        return $ids_html;
    }

    /** Inicializa un input de tipo text
     * @param string $name Name input
     * @param string $place_holder place_holder input
     * @param stdClass $row_upd Registro en proceso
     * @param mixed $value Valor del input
     * @param bool $value_vacio si vacio no integra value de row
     * @return array|stdClass
     * @version 6.24.2
     */
    private function init(string $name, string $place_holder, stdClass $row_upd, mixed $value,
                          bool $value_vacio): array|stdClass
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $value_input = $this->value_input(init: $init,name:  $name,value:  $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener value_input', data: $value_input);
        }

        $init->value_input = $value_input;
        return $init;
    }

    /**
     * Inicializa elementos de tipo name y title
     * @param string $name Nombre del input
     * @param string $title Title del input
     * @return array|stdClass
     * @version 8.10.0
     */
    private function init_names(string $name, string $title): array|stdClass
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio',data:  $name);
        }
        if($title === ''){
            $title = $name;
            $title = str_replace('_', ' ', $title);
            $title = ucwords($title);
            $title = trim($title);
        }

        $data = new stdClass();
        $data->name = $name;
        $data->title = $title;
        return $data;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Esta función se encarga de inicializar la entrada de los campos.
     *
     * @param string $name Este parámetro recibe el nombre del campo de entrada.
     * @param string $place_holder Este parámetro es el será el placeholder para el campo de entrada.
     * @param stdClass $row_upd Este parámetro es el objeto que contiene información para actualizar una fila.
     * @param bool $value_vacio Este parámetro indica si el valor de entrada está vacío o no.
     *
     * @return array|stdClass Retorna un objeto o un array dependiendo de las validaciones y operaciones realizadas.
     *
     * Esta función primero valida las etiquetas mediante la función `valida_etiquetas`. Si hay un error durante la validación,
     * se genera un error y se devuelve la información del error.
     *
     * Luego intenta generar el 'row upd' utilizando la función `row_upd_name`. Si hay un error durante esta operación,
     * se genera un error y se devuelve la información del error.
     *
     * Finalmente, retorna el 'row upd' si no ha habido errores durante las operaciones anteriores.
     *
     * @version 16.14.0
     */
    private function init_input(string $name, string $place_holder, stdClass $row_upd,
                                bool $value_vacio): array|stdClass
    {
        $valida = $this->valida_etiquetas(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar etiquetas', data: $valida);
        }
        $row_upd_ =$row_upd;
        $row_upd_ = $this->row_upd_name(name: $name, value_vacio: $value_vacio, row_upd: $row_upd_);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }
        return $row_upd_;
    }


    /**
     * Genera un input de tipo alias
     * @version 0.49.1
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     * @finalrev
     */
    public function input_alias(stdClass $row_upd, bool $value_vacio): array|string
    {
        $html =$this->input_text_required(disabled: false,name: 'alias',
            place_holder: 'Alias', row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: 6,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo codigo
     * @version 0.35.1
     * @param int $cols Numero de columnas boostrap
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     */
    final public function input_codigo(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->input_text_required(disabled: false,name: 'codigo',place_holder: 'Codigo',row_upd: $row_upd,
            value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo codigo bis
     * @version 0.36.1
     * @param int $cols Numero de columnas boostrap
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     */
    final public function input_codigo_bis(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {

        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->input_text_required(disabled: false,name: 'codigo_bis',
            place_holder: 'Codigo BIS', row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }
        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un text de tipo descripcion
     * @param stdClass $row_upd Objeto con datos del row
     * @param bool $value_vacio si value vacia no integra valor en el input
     * @return array|string
     * @version 0.106.4
     */
    final public function input_descripcion(stdClass $row_upd, bool $value_vacio): array|string
    {
        $html =$this->input_text_required(disabled: false,name: 'descripcion', place_holder: 'Descripcion',
            row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: 12,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un input text de descripcion_select
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     * @version 0.94.4
     */
    final public function input_descripcion_select(stdClass $row_upd, bool $value_vacio): array|string
    {
        $html =$this->input_text_required(disabled: false,name: 'descripcion_select',
            place_holder: 'Descripcion Select', row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: 6,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo id
     * @param int $cols Numero de columnas css
     * @param stdClass $row_upd Registro en operacion
     * @param bool $value_vacio si value vacio deja limpio el input
     * @return array|string
     * @version 0.103.4
     */
    final public function input_id(int $cols, stdClass $row_upd, bool $value_vacio): array|string
    {
        $valida = (new directivas(html: $this->html))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        $html =$this->input_text(disabled: true,name: 'id',place_holder: 'ID',
            required: false, row_upd: $row_upd, value_vacio: $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar input', data: $html);
        }

        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input tipo required
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $disabled si disabled retorna el input como disabled
     * @param string $name Usado para identificador css name input y place holder
     * @param string $place_holder Texto a mostrar en el input
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @return array|string
     * @version 1.110.4
     */
    final public function input_password(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                        bool $value_vacio ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,
            value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->password(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->row_upd->$name);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un input de tipo radio double
     * @param string $campo Campo A integrar
     * @param int $checked_default  Value default checked
     * @param string $tag Etiqueta
     * @param string $val_1 Value 1
     * @param string $val_2 Value 2
     * @return array|string
     * @version 8.21.0
     */
    final public function input_radio_doble(string $campo, int $checked_default, string $tag, string $val_1,
                                            string $val_2): array|string
    {
        $campo = trim($campo);
        if($campo === ''){
            return $this->error->error(mensaje: 'Error campo vacio',data:  $campo);
        }
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default);
        }

        $params_chk = (new params_inputs())->params_base_chk(campo: $campo,tag:  $tag);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener params_chk',data:  $params_chk);
        }

        $radio = $this->radio_doble(checked_default: $checked_default,
            class_label:  $params_chk->class_label,class_radio:  $params_chk->class_radio,cols:6,
            for: $params_chk->for, ids_css: $params_chk->ids_css,label_html:  $params_chk->label_html,
            name:  $params_chk->name,title:  $params_chk->title,val_1: $val_1,val_2: $val_2);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener radio',data:  $radio);
        }
        return $radio;

    }

    /**
     * Genera un input de tipo telefono
     * @param bool $disabled atributo disabled
     * @param string $name Name input
     * @param string $place_holder Tag Input
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja sin value
     * @param bool $required Indica si es requerido
     * @param mixed|null $value Valor prioritario a integracion en caso de que este seteado
     * @return array|string
     * @version 0.126.5
     */
    final public function input_telefono(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                   bool $value_vacio, bool $required = true, mixed $value = null ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $value_input = $row_upd->$name;
        if(!is_null($value)){
            $value_input = $value;
        }

        $html= $this->html->telefono(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $value_input);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }


    /**
     * Funcion de inicializacion de datos para inputs
     * @version 0.48.1
     * @param string $name Nombre del input
     * @param string $place_holder Dato a mostrar previo a la captura
     * @param stdClass $row_upd Registro
     * @param bool $value_vacio Si vacio inicializa row name como vacio
     * @return array|stdClass
     */
    final protected function init_text(string $name, string $place_holder, stdClass $row_upd,
                                       bool $value_vacio): array|stdClass
    {
        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $label = $this->label_input(name: $name,place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        if($value_vacio || !(isset($row_upd->$name))){
            $row_upd->$name = '';
        }

        $data = new stdClass();
        $data->row_upd = $row_upd;
        $data->label = $label;

        return $data;
    }

    /**
     * Integra un input de tipo fecha required
     * @param bool $disabled Atributo disabled
     * @param string $name Name input
     * @param string $place_holder Label input
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja vacio el input
     * @param bool $required Required default true
     * @param mixed|null $value Valor prioritario de input
     * @param bool $value_hora Si es verdadero integra datetime en input
     * @return array|string
     * @version 8.24.0
     */
    final public function input_fecha_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                               bool $value_vacio, bool $required = true, mixed $value = null,
                                               bool $value_hora = false ): array|string
    {

        $name = trim($name);
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            $place_holder = $name;
            $place_holder = str_replace('_', ' ',$place_holder);
            $place_holder = ucwords($place_holder);
        }

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init(
            name: $name,place_holder:  $place_holder,row_upd:  $row_upd,value:  $value,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }


        $html= $this->html->fecha(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $init->value_input, value_hora: $value_hora);

        $div = $this->html->div_label(html:  $html,label:$init->label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * Genera un input de tipo file
     * @param bool $disabled atributo disabled
     * @param string $name Name input
     * @param string $place_holder Tag input
     * @param bool $required Atributo required
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja limpio el input
     * @return array|string
     */
    final public function input_file(bool $disabled, string $name, string $place_holder, bool $required, stdClass $row_upd,
                               bool $value_vacio): array|string
    {

        $valida = $this->valida_etiquetas(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar etiquetas', data: $valida);
        }

        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }

        $html= $this->html->file(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $row_upd_->$name);



        $div = $this->div_label(html:$html, name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;
    }

    /**
     * Genera un input de tipo monto requerido
     * @param bool $disabled Si disabled deja el input deshabilitado
     * @param string $name Name del input
     * @param string $place_holder Se muestra en input
     * @param stdClass $row_upd Registro base en proceso
     * @param bool $value_vacio si vacio deja vacio
     * @param bool $con_label Si con label integra la etiqueta
     * @param mixed|null $value Valor
     * @return array|string
     * @version 7.8.0
     */
    final public function input_monto_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                         bool $value_vacio, bool $con_label = true , mixed $value = null): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init(
            name: $name,place_holder:  $place_holder,row_upd:  $row_upd,value:  $value,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->monto(disabled:$disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->value_input);

        if($con_label) {
            $html = $this->html->div_label(html: $html, label: $init->label);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar div', data: $html);
            }
        }

        return $html;

    }

    /**
     * Genera un input text en html
     * @param bool $disabled si disabled el elemento queda deshabilitado
     * @param string $name Nombre de input
     * @param string $place_holder Label a mostrar dentro de input
     * @param bool $required si required integra attr required
     * @param stdClass $row_upd Registro en proceso
     * @param bool $value_vacio Si vacio deja input sin value
     * @param array $ids_css Identificadores css
     * @param string $regex Regex
     * @param string $title Titulo on over
     * @return array|string
     * @version 0.101.4
     */
    final public function input_text(bool $disabled, string $name, string $place_holder, bool $required,
                                     stdClass $row_upd, bool $value_vacio, array $ids_css = array(),
                                     string $regex = '', string $title = ''): array|string
    {


        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }


        $html= $this->html->text(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $row_upd_->$name, ids_css: $ids_css, regex: $regex, title: $title);


        $div = $this->div_label(html:$html, name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    final public function input_text_sin_label(array $class_css,int $cols, bool $disabled, string $name,
                                               string $place_holder, bool $required, stdClass $row_upd,
                                               bool $value_vacio): array|string
    {


        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,
            value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }

        $html= $this->html->text_class(class_css: $class_css, disabled:$disabled, id_css: $name, name: $name,
            place_holder: $place_holder, required: $required, value: $row_upd_->$name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar html', data: $html);
        }

        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }


        return $div;

    }


    /**
     * Genera un input tipo required
     * @param bool $disabled si disabled retorna el input como disabled
     * @param string $name Usado para identificador css name input y place holder
     * @param string $place_holder Texto a mostrar en el input
     * @param stdClass $row_upd Registro obtenido para actualizar
     * @param bool $value_vacio Para altas en caso de que sea vacio o no existe el key
     * @param bool $con_label Integra el label en el input
     * @param array $ids_css Identificadores extra
     * @param string $regex regex a integrar en pattern
     * @param string $title title a integrar a input
     * @return array|string
     * @version 0.48.1
     */
    final public function input_text_required(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                        bool $value_vacio, bool $con_label = true, array $ids_css = array(),
                                              string $regex = '', string $title = '' ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $html= $this->html->text(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: true, value: $init->row_upd->$name, ids_css: $ids_css, regex: $regex, title: $title);

        if($con_label) {
            $html = $this->html->div_label(html: $html, label: $init->label);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar div', data: $html);
            }
        }

        return $html;

    }

    final public function input_text_base(bool $disabled, string $name, string $place_holder, stdClass $row_upd,
                                          bool $value_vacio, array $class_css = array(), bool $con_label = true,
                                          array $ids_css = array(), string $regex = '', bool $required = true,
                                          string $title = '', string|null $value = '' ): array|string
    {

        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $init = $this->init_text(name: $name,place_holder:  $place_holder, row_upd: $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al inicializar datos', data: $init);
        }

        $value_input = $init->row_upd->$name;

        if(is_null($value)){
            $value = '';
        }

        $value = trim($value);
        if($value!==''){
            $value_input = $value;
        }

        $html= $this->html->text_base(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $value_input, class_css: $class_css, ids_css: $ids_css, regex: $regex,
            title: $title);

        if($con_label) {
            $html = $this->html->div_label(html: $html, label: $init->label);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al integrar div', data: $html);
            }
        }

        return $html;

    }

    /**
     * Se inicializa los parametros de front de un radio
     * @param string $for Tag
     * @param string $label_html Label
     * @return stdClass|array
     * @version 8.18.0
     */
    private function label_init(string $for, string $label_html): stdClass|array
    {
        $for = trim($for);
        if($for === ''){
            $for = $label_html;
        }

        $label_html = trim($label_html);
        if($label_html === ''){
            $label_html = $for;
        }
        $for = trim($for);
        $label_html = trim($label_html);

        if($for === ''){
            return $this->error->error(mensaje: 'Error for esta vacio',data:  $for);
        }
        if($label_html === ''){
            return $this->error->error(mensaje: 'Error label_html esta vacio',data:  $label_html);
        }

        $data = new stdClass();
        $data->for = $for;
        $data->label_html = $label_html;

        return $data;
    }


    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Genera una etiqueta HTML a partir del nombre y placeholder proporcionados.
     *
     * Este método valida primero los datos de entrada. Si hay un error, devolverá un mensaje de error.
     * Después genera una etiqueta HTML usando el nombre y el placeholder proporcionados.
     * Si ocurre algún problema al generar la etiqueta, devolverá un mensaje de error.
     *
     * @param string $name El nombre que se usará en el atributo 'id' de la etiqueta.
     * @param string $place_holder El texto que se mostrará en el interior de la etiqueta.
     * @return array|string Devuelve la etiqueta generada si todo es correcto,
     *                      de lo contrario retorna un mensaje de error y la data
     *                      que causó el error.
     *
     * @throws errores En caso de algún error lor al generar la etiqueta HTML.
     * @version 16.7.0
     */
    final protected function label_input(string $name, string $place_holder): array|string
    {
        $valida = $this->valida_data_label(name: $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        $label = $this->html->label(id_css: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }
        return $label;
    }

    /**
     * Integra el label de un radio
     * @param string $checked attr checked
     * @param string $class_label_html clases css en div label
     * @param string $class_radio_html clases css en input radio
     * @param string $ids_html Identificador css
     * @param string $name name input
     * @param string $title titulo input
     * @param string $val Value input
     * @return string|array
     * @version 8.6.0
     */
    private function label_input_radio(string $checked, string $class_label_html,string $class_radio_html,
                                       string $ids_html, string $name, string $title, string $val): string|array
    {
        $checked = trim($checked);
        $class_label_html = trim($class_label_html);
        $class_radio_html = trim($class_radio_html);
        $ids_html = trim($ids_html);
        $name = trim($name);
        $title = trim($title);
        $val = trim($val);



        $init = $this->init_names(name: $name,title:  $title);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error integrar datos',data:  $init);
        }

        return trim("
            <label $class_label_html>
                <input type='radio' name='$init->name' value='$val' $class_radio_html $ids_html 
                title='$init->title' $checked>
                $val
            </label>");
    }

    /**
     * Genera el label para un input de tipo radio
     * @param string $for Param for de label
     * @param string $label_html Label a integrar
     * @return string|array
     * @version 8.2.0
     */
    private function label_radio(string $for, string $label_html): string|array
    {

        $params = $this->label_init(for: $for, label_html: $label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params',data:  $params);
        }

        return "<label class='control-label' for='$params->for'>$params->label_html</label>";
    }

    /**
     * Obtiene los inputs de tipo radio dos opciones
     * @param string $name Nombre del input
     * @param stdClass $params Parametros de input
     * @param string $title Titulo de radios
     * @param string $val_1 Valor de input 1
     * @param string $val_2 Valor de input 2
     * @return array|stdClass
     * @version 8.9.0
     */
    private function labels_radios(
        string $name, stdClass $params, string $title, string $val_1, string $val_2): array|stdClass
    {
        $keys = array('checked_default','class_label_html','class_radio_html','ids_html');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $params,valida_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar params', data: $valida);
        }

        $keys = array('checked_default_v1','checked_default_v2');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $params->checked_default,
            valida_vacio: false);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar params', data: $valida);
        }
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio',data:  $name);
        }


        $init = $this->init_names(name: $name,title:  $title);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error integrar datos',data:  $init);
        }


        $label_input_v1 = $this->label_input_radio(checked: $params->checked_default->checked_default_v1,
            class_label_html:  $params->class_label_html, class_radio_html:  $params->class_radio_html,
            ids_html:  $params->ids_html,name:  $init->name,title:  $init->title,val:  $val_1);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar label_input_v1', data: $label_input_v1);
        }

        $label_input_v2 = $this->label_input_radio(checked: $params->checked_default->checked_default_v2,
            class_label_html:  $params->class_label_html, class_radio_html:  $params->class_radio_html,
            ids_html:  $params->ids_html,name:  $name,title:  $title,val:  $val_2);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar label_input_v2', data: $label_input_v2);
        }

        $data = new stdClass();
        $data->label_input_v1 = $label_input_v1;
        $data->label_input_v2 = $label_input_v2;
        return $data;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Genera un mensaje de alerta de éxito o un mensaje de error.
     *
     * @param string $mensaje_exito El mensaje que se mostrará en la alerta de exito.
     * @return array|string Retorna una alerta de éxito. Si se produce algún error, se retorna un mensaje de error.
     * @version 18.7.0
     */
    final public function mensaje_exito(string $mensaje_exito): array|string
    {
        $alert_exito = '';
        if($mensaje_exito!==''){
            $alert_exito = $this->html->alert_success(mensaje: $mensaje_exito);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar alerta', data: $alert_exito);
            }

        }
        return $alert_exito;
    }

    /**
     * Genera un mensaje de tipo warning
     * @param string $mensaje_warning mensaje a mostrar
     * @return array|string

     */
    final public function mensaje_warning( string $mensaje_warning): array|string
    {
        $alert_warning = '';
        if($mensaje_warning!==''){
            $alert_warning = $this->html->alert_warning(mensaje: $mensaje_warning);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar alerta', data: $alert_warning);
            }
        }
        return $alert_warning;
    }

    /**
     * Genera un numero para menu lateral
     * @param string $number Numero svg
     * @return string
     */
    public function number_menu_lateral(string $number): string
    {
        $img =  (new views())->url_assets."img/numeros/$number.svg";
        return "<img src='$img' class='numero'>";
    }


    /**
     * Integra los parametros para un input radio
     * @param int $checked_default valor 1 0 2 integra checked input
     * @param array $class_label Clases del label radio
     * @param array $class_radio Clases del input radio
     * @param array $ids_css Ids del input radio
     * @param string $label_html Tag de input
     * @param string $for For de input
     * @return array|stdClass
     * @version 8.19.0
     */
    private function params_html(int $checked_default, array $class_label, array $class_radio, array $ids_css,
                                 string $label_html, string $for): array|stdClass
    {

        $params_radio = $this->label_init(for: $for, label_html: $label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params',data:  $params_radio);
        }
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default);
        }

        $label_html = $this->label_radio(for: $params_radio->for,label_html:  $params_radio->label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label_html', data: $label_html);
        }

        $class_label_html = $this->class_label_html(class_label: $class_label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_label', data: $class_label_html);
        }

        $class_radio_html = $this->class_radio_html(class_radio: $class_radio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar class_radio_html', data: $class_radio_html);
        }

        $ids_html = $this->ids_html(ids_css: $ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar ids_html', data: $ids_html);
        }

        $checked_default = $this->checked_default(checked_default: $checked_default);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar checked_default', data: $checked_default);
        }

        $params = new stdClass();
        $params->label_html = $label_html;
        $params->class_label_html = $class_label_html;
        $params->class_radio_html = $class_radio_html;
        $params->ids_html = $ids_html;
        $params->checked_default = $checked_default;

        return $params;


    }

    /**
     * Se integra un input de tipo radio
     * @param int $checked_default checked input checked
     * @param array $class_label Clases Label
     * @param array $class_radio  Clases input radio
     * @param int $cols n columnas css
     * @param string $for tag
     * @param array $ids_css ids css
     * @param string $label_html Label de input
     * @param string $name Name input
     * @param string $title Titulo input
     * @param string $val_1 Valor input 1
     * @param string $val_2 Valor input 2
     * @return array|string
     * @version 1.99.1
     *
     */
    private function radio_doble(int $checked_default,array $class_label, array $class_radio, int $cols,string $for,
                                 array $ids_css, string $label_html, string $name, string $title, string $val_1,
                                 string $val_2): array|string
    {

        $params_radio = $this->label_init(for: $for, label_html: $label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params',data:  $params_radio);
        }
        if($checked_default <=0){
            return $this->error->error(mensaje: 'Error checked_default debe ser mayor a 0', data: $checked_default);
        }
        if($checked_default > 2){
            return $this->error->error(mensaje: 'Error checked_default debe ser menor a 3', data: $checked_default);
        }
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio',data:  $name);
        }
        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error validar cols', data: $valida);
        }


        $params = $this->params_html(checked_default: $checked_default,class_label:  $class_label,
            class_radio:  $class_radio, ids_css: $ids_css,label_html:  $params_radio->label_html,
            for:  $params_radio->for);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar params', data: $params);
        }


        $inputs = $this->labels_radios(name: $name,params:  $params,title:  $title,val_1: $val_1,val_2:  $val_2);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar inputs', data: $inputs);
        }


        $radios = $this->div_radio(cols: $cols,inputs:  $inputs,label_html:  $params->label_html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar radios', data: $radios);
        }

        return $radios;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Este método `row_upd_name` sirve para procesar y actualizar un nombre de fila.
     * @param string $name El nombre que se va a procesar.
     * @param bool $value_vacio Un indicador que determina si el valor de la fila $name debe ser vacío.
     * @param stdClass $row_upd (opcional) Un objeto que contiene la fila a actualizar.
     * @return stdClass|array Retorna un objeto stdClass si el proceso es exitoso. En caso contrario,
     * se devuelve un arreglo con la información del error.
     * @version 16.13.0
     */
    private function row_upd_name(string $name, bool $value_vacio, stdClass $row_upd = new stdClass()): stdClass|array
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio', data: $name, es_final: true);
        }
        if($value_vacio){
            $row_upd = new stdClass();
            $row_upd->$name = '';
        }
        if(!isset($row_upd->$name)){
            $row_upd->$name = '';
        }

        return $row_upd;
    }

    public function textarea(bool $disabled, string $name, string $place_holder, bool $required,
                                   stdClass $row_upd, bool $value_vacio, array $ids_css = array()): array|string
    {
        $row_upd_ = $this->init_input(name:$name,place_holder:  $place_holder,row_upd:  $row_upd,value_vacio:  $value_vacio);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar row upd', data: $row_upd_);
        }

        $html= $this->html->textarea(disabled: $disabled, id_css: $name, name: $name, place_holder: $place_holder,
            required: $required, value: $row_upd_->$name, ids_css: $ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar textarea', data: $html);
        }

        $div = $this->div_label(html:$html, name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        return $div;

    }

    /**
     * @url https://github.com/gamboamartin/template/wiki/template-src-directivas#m%C3%A9todo-valida_btn_next---clase-directivas
     * Valida los botones del siguiente.
     *
     * @param string $label Etiqueta del botón, también se valida en la base de datos.
     * @param string $style Estilo del botón, no puede estar vacío.
     * @param string $type Tipo del botón, no puede estar vacío.
     * @param string $value Valor del botón, se valida en la base de datos.
     * @return true|array Devuelve verdadero si la validación es exitosa, de lo contrario, devuelve un array con los errores.
     */
    final public function valida_btn_next(string $label, string $style, string $type, string $value): true|array
    {

        $valida = $this->valida_data_base(label: $label,value:  $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $style = trim($style);
        if($style === ''){
            return $this->error->error(mensaje: 'Error $style esta vacio', data: $style, es_final: true);
        }
        $type = trim($type);
        if($type === ''){
            return $this->error->error(mensaje: 'Error $type esta vacio', data: $type, es_final: true);
        }

        return true;
    }

    /**
     * @url https://github.com/gamboamartin/template/wiki/template-src-directivas#m%C3%A9todo-valida_data_base--clase-directivas
     * Valida los datos de entrada para asegurarse de que la etiqueta y el valor no estén vacíos.
     *
     * @param string $label Etiqueta a comprobar.
     * @param string $value Valor a comprobar.
     * @return true|array Devuelve true si la etiqueta y el valor no están vacíos,
     *                    o devuelve un array con un mensaje de error en caso contrario.
     *
     */
    final public function valida_data_base(string $label, string $value): true|array
    {
        $label = trim($label);
        if($label === ''){
            return $this->error->error(mensaje: 'Error label esta vacio', data: $label, es_final: true);
        }
        $value = trim($value);
        if($value === ''){
            return $this->error->error(mensaje: 'Error $value esta vacio', data: $value, es_final: true);
        }
        return true;
    }



    /**
     * POR DOCUMENTAR EN WIKI ERROR FINAL REV
     * Valida el número de columnas proporcionado.
     *
     * Esta función valida que el número de columnas ($cols) este entre 1 y 12. Si el valor es menor
     * o igual a cero, o mayor o igual a trece, la función retorna un error.
     *
     * @param int $cols El número de columnas a validar.
     * @return bool|array Devuelve true si el número de columnas esta dentro del rango aceptable,
     * o un array con información de error en caso contrario.
     * @version 16.2.0
     */
    final public function valida_cols(int $cols): true|array
    {
        if($cols<=0){
            return $this->error->error(mensaje: 'Error cols debe ser mayor a 0', data: $cols,es_final: true);
        }
        if($cols>=13){
            return $this->error->error(mensaje: 'Error cols debe ser menor o igual a  12', data: $cols,es_final: true);
        }
        return true;
    }

    /**
     * Método para validar si el nombre y el lugar de reserva proporcionados no están vacíos.
     *
     * @param string $name El nombre a validar. La cadena no debe ser vacía después de haber sido recortada.
     * @param string $place_holder El marcador de posición a validar. La cadena no debe estar vacía después de haber sido recortada.
     *
     * @return true|array True si ambas entradas son válidas, de lo contrario, se devuelve un conjunto de información de error.
     *
     * @final
     * @version 15.1.0
     * @url https://github.com/gamboamartin/template/wiki/template-src-directivas#m%C3%A9todo-valida_data_label---clase-directivas
     */
    final public function valida_data_label(string $name, string $place_holder): true|array
    {

        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error $name debe tener info', data: $name, es_final: true);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder debe tener info', data: $place_holder,
                es_final: true);
        }
        return true;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Valida si los valores de 'name' y 'place_holder' existen y no están vacíos.
     *
     * Esta función toma dos parámetros 'name' y 'place_holder' como entrada y realiza
     * una verificación. Si cualquiera de estas entradas está vacía, se genera un error.
     * Si ambos pasan la verificación, la función retorna verdadero.
     *
     * @param string $name Un nombre que se va a validar.
     * @param string $place_holder Un marcador de posición que se va a validar.
     *
     * @return bool|array Retorna true si tanto el 'name' como el 'place_holder' son válidos,
     * de lo contrario, devuelve el mensaje y el tipo de error.
     *
     *
     * @example
     * valida_etiquetas('Nombre', 'MarcadorDePosicion');
     * Este ejemplo retornará 'true' si tanto 'Nombre' como 'MarcadorDePosicion'
     * no están vacíos, de lo contrario retornará el mensaje y tipo de error.
     *
     * @version 16.12.0
     */
    private function valida_etiquetas(string $name, string $place_holder): true|array
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error el $name esta vacio', data: $name, es_final: true);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error el $place_holder esta vacio', data: $place_holder
                , es_final: true);
        }
        return true;
    }

    /**
     * Integra un value para input dando prioridad a un value
     * @param stdClass $init Objeto inicializado de input
     * @param string $name Name input
     * @param string|null|int|float $value Value del input puede ser nulo
     * @version 0.130.6
     */
    private function value_input(stdClass $init, string $name, string|null|int|float $value): float|int|string|null|array
    {
        if(!isset($init->row_upd)){
            return $this->error->error(mensaje: 'Error $init->row_upd no existe', data: $init);
        }
        if(!is_object($init->row_upd)){
            return $this->error->error(mensaje: 'Error $init->row_upd debe ser un objeto', data: $init);
        }
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name esta vacio', data: $name);
        }
        if(is_numeric($name)){
            return $this->error->error(mensaje: 'Error name debe ser un texto no un numero', data: $name);
        }
        if(!isset($init->row_upd->$name)){
            $init->row_upd->$name = '';
        }
        $value_input = $init->row_upd->$name;
        if(!is_null($value_input)){
            $value_input = $value;
        }
        return $value_input;
    }
}
