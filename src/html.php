<?php
namespace gamboamartin\template;
use base\frontend\params_inputs;
use base\orm\modelo;
use config\generales;
use config\views;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class html{
    protected errores $error;
    public function __construct(){
        $this->error = new errores();
    }

    /**
     * REG
     * Genera una alerta de éxito en formato HTML.
     *
     * Esta función toma un mensaje como parámetro y genera un bloque de código HTML con una alerta
     * de Bootstrap de tipo éxito. Si el mensaje proporcionado está vacío, se devolverá un error.
     * La alerta generada incluye un encabezado "Muy bien!" seguido del mensaje proporcionado.
     *
     * @param string $mensaje El mensaje que se mostrará en la alerta de éxito. Este debe ser una cadena
     *                       no vacía. Si el mensaje está vacío o contiene solo espacios, se generará
     *                       un error. El mensaje se mostrará dentro de una alerta de tipo `alert-success`
     *                       de Bootstrap.
     *
     * @return string|array Retorna una cadena con el HTML de la alerta de éxito si el mensaje es válido.
     *                      Si el mensaje está vacío, devuelve un arreglo con información sobre el error.
     *                      El arreglo contiene el mensaje de error y los datos relacionados con el error.
     *
     * @throws errores Si el mensaje está vacío, se lanza un error con el mensaje 'Error mensaje esta vacio'.
     *                 Además, se retornará un objeto `errores` con la información del error generado.
     *
     * Ejemplo de uso:
     *
     * ```php
     * $html = new Html();  // Crear una instancia de la clase Html
     * echo $html->alert_success('La operación se completó con éxito');
     * ```
     *
     * En el caso anterior, si el mensaje proporcionado es `'La operación se completó con éxito'`,
     * se generará el siguiente HTML:
     *
     * ```html
     * <div class='alert alert-success' role='alert'>
     *     <strong>Muy bien!</strong> La operación se completó con éxito.
     * </div>
     * ```
     *
     * Si el mensaje está vacío o solo contiene espacios, la función devolverá un error.
     * Por ejemplo, con el siguiente código:
     *
     * ```php
     * echo $html->alert_success('   ');  // Mensaje vacío o solo espacios
     * ```
     *
     * El resultado sería un error como el siguiente:
     * ```php
     * array(
     *     'mensaje' => 'Error mensaje esta vacio',
     *     'data' => '   ',
     *     'es_final' => true
     * )
     * ```
     *
     * En este caso, el mensaje está vacío y no se genera el HTML de la alerta.
     *
     * @version 1.0.0
     */
    final public function alert_success(string $mensaje): string|array
    {
        // Se eliminan los espacios en blanco al principio y al final del mensaje
        $mensaje = trim($mensaje);

        // Si el mensaje es vacío, se genera un error
        if ($mensaje === '') {
            return $this->error->error(
                mensaje: 'Error mensaje esta vacio',  // Mensaje de error
                data: $mensaje,                      // Datos relacionados con el error
                es_final: true                        // Indicador de que es un error final
            );
        }

        // Si el mensaje no está vacío, se genera la alerta HTML
        return "<div class='alert alert-success' role='alert'><strong>Muy bien!</strong> $mensaje.</div>";
    }


    /**
     * REG
     * Genera una alerta de advertencia en formato HTML.
     *
     * Esta función recibe un mensaje de advertencia y genera un bloque de código HTML
     * que representa una alerta de tipo 'alert-warning' utilizando Bootstrap. El mensaje
     * se presenta dentro de una alerta con el texto "Advertencia!" seguido del mensaje
     * proporcionado. Si el mensaje está vacío, la función devuelve un error.
     *
     * @param string $mensaje El mensaje de advertencia que se mostrará en la alerta.
     *                        Este parámetro debe ser una cadena no vacía.
     *                        Si el mensaje está vacío o contiene solo espacios, se generará un error.
     *
     * @return string|array Retorna una cadena con el HTML de la alerta si el mensaje es válido.
     *                      Si el mensaje está vacío, devuelve un arreglo con información sobre el error.
     *                      El arreglo contiene el mensaje de error y los datos relacionados con el error.
     *
     * @throws errores Si el mensaje está vacío, se lanza un error con el mensaje 'Error mensaje esta vacio'.
     *                 Además, se retornará un objeto `errores` con la información del error generado.
     *
     * Ejemplo de uso:
     *
     * ```php
     * $html = new Html();  // Crear una instancia de la clase Html
     * echo $html->alert_warning('Se ha producido un error en el proceso');
     * ```
     *
     * En el ejemplo anterior, si el mensaje proporcionado es `'Se ha producido un error en el proceso'`,
     * se generará el siguiente HTML:
     *
     * ```html
     * <div class='alert alert-warning' role='alert'>
     *     <strong>Advertencia!</strong> Se ha producido un error en el proceso.
     * </div>
     * ```
     *
     * Si el mensaje está vacío o solo contiene espacios, la función devolverá un error.
     * Por ejemplo, con el siguiente código:
     *
     * ```php
     * echo $html->alert_warning('   ');  // Mensaje vacío o solo espacios
     * ```
     *
     * El resultado sería un error como el siguiente:
     * ```php
     * array(
     *     'mensaje' => 'Error mensaje esta vacio',
     *     'data' => '   ',
     *     'es_final' => true
     * )
     * ```
     *
     * En este caso, el mensaje está vacío y no se genera el HTML de la alerta.
     *
     * @version 1.0.0
     */
    final public function alert_warning(string $mensaje): string|array
    {
        // Se eliminan los espacios en blanco al principio y al final del mensaje
        $mensaje = trim($mensaje);

        // Si el mensaje es vacío, se genera un error
        if ($mensaje === '') {
            return $this->error->error(
                mensaje: 'Error mensaje esta vacio',  // Mensaje de error
                data: $mensaje,                      // Datos relacionados con el error
                es_final: true                        // Indicador de que es un error final
            );
        }

        // Si el mensaje no está vacío, se genera la alerta HTML
        return "<div class='alert alert-warning' role='alert'><strong>Advertencia!</strong> $mensaje.</div>";
    }


    /**
     * REG
     * Genera un enlace HTML (`<a>`) para un botón con los parámetros proporcionados.
     *
     * Esta función recibe varios parámetros como la acción, etiqueta, ID de registro, sección, estilo y otros parámetros adicionales,
     * y genera un enlace HTML. El enlace se utilizará como un botón en la interfaz de usuario. Además, valida que los parámetros sean correctos.
     * Si los parámetros son válidos, genera un enlace HTML con los parámetros correspondientes, sino, retorna un mensaje de error.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que los parámetros `accion`, `etiqueta`, `seccion` y `style` no estén vacíos utilizando el método `valida_input`.
     * 2. Si la validación es exitosa, se obtiene el `session_id` de la sesión actual.
     * 3. Si el `session_id` está vacío, se genera un mensaje de error.
     * 4. Se construye una URL de enlace con los parámetros proporcionados y cualquier parámetro adicional.
     * 5. Se devuelve el HTML del enlace generado.
     *
     * **Parámetros:**
     *
     * @param string $accion La acción que se realizará cuando se haga clic en el botón.
     * @param string $etiqueta El texto que se mostrará en el botón.
     * @param int $registro_id El ID del registro que se utilizará para la acción.
     * @param string $seccion El nombre de la sección a la que pertenece el botón.
     * @param string $style El estilo CSS del botón.
     * @param array $params Parámetros adicionales que se agregarán a la URL como parámetros GET.
     *
     * **Retorno:**
     * - Devuelve el HTML de un enlace `<a>` con el estilo y parámetros proporcionados.
     * - Si ocurre un error durante la validación o generación, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Generación de un enlace válido**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $params = ['redirigir' => 'true'];
     * $resultado = $this->button_href($accion, $etiqueta, $registro_id, $seccion, $style, $params);
     * // Retorna: "<a href='index.php?seccion=usuarios&accion=guardar&registro_id=123&session_id=xyz&redirigir=true' class='btn-primary'>Guardar cambios</a>"
     * ```
     *
     * **Ejemplo 2: Error por `session_id` vacío**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $params = [];
     * $resultado = $this->button_href($accion, $etiqueta, $registro_id, $seccion, $style, $params);
     * // Si no hay `session_id` válido, retorna un mensaje de error.
     * ```
     *
     * **Ejemplo 3: Error por parámetro vacío**
     * ```php
     * $accion = "";
     * $etiqueta = "Guardar cambios";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $params = [];
     * $resultado = $this->button_href($accion, $etiqueta, $registro_id, $seccion, $style, $params);
     * // Retorna un mensaje de error: 'Error al validar datos'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function button_href(string $accion, string $etiqueta, int $registro_id, string $seccion,
                                string $style, array $params = array()): string|array
    {

        $valida = $this->valida_input(accion: $accion,etiqueta:  $etiqueta, seccion: $seccion,style:  $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $session_id = (new generales())->session_id;

        if($session_id === ''){
            return $this->error->error(mensaje: 'Error la $session_id esta vacia', data: $session_id, es_final: true);
        }

        $params_get = '';
        foreach ($params as $key=>$value){
            $params_get .= "&$key=$value";
        }

        $link = "index.php?seccion=$seccion&accion=$accion&registro_id=$registro_id&session_id=$session_id";
        $link .= $params_get;
        return /** @lang html */ "<a |role| href='$link' |class|>$etiqueta</a>";
    }

    private function class_css_html(array $class_css): array|string
    {
        $class_html = '';
        foreach ($class_css as $class){
            $class = trim($class);
            if($class === ''){
                return $this->error->error(mensaje: 'Error class vacio',data:  $class);
            }
            $class_html.=" $class ";
        }
        return trim($class_html);
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Esta función toma una columna, una descripción_select y una fila (array) como parámetros.
     * Verifica si la columna pasada está vacía, y de ser así, devuelve un error.
     * A continuación, valida la existencia de la columna en la fila.
     * Si la validación encuentra un error, devuelve un mensaje de error.
     * Luego, si la descripción_select no está vacía, agrega un espacio a la misma.
     * La descripción_select se concatena a continuación con el valor de la columna en la fila.
     * Finalmente, devuelve la descripción_select después de eliminar los espacios en blanco.
     *
     * @param string $column La columna que se buscará en la fila.
     * @param string $descripcion_select La cadena con la cual se concatenará el valor de la columna.
     * @param array $row La fila (array) en la que se buscará la columna.
     * @return string|array La descripción_select concatenada con el valor de la columna,
     *                       o un error en caso de que la columna esté vacía o no se encuentre en la fila.
     *
     * @version 17.17.0
     */
    private function concat_descripcion_select(string $column, string $descripcion_select, array $row): array|string
    {
        $column = trim($column);
        if($column === ''){
            return $this->error->error(mensaje: 'Error column esta vacia', data: $column);
        }
        $keys_val = array($column);
        $valida = (new validacion())->valida_existencia_keys($keys_val, $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar row', data: $valida);
        }
        $descripcion_select = trim($descripcion_select);
        $espacio = '';
        if($descripcion_select !== ''){
            $espacio = ' ';
        }
        $descripcion_select .= $espacio.trim($row[$column]);
        return trim($descripcion_select);

    }

    private function data_option(array $columns_ds, string $key_value_custom, array $row)
    {
        $row = $this->row_descripcion_select(columns_ds: $columns_ds,row:  $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar descripcion select', data: $row);
        }


        $keys = array('descripcion_select');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar row', data: $valida);
        }

        $value_custom = $this->value_custom(key_value_custom: $key_value_custom,row:  $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar value custom', data: $value_custom);
        }

        $data = new stdClass();
        $data->row = $row;
        $data->value_custom = $value_custom;

        return $data;
    }

    private function descripcion_select(array $columns_ds, array $row)
    {
        $descripcion_select = '';
        foreach ($columns_ds as $column){
            $column = trim($column);
            if($column === ''){
                return $this->error->error(mensaje: 'Error column esta vacia', data: $column);
            }
            $keys_val = array($column);
            $valida = (new validacion())->valida_existencia_keys($keys_val, $row);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al validar row', data: $valida);
            }
            $descripcion_select = $this->concat_descripcion_select(column: $column,
                descripcion_select:  $descripcion_select,row:  $row);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar descripcion select', data: $descripcion_select);
            }
        }
        return $descripcion_select;

    }

    /**
     * Genera un div con un label dentro del div
     * @param int $cols Numero de columnas css
     * @param string $contenido Contenido a integrar dentro del div
     * @return string|array
     * @version 0.50.1
     */
    final protected function div_control_group_cols(int $cols, string $contenido): string|array
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
     * Genera un div con una etiqueta
     * @param int $cols Numero de columnas css
     * @param string $contenido Contenido a integrar dentro del div
     * @param string $label Etiqueta a mostrar
     * @param string $name Name a utilizar como label
     * @return string|array
     * @version 0.69.4
     * @verfuncion 0.1.0
     * @fecha 2022-08-03 15:21
     * @author mgamboa
     */
    private function div_control_group_cols_label(int $cols, string $contenido, string $label, string $name): string|array
    {

        $label = trim($label);
        $name = trim($name);
        $valida = $this->valida_input_select(cols: $cols, label: $label, name: $name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar input', data: $valida);
        }

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

    /**
     * Integra el contenido de divs de tipo input
     * @param string $contenido Contenido a integrar en el div
     * @return string
     * @version 0.68.4
     * @verfuncion 0.1.0
     * @fecha 2022-08-03 15:13
     * @author mgamboa
     */
    private function div_controls(string $contenido): string
    {
        $div_controls_ini = "<div class='controls'>";
        $div_controls_fin = "</div>";

        return $div_controls_ini.$contenido.$div_controls_fin;
    }

    /**
     * REG
     * Crea un contenedor `div` con un contenido HTML, validando el número de columnas y limpiando el resultado.
     *
     * Esta función genera un `div` que contiene el contenido HTML proporcionado, el cual está envuelto en un número de columnas
     * determinado por el parámetro `$cols`. La función valida que el número de columnas sea adecuado y limpia el HTML generado
     * para asegurar que no haya espacios extra innecesarios.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que el número de columnas `$cols` sea válido utilizando el método `valida_cols` de la clase `directivas`.
     * 2. Se crea un contenedor `div` con el contenido HTML proporcionado.
     * 3. Se pasa el HTML generado a través de la función `limpia_salida` para eliminar espacios extra y corregir posibles errores de formato.
     * 4. Si ocurre un error durante la validación o la limpieza, se devuelve un mensaje de error con detalles sobre el problema.
     * 5. Si la limpieza es exitosa, se devuelve el HTML del contenedor `div` con el contenido.
     *
     * **Parámetros:**
     *
     * @param int $cols El número de columnas que se utilizarán en el contenedor `div`. Este parámetro es obligatorio y se valida.
     * @param string $html El contenido HTML que se incluirá dentro del `div`. Este parámetro es obligatorio y debe ser una cadena de texto.
     *
     * **Retorno:**
     * - Devuelve el HTML de un contenedor `div` que contiene el contenido HTML proporcionado si todo es válido.
     * - Si ocurre un error durante la validación o la limpieza, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Creación de un contenedor div válido**
     * ```php
     * $cols = 6;
     * $html = "<p>Texto dentro del div</p>";
     * $resultado = $this->div_group($cols, $html);
     * // Retorna: "<div class='col-6'><p>Texto dentro del div</p></div>"
     * ```
     *
     * **Ejemplo 2: Error por número de columnas inválido**
     * ```php
     * $cols = -1;
     * $html = "<p>Texto dentro del div</p>";
     * $resultado = $this->div_group($cols, $html);
     * // Retorna un mensaje de error: 'Error al validar cols'.
     * ```
     *
     * **Ejemplo 3: Error durante la limpieza del HTML**
     * ```php
     * $cols = 6;
     * $html = "<p>Texto con problemas</p>";
     * $resultado = $this->div_group($cols, $html);
     * // Si ocurre un error durante la limpieza, se retorna un mensaje de error.
     * ```
     *
     * **@version 1.0.0**
     */
    public function div_group(int $cols, string $html): string|array
    {
        // Validación del número de columnas
        $valida = (new directivas(html: $this))->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        // Creación del HTML del div con el contenido proporcionado
        $html_r = /** @lang html */
            "<div |class|>$html</div>";

        // Limpiar el HTML generado
        $html_r = $this->limpia_salida(html: $html_r);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar salida', data: $html_r);
        }

        // Retorno del HTML limpio del div
        return $html_r;
    }


    /**
     * REG
     * Genera un div que contiene un HTML proporcionado y una etiqueta.
     *
     * Esta función recibe un fragmento de HTML y una etiqueta, y genera un contenedor `<div>` que envuelve el HTML.
     * Luego, limpia el resultado utilizando la función `limpia_salida` para asegurar que no haya espacios extra innecesarios en el HTML generado.
     *
     * **Pasos de procesamiento:**
     * 1. Se crea una estructura de `div` con el contenido de la etiqueta y el HTML proporcionado.
     * 2. El resultado se pasa a través de la función `limpia_salida` para eliminar espacios adicionales y corregir posibles problemas de formato.
     * 3. Si hay un error al limpiar la salida, se devuelve un error con el mensaje correspondiente.
     * 4. Si la limpieza es exitosa, se devuelve el HTML limpio y formateado correctamente.
     *
     * **Parámetros:**
     *
     * @param string $html El contenido HTML que se incluirá dentro del `<div>`. Este parámetro es obligatorio y debe ser una cadena de texto con el HTML a mostrar.
     * @param string $label El contenido que se mostrará como una etiqueta antes del contenido HTML. Este parámetro también es obligatorio.
     *
     * **Retorno:**
     * - Devuelve un string con el código HTML de un `<div>` que incluye la etiqueta proporcionada y el contenido HTML.
     * - Si ocurre algún error durante el proceso de limpieza del HTML, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Crear un div con contenido HTML**
     * ```php
     * $html = "<p>Texto dentro del div</p>";
     * $label = "<label>Etiqueta del div</label>";
     * $resultado = $this->div_label($html, $label);
     * // Retorna: "<label>Etiqueta del div</label><div |class|><p>Texto dentro del div</p></div>"
     * ```
     *
     * **Ejemplo 2: Error durante la limpieza de la salida**
     * ```php
     * $html = "<p>Texto con problemas</p>";
     * $label = "<label>Etiqueta</label>";
     * $resultado = $this->div_label($html, $label);
     * // Si la función limpia_salida devuelve un error, se retorna un mensaje de error.
     * ```
     *
     * **@version 1.0.0**
     */
    public function div_label(string $html, string $label): string
    {
        // Crear el contenido del div con la etiqueta proporcionada
        $div_r = /** @lang html */
            $label."<div |class|>$html</div>";

        // Limpiar el HTML resultante utilizando la función limpia_salida
        $div_r = $this->limpia_salida(html: $div_r);
        if(errores::$error){
            // Si ocurre un error durante la limpieza, retornar un mensaje de error
            return $this->error->error(mensaje: 'Error al limpiar salida', data: $div_r);
        }

        // Si todo va bien, retornar el HTML limpio
        return $div_r;
    }


    /**
     * Genera un div de tipo select
     * @param string $name Name input
     * @param string $options_html Options en html
     * @param array $class_css Class css nuevas
     * @param bool $disabled Si disabled el input quedara disabled
     * @param string $id_css Si existe lo cambia por el name
     * @param bool $required si required integra requieren en select
     * @return array|string
     */
    public function div_select(string $name, string $options_html, array $class_css = array(), bool $disabled = false,
                               string $id_css = '', bool $required = false): array|string
    {
        $required_html = (new params_inputs())->required_html(required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'La asignacion de required es incorrecta', data: $required_html);
        }

        $disabled_html = (new params_inputs())->disabled_html(disabled: $disabled);
        if(errores::$error){
            return $this->error->error(mensaje: 'La asignacion de disabled es incorrecta', data: $disabled_html);
        }

        $class_html = $this->class_css_html(class_css: $class_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar class css', data: $class_html);
        }

        $id_css = trim($id_css);
        if($id_css === ''){
            $id_css = trim($name);
        }


        $select_in  ="<select class='form-control selectpicker color-secondary $name $class_html' ";
        $select_in  .="data-live-search='true' id='$id_css' name='$name' ";
        $select_in  .="$required_html $disabled_html>";

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
     * @final rev
     */
    public function email(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
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

        $val = new validacion();
        if (!isset($val->patterns['correo_html5'])) {
            return $this->error->error(mensaje: 'No existe el regex para email', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$params->id_css' placeholder='$params->place_holder' pattern='".$val->patterns['correo_html_base']."' />";
        return $html;
    }

    /**
     * Genera extra params para integrar con html
     * @param array $extra_params Conjunto de extra params key = data value = valor
     * @return array|string
     * @version 0.61.4
     * @verfuncion 0.1.0
     * @fecha 2022-08-03 09:59
     * @author mgamboa
     */
    private function extra_params(array $extra_params): array|string
    {
        $extra_params_html = '';
        foreach ($extra_params as $data=>$val){
            if(is_numeric($data)){
                return $this->error->error(mensaje: 'Error $data bede ser un texto valido', data: $extra_params);
            }

            $extra_params_html.= " data-$data = '$val'";
        }
        return $extra_params_html;
    }

    private function extra_param_data(array $extra_params_key, array $row): array
    {
        $extra_params = array();
        foreach ($extra_params_key as $key_extra_param){
            $key_extra_param = trim($key_extra_param);
            if($key_extra_param === ''){
                return $this->error->error(mensaje: 'Error key_extra_param esta vacio', data: $key_extra_param);
            }
            if(!isset($row[$key_extra_param])){
                $row[$key_extra_param] = 'SIN DATOS';
            }
            $extra_params[$key_extra_param] = $row[$key_extra_param];
        }
        return $extra_params;
    }

    /**
     * Obtiene el html de una fecha
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador de tipo css
     * @param string $name Nombre del input
     * @param string $place_holder Contenido a mostrar previo a la captura del input
     * @param bool $required Atributo required
     * @param mixed $value Valor a integrar
     * @param bool $value_hora te integra date time si value hora es true
     * @return array|string
     * @finalrev
     * @version 0.31.1
     */
     public function fecha(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                          mixed $value, bool $value_hora = false): array|string
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

        $type = 'date';
        if($value_hora){
            $type = 'datetime-local';
        }

        $html = "<input type='$type' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$params->id_css' placeholder='$params->place_holder' />";
        return $html;
    }

    /**
     * Genera un input de tipo file
     * @param bool $disabled attr disabled
     * @param string $id_css identificador css
     * @param string $name Name input
     * @param string $place_holder attr place holder
     * @param bool $required attr required
     * @param mixed $value value input
     * @return string|array
     */
    final public function file(bool $disabled, string $id_css, string $name,
                               string $place_holder, bool $required, mixed $value, bool $multiple = false): string|array
    {

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $params = $this->params_txt(disabled: $disabled,id_css:  $id_css,name:  $name,place_holder:  $place_holder,
            required:  $required, multiple: $multiple);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='file' name='$params->name' value='$value' class = 'form-control' $params->disabled $params->required ";
        $html.= "id='$id_css' $params->multiple />";
        return $html;
    }

    /**
     * Integra los options en forma de html
     * @param string $descripcion_select Descripcion del option
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $options_html Options previamente generados en html
     * @param mixed $value Valor a asignar en option
     * @param array $extra_params Conjunto de datos para extra params
     * @return array|string
     * @version 0.65.4
     * @verfuncion 0.1.0
     * @fecha 2022-08-03 12:25
     * @author mgamboa
     */
    private function integra_options_html(string $descripcion_select, int|null|string|float $id_selected,
                                          string $options_html, int|null|string|float $value,
                                          array $extra_params = array()): array|string
    {

        $option_html = $this->option_html(descripcion_select: $descripcion_select, id_selected: $id_selected,
            value: $value, extra_params: $extra_params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar option', data: $option_html);
        }

        $options_html.=$option_html;

        return $options_html;
    }

    /**
     * REG
     * Valida los parámetros `$id_css` y `$place_holder`, asegurándose de que ambos no estén vacíos.
     *
     * Esta función valida los valores proporcionados para los parámetros `$id_css` y `$place_holder`. Ambos parámetros
     * deben ser cadenas no vacías. Si alguno de los parámetros está vacío, se genera un error con un mensaje descriptivo.
     * Si ambos parámetros son válidos, la función genera un HTML de etiqueta (aunque actualmente solo retorna una cadena vacía).
     *
     * **Pasos de validación:**
     * 1. Se elimina cualquier espacio en blanco al principio y al final de los valores de `$id_css` y `$place_holder`.
     * 2. Se valida que `$id_css` no esté vacío.
     * 3. Se valida que `$place_holder` no esté vacío.
     * 4. Si alguna de las validaciones falla, se genera un error con un mensaje descriptivo.
     * 5. Si ambas validaciones son correctas, se retorna una cadena vacía, ya que el código actual no genera la etiqueta HTML.
     *
     * **Parámetros:**
     *
     * @param string $id_css El identificador CSS de la etiqueta. Este parámetro es obligatorio y no debe estar vacío.
     *                       Se utiliza para generar un identificador único en el HTML.
     * @param string $place_holder El texto que se mostrará como marcador de posición dentro del campo de entrada.
     *                             Este parámetro es obligatorio y no debe estar vacío.
     *
     * **Retorno:**
     * - Devuelve una cadena vacía si ambos parámetros no están vacíos y son válidos.
     * - Si alguno de los parámetros está vacío, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $id_css = "usuario_id";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label($id_css, $place_holder);
     * // Retorna "" porque ambos parámetros son válidos.
     * ```
     *
     * **Ejemplo 2: Error por $id_css vacío**
     * ```php
     * $id_css = "";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label($id_css, $place_holder);
     * // Retorna un arreglo de error: 'Error el $id_css esta vacio'.
     * ```
     *
     * **Ejemplo 3: Error por $place_holder vacío**
     * ```php
     * $id_css = "usuario_id";
     * $place_holder = "";
     * $resultado = $this->label($id_css, $place_holder);
     * // Retorna un arreglo de error: 'Error el $place_holder esta vacio'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function label(string $id_css, string $place_holder): string|array
    {
        // Eliminar espacios en blanco al principio y al final de los valores
        $id_css = trim($id_css);
        if($id_css === ''){
            return $this->error->error(mensaje: 'Error el $id_css esta vacio', data: $id_css, es_final: true);
        }

        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error el $place_holder esta vacio', data: $place_holder, es_final: true);
        }

        // Actualmente no se genera ninguna etiqueta HTML, solo se retorna una cadena vacía
        return "";
    }


    /**
     * REG
     * Limpia el contenido HTML eliminando los espacios adicionales.
     *
     * Esta función recibe un fragmento de HTML y reemplaza múltiples espacios consecutivos por un solo espacio.
     * Además, realiza un reemplazo específico para corregir las secuencias de `  /` a ` /` en el HTML.
     * Esto asegura que el HTML sea más limpio y consistente para su uso posterior, evitando problemas de formato.
     *
     * **Pasos de procesamiento:**
     * 1. Reemplaza los espacios consecutivos (dos o más espacios) por un solo espacio, haciendo que el HTML sea más compacto.
     * 2. Realiza un reemplazo adicional para corregir las secuencias de `  /` en los atributos HTML, convirtiéndolas a ` /`.
     * 3. El resultado es un HTML más limpio con menos espacio innecesario.
     *
     * **Parámetros:**
     *
     * @param string $html El fragmento de HTML que se va a limpiar. Este parámetro es obligatorio y debe contener una cadena de texto que represente el HTML a procesar.
     *
     * **Retorno:**
     * - Devuelve el HTML limpio con los espacios consecutivos reducidos y las secuencias `  /` corregidas.
     * - Si se pasa un HTML vacío o mal formado, la función devolverá el HTML tal cual sin modificaciones.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Limpiar HTML con espacios consecutivos**
     * ```php
     * $html = "<div  class='container'>  <p>Texto de ejemplo</p>  </div>";
     * $resultado = $this->limpia_salida($html);
     * // Retorna: "<div class='container'> <p>Texto de ejemplo</p> </div>"
     * ```
     *
     * **Ejemplo 2: Limpiar HTML con secuencias de `  /`**
     * ```php
     * $html = "<img src='image.jpg'  / >";
     * $resultado = $this->limpia_salida($html);
     * // Retorna: "<img src='image.jpg' / >"
     * ```
     *
     * **Ejemplo 3: HTML sin cambios**
     * ```php
     * $html = "<p>Texto limpio</p>";
     * $resultado = $this->limpia_salida($html);
     * // Retorna: "<p>Texto limpio</p>"
     * ```
     *
     * **@version 1.0.0**
     */
    final public function limpia_salida(string $html): array|string
    {
        // Reemplaza múltiples espacios consecutivos por un solo espacio
        $html_r = str_replace('  ', ' ', $html);
        $html_r = str_replace('  ', ' ', $html_r);
        $html_r = str_replace('  ', ' ', $html_r);
        $html_r = str_replace('  ', ' ', $html_r);
        $html_r = str_replace('  ', ' ', $html_r);

        // Realiza un reemplazo específico para corregir las secuencias de "  /" a " /"
        return str_replace('  /', ' /', $html_r);
    }


    /**
     * Genera un link en el menu lateral con un numero
     * @param string $etiqueta Etiqueta a mostrar del menu
     * @param string $number Numero de etiqueta
     * @return array|string
     */
    final public function link_menu_lateral(string $etiqueta, string $number): array|string
    {
        $number_html = $this->number_menu_lateral(number: $number);
        if(errores::$error){
            return $this->error->error(mensaje:  'Error al obtener numero ', data: $number_html);
        }
        $txt_link = $this->menu_lateral(etiqueta: $etiqueta);
        if(errores::$error){
            return $this->error->error(mensaje:  'Error al generar link', data: $txt_link);
        }

        return $number_html.$txt_link;

    }

    /**
     * Genera un texto de menu lateral
     * @param string $etiqueta Etiqueta del menu
     * @return string|array
     * @version 0.96.4
     */
    final public function menu_lateral(string $etiqueta): string|array
    {
        $etiqueta = trim($etiqueta);
        if($etiqueta === ''){
            return $this->error->error(mensaje: 'Error la etiqueta esta vacia', data: $etiqueta);
        }
        return "<span class='texto-menu-lateral'>$etiqueta</span>";
    }

    /**
     *  Integra un input de tipo monto
     * @param bool $disabled Atributo disabled si true
     * @param string $id_css Css
     * @param string $name Atributo name
     * @param string $place_holder Atributo place holder1
     * @param bool $required Atributo required si true
     * @param mixed $value Value input
     * @return array|string
     * @final rev
     * @version 6.25.2
     */
    public function monto(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
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

        $html = "<input type='text' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= "id='$params->id_css' placeholder='$params->place_holder' />";
        return $html;
    }

    /**
     * Genera un numero en img para menu lateral
     * @param string $number numero
     * @return string|array
     * @version 0.100.4
     */
    private function number_menu_lateral(string $number): string|array
    {
        $number = trim($number);
        if($number === ''){
            return $this->error->error(mensaje: 'Error number vacio', data: $number);
        }
        $img =  (new views())->url_assets."img/numeros/$number.svg";
        return "<img src='$img' class='numero'>";
    }

    /**
     * Genera un option para un select
     * @param string $descripcion descripcion del option
     * @param bool $selected Si selected se anexa selected a option
     * @param mixed $value Value del option
     * @param array $extra_params Arreglo con datos para integrar un extra param
     * @return string|array
     * @version 0.62.4
     * @verfuncion 0.1.0
     * @fecha 2022-08-03 10:59
     * @author mgamboa
     */
    private function option(string $descripcion, bool $selected, int|string $value, array $extra_params = array()): string|array
    {

        $valida = $this->valida_option(descripcion: $descripcion, value: $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar option', data: $valida);
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

    private function option_con_extra_param(array $extra_params_key, int|null|string|float $id_selected,
                                           string $options_html_, array $row, int|string|float|null $row_id,
                                           string|int|float $value_custom){
        $keys = array('descripcion_select');
        $valida = (new validacion())->valida_existencia_keys(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar row', data: $valida);
        }

        $extra_params = $this->extra_param_data(extra_params_key: $extra_params_key,row:  $row);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar extra params', data: $extra_params);
        }
        $value = $this->value_select(row_id: $row_id,value_custom:  $value_custom);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar value', data: $value);
        }

        $options_html_ = $this->integra_options_html(descripcion_select: $row['descripcion_select'],
             id_selected: $id_selected, options_html: $options_html_, value: $value, extra_params: $extra_params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar option', data: $options_html_);
        }
        return $options_html_;
    }

    /**
     * Genera un option en forma de html
     * @param string $descripcion_select Descripcion a mostrar en option
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param mixed $value Valor de asignacion a option
     * @param array $extra_params Conjunto de datos para integrar un extra param en un option
     * @return array|string
     * @version 0.63.4
     * @verfuncion 0.1.0
     * @fecha 2022-08-03 12:03
     * @author mgamboa
     */
    private function option_html(string $descripcion_select, int|null|string|float $id_selected,
                                 int|null|string|float $value, array $extra_params = array()): array|string
    {
        $descripcion_select = trim($descripcion_select);
        if($descripcion_select === ''){
            return $this->error->error(mensaje: 'Error $descripcion_select no puede venir vacio',
                data: $descripcion_select);
        }
        $value = trim($value);
        if($value === ''){
            $value = -1;
        }

        $value = trim($value);
        $selected = $this->selected(value: $value,id_selected: $id_selected);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al verificar selected', data: $selected);
        }

        $option_html = $this->option(descripcion: $descripcion_select,selected:  $selected, value: $value,
            extra_params: $extra_params);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar option', data: $option_html);
        }
        return $option_html;
    }

    /**
     * Integra todos los options de un html select
     * @param array $columns_ds Columnas a integrar a descripcion de option
     * @param array $extra_params_key keys de extra params para integrar valor
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $key_value_custom
     * @param array $values Valores para options
     * @return array|string
     * @author mgamboa
     */
    private function options(
        array $columns_ds, array $extra_params_key, int|float|string|null $id_selected,
        string $key_value_custom, array $values): array|string
    {

        $options_html = $this->option(descripcion: 'Selecciona una opcion',selected:  false, value: -1);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar option', data: $options_html);
        }
        $options_html = $this->options_html_data(columns_ds: $columns_ds, extra_params_key: $extra_params_key,
            id_selected: $id_selected, key_value_custom: $key_value_custom, options_html: $options_html,
            values: $values);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar options', data: $options_html);
        }
        return $options_html;
    }

    /**
     * Integra el html de un conjunto de options
     * @param array $columns_ds Columnas a integrar a descripcion de option
     * @param array $extra_params_key Conjunto de keys para asignar el valor e integrar un extra param basado en el
     * valor puesto
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $key_value_custom
     * @param string $options_html Options previos en html
     * @param array $values Valores para asignacion y generacion de options
     * @return array|string
     */
    private function options_html_data(array $columns_ds, array $extra_params_key, int|null|string|float $id_selected,
                                       string $key_value_custom, string $options_html, array $values): array|string
    {

        $options_html_ = $options_html;
        foreach ($values as $row_id=>$row){
            if(!is_array($row)){
                return $this->error->error(mensaje: 'Error el row debe ser un array', data: $row);
            }

            $data_option = $this->data_option(columns_ds: $columns_ds,key_value_custom:  $key_value_custom,row:  $row);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar data option', data: $data_option);
            }

            $options_html_ = $this->option_con_extra_param(extra_params_key: $extra_params_key,
                id_selected: $id_selected, options_html_: $options_html_, row: $data_option->row, row_id: $row_id,
                value_custom: $data_option->value_custom);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al generar option', data: $options_html_);
            }
        }
        return $options_html_;
    }

    /**
     * Genera in input de tipo password
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador de tipo css
     * @param string $name Nombre del input
     * @param string $place_holder Contenido a mostrar previo a la captura del input
     * @param bool $required Si required aplica required en html
     * @param mixed $value Valor precargado
     * @return string|array
     * @version 0.108.4
     */
    final public function password(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                         mixed $value): string|array
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

        $html = "<input type='password' name='$params->name' value='$value' class='form-control' ";
        $html .= " $params->disabled $params->required ";
        $html.= "id='$id_css' placeholder='$params->place_holder' />";
        return $html;
    }


    /**
     * Genera y valida los parametros de in input tipo text
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador de tipo css
     * @param string $name Nombre del input
     * @param string $place_holder Contenido a mostrar previo a la captura del input
     * @param bool $required Si required aplica required en html
     * @param array $class_css Integra clases css
     * @param array $ids_css
     * @param string $regex Integra un regex para atributo pattern del input
     * @param string $title Title de input
     * @return array|stdClass
     */
    private function params_txt(bool $disabled, string $id_css, string $name,string $place_holder,
                                bool $required, array $class_css = array(),bool $multiple = false, array $ids_css = array(),
                                string $regex = '', string $title = ''): array|stdClass
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

        $multiple_html = (new params_inputs())->multiple_html(multiple: $multiple);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar $multiple_html', data: $multiple_html);
        }

        $regex_html = (new params_inputs())->regex_html(regex: $regex);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar regex_html', data: $regex_html);
        }

        $title_html = (new params_inputs())->title_html(place_holder: $place_holder, title: $title);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar title_html', data: $title_html);
        }

        $class_html = (new params_inputs())->class_html(class_css: $class_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar class_html', data: $class_html);
        }
        $ids_css[] = $id_css;
        $ids_css_html = (new params_inputs())->ids_html($ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar id_css', data: $ids_css_html);
        }

        $params = new stdClass();
        $params->name = $name;
        $params->id_css = $id_css;
        $params->place_holder = $place_holder;
        $params->disabled = $disabled_html;
        $params->required = $required_html;
        $params->regex = $regex_html;
        $params->title = $title_html;
        $params->class = $class_html;
        $params->ids_css_html = $ids_css_html;
        $params->multiple = $multiple_html;

        return $params;
    }

    private function row_descripcion_select(array $columns_ds, array $row)
    {
        if(count($columns_ds) > 0){
            $descripcion_select = $this->descripcion_select(columns_ds: $columns_ds,row:  $row);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar descripcion select', data: $descripcion_select);
            }
            $row['descripcion_select'] = trim($descripcion_select);
        }
        return $row;

    }

    /**
     * Genera un input de tipo select
     * @param int $cols Numero de columnas css
     * @param mixed $id_selected Id o valor a comparar origen de la base de valor
     * @param string $label Etiqueta a mostrar
     * @param string $name Name input
     * @param array $values Valores para options
     * @param array $class_css Class estar para css
     * @param array $columns_ds Columnas a integrar a descripcion de option
     * @param bool $disabled Si disabled el input quedara disabled
     * @param array $extra_params_key keys de extra params para integrar valor
     * @param string $id_css Identificador css si esta vacio integra en name
     * @param string $key_value_custom
     * @param bool $required if required integra required a select
     * @return array|string
     * @author mgamboa
     */
    final public function select(int $cols, int|float|string|null $id_selected, string $label, string $name,
                                 array $values, array $class_css = array(), array $columns_ds = array(),
                                 bool $disabled = false, array $extra_params_key = array(), string $id_css = '',
                                 string $key_value_custom = '', bool $required = false): array|string
    {

        $label = trim($label);
        $name = trim($name);
        $valida = $this->valida_input_select(cols: $cols, label: $label, name: $name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar input', data: $valida);
        }

        $options_html = $this->options(columns_ds: $columns_ds, extra_params_key: $extra_params_key,
            id_selected: $id_selected, key_value_custom: $key_value_custom, values: $values);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar options', data: $options_html);
        }

        $select = $this->select_html(cols: $cols, label: $label, name: $name, options_html: $options_html,
            class_css: $class_css, disabled: $disabled, id_css: $id_css, required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar contenedor', data: $select);
        }

        return $select;

    }

    /**
     * Genera un select en forma de html completo
     * @param int $cols Numero de columnas css
     * @param string $label Etiqueta a mostrar
     * @param string $name Name input
     * @param string $options_html Options precargados para select
     * @param array $class_css Class extra
     * @param bool $disabled Si disabled el input quedara inactivo
     * @param string $id_css Si existe lo integra en lugar del name
     * @param bool $required Si required se integra required como atributo del input
     * @return array|string
     */
    private function select_html(int $cols, string $label, string $name, string $options_html,
                                 array $class_css = array(), bool $disabled = false, string $id_css = '',
                                 bool $required = false): array|string
    {

        $label = trim($label);
        $name = trim($name);
        $valida = $this->valida_input_select(cols: $cols, label: $label, name: $name);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar input', data: $valida);
        }

        $select = $this->div_select(name: $name, options_html: $options_html, class_css: $class_css,
            disabled: $disabled, id_css: $id_css, required: $required);
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
    final protected function selected(int|null|string|float $value, int|null|string|float $id_selected): bool
    {
        $selected = false;
        if((string)$value === (string)$id_selected){
            $selected = true;
        }
        return $selected;
    }

    final public function submit(string $css, string $label): string|array
    {
        $css = trim($css);
        if($css === ''){
            return $this->error->error(mensaje: 'Error css esta vacio', data: $css);
        }
        $label = trim($label);
        if($label === ''){
            return $this->error->error(mensaje: 'Error label esta vacio', data: $label);
        }
        $btn = "<div class='control-group btn-modifica'>";
        $btn .= "<div class='controls'>";
        $btn .= "<button type='submit' class='btn btn-$css'>$label</button><br>";
        $btn .= "</div>";
        $btn .= "</div>";

        return $btn;

    }

    /**
     * Genera un input de tipo telefono
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador css
     * @param string $name Name input html
     * @param string $place_holder Muestra elemento en input
     * @param bool $required indica si es requerido o no
     * @param mixed $value Valor en caso de que exista
     * @return string|array
     * @version 0.112.4
     */
    final public function telefono(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                             mixed $value): string|array
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

        $valida = (new validacion());
        $keys = array('telefono_mx_html');
        $valida = (new validacion())->valida_existencia_keys(keys:$keys,registro:  $valida->patterns);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar patterns', data: $valida);
        }

        $regex = (new validacion())->patterns['telefono_mx_html'];

        $html = "<input type='text' name='$params->name' value='$value' class='form-control' ";
        $html .= " $params->disabled $params->required ";
        $html.= "id='$id_css' placeholder='$params->place_holder' pattern='$regex' />";
        return $html;
    }


    /**
     * Genera um input text basado en los parametros enviados
     * @param bool $disabled Si disabled retorna text disabled
     * @param string $id_css Identificador css
     * @param string $name Name input html
     * @param string $place_holder Muestra elemento en input
     * @param bool $required indica si es requerido o no
     * @param mixed $value Valor en caso de que exista
     * @param mixed $regex Integra regex a pattern
     * @param array $ids_css Integra los identificadores css
     * @return string|array Html en forma de input text
     * @version 0.9.0
     * @final rev
     */
    public function text(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                         mixed $value, array $ids_css = array(), string $regex = '', string $title = ''): string|array
    {



        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, ids_css: $ids_css, regex: $regex, title: $title);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' |class| $params->disabled $params->required ";
        $html.= $params->ids_css_html." placeholder='$params->place_holder' $params->regex $params->title />";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }

    public function textarea(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                             mixed $value, array $ids_css = array()): string|array
    {
        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, ids_css: $ids_css);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<textarea name='$params->name' class='form-control' $params->disabled $params->required ";
        $html.= $params->ids_css_html." placeholder='$params->place_holder'/>";
        $html.= $value . "</textarea>";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }


    final public function text_base(bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
                                    mixed $value, array $class_css = array(), array $ids_css = array(),
                                    string $regex = '', string $title = ''): string|array
    {

        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, class_css: $class_css, ids_css: $ids_css, regex: $regex, title: $title);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' $params->class $params->disabled $params->required ";
        $html.= $params->ids_css_html." placeholder='$params->place_holder' $params->regex $params->title />";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }

    /**
     * Genera un input type de texto con clases asignadas
     * @param array $class_css Clases css a integrar
     * @param bool $disabled if disabled input disabled
     * @param string $id_css Ids a integrar
     * @param string $name name input
     * @param string $place_holder marca agua input
     * @param bool $required atributo required si es verdadero
     * @param mixed $value Valor
     * @param string $regex validacion de input
     * @param string $title titulo input
     * @return string|array
     * @version 8.25.0
     */
    final public function text_class(
        array $class_css, bool $disabled, string $id_css, string $name, string $place_holder, bool $required,
        mixed $value, string $regex = '', string $title = ''): string|array
    {

        $name = trim($name);
        $place_holder = trim($place_holder);
        $id_css = trim($id_css);
        if($place_holder === ''){
            $place_holder = $name;
            $place_holder = str_replace('_', $place_holder, $place_holder);
            $place_holder = ucwords($place_holder);
        }
        if($id_css === ''){
            $id_css = $name;
        }

        $valida = $this->valida_params_txt(id_css: $id_css,name:  $name,place_holder:  $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        $params = $this->params_txt(disabled: $disabled, id_css: $id_css, name: $name, place_holder: $place_holder,
            required: $required, class_css: $class_css, regex: $regex, title: $title);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar parametros', data: $params);
        }

        $html = "<input type='text' name='$params->name' value='$value' $params->class $params->disabled $params->required ";
        $html.= "id='$id_css' placeholder='$params->place_holder' $params->regex $params->title />";

        $html_r = $this->limpia_salida(html: $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al limpiar html', data: $html_r);
        }

        return $html_r;
    }



    /**
     * REG
     * Valida los datos de entrada para los campos `accion`, `etiqueta`, `seccion` y `style`.
     *
     * Esta función asegura que los valores proporcionados para los parámetros `accion`, `etiqueta`, `seccion` y `style`
     * no estén vacíos. Si alguno de estos valores está vacío, se genera un error con un mensaje específico.
     *
     * **Pasos de validación:**
     * 1. Se elimina cualquier espacio en blanco al principio y al final de los valores de los parámetros.
     * 2. Se valida que cada parámetro no esté vacío.
     * 3. Si alguno de los parámetros está vacío, se genera un error con un mensaje detallado.
     * 4. Si todas las validaciones son exitosas, se devuelve `true`.
     *
     * **Parámetros:**
     *
     * @param string $accion La acción que se va a realizar. Este parámetro es obligatorio y no debe estar vacío.
     *                       Representa la acción que se llevará a cabo en la interfaz de usuario.
     * @param string $etiqueta El texto que se mostrará como etiqueta del botón o campo. Este parámetro también es obligatorio
     *                         y no debe estar vacío.
     * @param string $seccion El nombre de la sección donde se llevará a cabo la acción. Este parámetro es obligatorio y no puede
     *                        estar vacío.
     * @param string $style El estilo CSS asociado al botón o campo. Este parámetro es obligatorio y no debe estar vacío.
     *
     * **Retorno:**
     * - Devuelve `true` si todos los parámetros están presentes y no están vacíos.
     * - Si alguno de los parámetros está vacío, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $seccion = "usuarios";
     * $style = "btn-primary";
     *
     * $resultado = $this->valida_input($accion, $etiqueta, $seccion, $style);
     * // Retorna true porque todos los parámetros son válidos.
     * ```
     *
     * **Ejemplo 2: Error por parámetro vacío**
     * ```php
     * $accion = "";
     * $etiqueta = "Guardar cambios";
     * $seccion = "usuarios";
     * $style = "btn-primary";
     *
     * $resultado = $this->valida_input($accion, $etiqueta, $seccion, $style);
     * // Retorna un arreglo de error: 'Error la $accion esta vacia'.
     * ```
     *
     * **Ejemplo 3: Error por parámetro vacío (etiqueta)**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "";
     * $seccion = "usuarios";
     * $style = "btn-primary";
     *
     * $resultado = $this->valida_input($accion, $etiqueta, $seccion, $style);
     * // Retorna un arreglo de error: 'Error la $etiqueta esta vacia'.
     * ```
     *
     * **@version 1.0.0**
     */
    final public function valida_input(string $accion, string $etiqueta, string $seccion, string $style): true|array
    {
        $seccion = trim($seccion);
        if($seccion === ''){
            return $this->error->error(mensaje: 'Error la $seccion esta vacia', data: $seccion, es_final: true);
        }
        $accion = trim($accion);
        if($accion === ''){
            return $this->error->error(mensaje: 'Error la $accion esta vacia', data: $accion, es_final: true);
        }
        $style = trim($style);
        if($style === ''){
            return $this->error->error(mensaje: 'Error la $style esta vacia', data: $style, es_final: true);
        }
        $etiqueta = trim($etiqueta);
        if($etiqueta === ''){
            return $this->error->error(mensaje: 'Error la $etiqueta esta vacia', data: $etiqueta, es_final: true);
        }
        return true;
    }


    /**
     * POR DOCUMENTAR EN WIKI
     * Valida los parámetros de entrada para un elemento select en desarrollador de formularios HTML.
     *
     * @param int $cols El número de columnas para el elemento select.
     * @param string $label La etiqueta para el elemento select.
     * @param string $name El nombre para el elemento select.
     * @return true|array Devuelve verdadero en caso de éxito. En caso de error, devuelve un array con el detalle del error.
     * @version 17.2.0
     */
    final protected function valida_input_select(int $cols, string $label, string $name): true|array
    {
        $label = trim($label);
        if($label === ''){
            return $this->error->error(mensaje: 'Error el $label esta vacio', data: $label);
        }
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error el $name esta vacio', data: $name);
        }
        $valida = (new directivas(html:$this))->valida_cols(cols:$cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }
        return true;
    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Función para validar las opciones de una instancia.
     *
     * Esta función realiza la validación de las opciones dadas para
     * una cierta acción. Las validaciones realizadas incluyen:
     * - Se verifica si el valor no es vacío.
     * - Se verifica si la descripción no es vacía.
     *
     * @param string $descripcion La descripción de la opción.
     * @param int|string $value El valor de la opción.
     *
     * @return true|array Devuelve verdadero si las validaciones son exitosas.
     * En caso de error, devuelve un arreglo con los detalles del error.
     * @version 17.18.0
     */
    final protected function valida_option(string $descripcion,int|string $value ): true|array
    {
        $value = trim($value);
        if($value === ''){
            return $this->error->error(mensaje: 'Error value no puede venir vacio', data: $value);
        }
        $descripcion = trim($descripcion);
        if($descripcion === ''){
            return $this->error->error(mensaje: 'Error $descripcion no puede venir vacio', data: $descripcion);
        }
        return true;
    }

    /**
     * POR DOCUMENTAR EN WIKI FINAL REV
     * Valida los parámetros que se pasan a la función.
     *
     * @param string $id_css es el identificador CSS para el elemento.
     * @param string $name es el nombre del elemento.
     * @param string $place_holder es el placeholder del elemento.
     *
     * @return true|array devuelve true si la validación es exitosa, de lo contrario devuelve un array con detalles del error.
     * @version 16.15.0
     */
    final protected function valida_params_txt(string $id_css, string $name, string $place_holder): true|array
    {
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error name es necesario', data: $name, es_final: true);
        }
        $id_css = trim($id_css);
        if($id_css === ''){
            return $this->error->error(mensaje: 'Error $id_css es necesario', data: $id_css, es_final: true);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder es necesario', data: $place_holder,
                es_final: true);
        }
        return true;

    }

    /**
     * POR DOCUMNETAR EN WIKI
     * Esta función toma una clave y una fila (array) como parámetros.
     * Luego verifica si la clave pasada no está vacía.
     * Si la clave no está vacía, llama a la función `value_custom_row`
     * para obtener el valor de esta clave en la fila.
     * Además, verifica si ha ocurrido un error durante la llamada a `value_custom_row`.
     * Si se ha producido un error, devuelve un mensaje de error.
     * Finalmente, devuelve el valor obtenido de la fila para la clave dada.
     *
     * @param string $key_value_custom La clave que se buscará en la fila.
     * @param array $row La fila (array) en la que se buscará la clave.
     * @return array|string El valor de la clave obtenido de la fila,
     *                o un mensaje de error si se ha producido un error.
     *
     * @version 17.17.0
     */
    private function value_custom(string $key_value_custom, array $row): array|string
    {
        $key_value_custom = trim($key_value_custom);

        $value_custom = '';
        if($key_value_custom !== ''){
            $value_custom = $this->value_custom_row(key_value_custom: $key_value_custom,row:  $row);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al integrar value custom', data: $value_custom);
            }
        }
        return $value_custom;

    }

    /**
     * POR DOCUMENTAR EN WIKI
     * Esta función toma una clave y una fila (array) como parámetros.
     * Luego verifica si la clave pasada está vacía o no está establecida en la fila.
     * En caso de que la clave esté vacía, retornará un error.
     * Si la clave no está establecida en la fila, establecerá su valor como string vacío.
     * Finalmente, devuelve el valor de la clave en la fila después de eliminar los espacios en blanco.
     *
     * @param string $key_value_custom La clave que se buscará en la fila.
     * @param array $row La fila (array) en la que se buscará la clave.
     * @return array|string El valor de la clave después de aplicar trim,
     *                      o un error si la clave está vacía.
     *
     * @version 17.17.0
     */
    private function value_custom_row(string $key_value_custom, array $row): array|string
    {
        $key_value_custom = trim($key_value_custom);
        if($key_value_custom === ''){
            return $this->error->error(mensaje: 'Error key_value_custom esta vacio', data: $key_value_custom);
        }
        if(!isset($row[$key_value_custom])){
            $row[$key_value_custom] = '';
        }
        return trim($row[$key_value_custom]);

    }

    private function value_select(int|string|float|null $row_id, int|string|float $value_custom): string
    {
        $value = trim($row_id);
        $value_custom = trim($value_custom);
        if($value_custom !== ''){
            $value = $value_custom;
        }
        return $value;

    }
}
