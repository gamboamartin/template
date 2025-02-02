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
     * REG
     * Genera un botón HTML dinámicamente con los parámetros proporcionados.
     *
     * Este método permite generar un botón en HTML con diversos atributos configurables, como el estilo, el tipo,
     * los identificadores CSS, las clases CSS, los parámetros adicionales, etc.
     * Además, realiza una validación de los datos de entrada para garantizar que los valores proporcionados sean válidos
     * antes de generar el código HTML del botón.
     *
     * @param array $ids_css Un array de identificadores CSS que se asignarán al botón.
     *                       Cada valor del array será agregado como un identificador del atributo `id`.
     *
     * @param array $clases_css Un array de clases CSS que se aplicarán al botón.
     *                           Cada valor del array se agregará como una clase CSS al botón.
     *
     * @param array $extra_params Un array de parámetros adicionales que se agregarán al botón como atributos `data-*`.
     *                             El array debe contener claves como el nombre del atributo y sus respectivos valores.
     *
     * @param string $label El texto que se mostrará en el botón. Si está vacío, se usará el nombre del botón
     *                      con un formato adecuado.
     *
     * @param string $name El nombre del botón. Este valor será usado como el atributo `name` del botón en HTML.
     *
     * @param string $value El valor que se asignará al botón. Este valor será utilizado como el atributo `value` del botón.
     *
     * @param int $cols (Opcional) El número de columnas que ocupará el botón en el sistema de grillas de Bootstrap.
     *                  Por defecto, se asigna 6.
     *
     * @param string $style (Opcional) El estilo del botón según las clases de Bootstrap.
     *                       Por defecto, se usa el estilo 'info'. Otros valores posibles incluyen 'primary', 'danger', etc.
     *
     * @param string $type (Opcional) El tipo del botón, como 'button', 'submit', etc.
     *                       Por defecto, se usa el tipo 'button'.
     *
     * @return array|string Devuelve el código HTML del botón generado si la validación es exitosa.
     *                      Si ocurre un error durante la validación, se devuelve un array con el mensaje de error.
     *
     * @throws errores Si la validación de los datos falla.
     *
     * @example
     * // Ejemplo de uso del método para generar un botón con estilo 'primary', tipo 'submit', y con un ID específico.
     * $ids_css = ['btn_submit'];
     * $clases_css = ['extra-class'];
     * $extra_params = ['onclick' => 'alert("Botón presionado")'];
     * $label = 'Enviar';
     * $name = 'submit_form';
     * $value = 'submit';
     * $cols = 4;
     * $style = 'primary';
     * $type = 'submit';
     *
     * $boton_html = $directiva->btn($ids_css, $clases_css, $extra_params, $label, $name, $value, $cols, $style, $type);
     * echo $boton_html;
     * // Salida esperada: <button type='submit' class='btn btn-primary btn-guarda col-md-4 extra-class' id='btn_submit' name='submit_form' value='submit' data-onclick='alert("Botón presionado")'>Enviar</button>
     *
     * @version 1.0.0
     */
    final public function btn(array $ids_css, array $clases_css, array $extra_params, string $label, string $name,
                              string $value, int $cols = 6 , string $style = 'info', string $type = 'button'): array|string
    {
        // Se recortan los valores de los parámetros
        $label = trim($label);
        $name = trim($name);

        // Si la etiqueta está vacía, se usa el nombre como etiqueta
        if($label === ''){
            $label = $name;
            $label = str_replace('_', ' ', $label); // Reemplaza guiones bajos por espacios
            $label = ucwords($label); // Convierte la primera letra de cada palabra en mayúscula
        }

        // Validación de los datos antes de generar el HTML
        $valida = $this->valida_btn_next(label: $label, style: $style, type: $type, value: $value);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Generación de los identificadores CSS
        $ids_css_html = '';
        foreach ($ids_css as $id_css){
            $ids_css_html .= ' ' . $id_css;
        }

        // Generación de las clases CSS
        $clases_css_html = '';
        foreach ($clases_css as $class_css){
            $clases_css_html .= ' ' . $class_css;
        }

        // Generación de los parámetros adicionales
        $extra_params_data = '';
        foreach ($extra_params as $key => $value_param){
            $extra_params_data = " data-$key='$value_param' ";
        }

        // Construcción del botón en HTML
        $btn = "<button type='$type' class='btn btn-$style btn-guarda col-md-$cols $clases_css_html' id='$ids_css_html' ";
        $btn .= "name='$name' value='$value' $extra_params_data>$label</button>";

        return $btn; // Retorna el HTML del botón generado
    }



    /**
     * REG
     * Genera un botón HTML de acción con los parámetros proporcionados.
     *
     * Este método genera un botón en HTML, con diversos atributos configurables como el estilo, tipo,
     * valor y etiqueta. Utiliza el método `valida_btn_next` para validar los datos de entrada antes de
     * generar el código HTML. Si alguna validación falla, se devuelve un mensaje de error con detalles.
     *
     * @param string $label La etiqueta del botón. Este texto es lo que aparecerá dentro del botón en la interfaz de usuario.
     *                      No puede estar vacío.
     * @param string $value El valor del botón, que se asigna al atributo `value` del botón HTML. Este valor será enviado
     *                      al servidor cuando el botón sea presionado.
     * @param string $style El estilo del botón. Especifica la clase CSS de Bootstrap que se aplicará al botón.
     *                      El valor por defecto es `'info'`. Otros posibles valores son `'primary'`, `'danger'`, etc.
     * @param string $type El tipo de botón. Puede ser `'submit'`, `'button'`, entre otros. El valor por defecto es `'submit'`.
     *
     * @return string|array Devuelve el HTML del botón generado si las validaciones son exitosas. Si ocurre un error
     *                      durante la validación, devuelve un array con el mensaje de error y los detalles de la causa del error.
     *
     * @throws errores Si la validación de los datos falla, se lanzará un error.
     *
     * @example
     * // Caso exitoso: Generación de un botón de tipo submit con estilo 'primary' y valor 'submit'.
     * $label = "Enviar";
     * $value = "submit";
     * $style = "primary";
     * $type = "submit";
     * $boton_html = $directiva->btn_action_next($label, $value, $style, $type);
     * echo $boton_html;  // Resultado esperado: <button type='submit' class='btn btn-primary btn-guarda col-md-12' name='btn_action_next' value='submit'>Enviar</button>
     *
     * @example
     * // Caso de error: Si ocurre un error al validar los datos.
     * $label = "";
     * $value = "submit";
     * $boton_html = $directiva->btn_action_next($label, $value);
     * if (is_array($boton_html)) {
     *     echo $boton_html['mensaje'];  // Resultado: "Error al validar datos"
     * }
     *
     * @version 1.0.0
     */
    private function btn_action_next(
        string $label, string $value, string $style = 'info', string $type = 'submit'): string|array
    {
        // Validación de los datos antes de generar el HTML
        $valida = $this->valida_btn_next(label: $label, style: $style, type: $type, value: $value);
        if (errores::$error) {
            // Si hay un error en la validación, se devuelve el error
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Generación del HTML del botón
        $btn = "<button type='$type' class='btn btn-$style btn-guarda col-md-12' ";
        $btn .= "name='btn_action_next' value='$value'>$label</button>";

        // Retorna el HTML del botón generado
        return $btn;
    }


    /**
     * REG
     * Genera un botón dentro de un contenedor `div` con un número de columnas especificado.
     *
     * Este método crea un botón HTML utilizando la función `btn_action_next` y lo envuelve dentro de un contenedor `div`
     * con la clase Bootstrap correspondiente al número de columnas (`$cols`) especificado. Primero valida los parámetros
     * proporcionados, incluyendo el estilo, tipo, valor y número de columnas. Si alguna validación falla, devuelve un mensaje de error.
     * Si todo es válido, genera el botón y lo envuelve en un `div` con la clase `col-md-` correspondiente al número de columnas.
     *
     * @param string $label El texto que se mostrará en el botón. Este es el texto que aparece en el botón HTML.
     * @param string $value El valor que se asignará al botón como atributo `value` en HTML.
     * @param int $cols El número de columnas que el `div` ocupará en el sistema de grillas de Bootstrap. Por defecto, es 6.
     * @param string $style El estilo del botón, que se corresponde con las clases de Bootstrap (por ejemplo, 'info', 'primary').
     *                      Por defecto, es 'info'.
     * @param string $type El tipo del botón (por ejemplo, 'submit', 'button'). Por defecto, es 'submit'.
     *
     * @return array|string Devuelve el código HTML del `div` con el botón dentro si la validación es exitosa. Si ocurre un error,
     *                      devuelve un array con el mensaje de error correspondiente.
     *
     * @throws errores Si alguna de las validaciones de los parámetros falla, se lanza un error.
     *
     * @example
     * // Caso exitoso: Se genera un botón con estilo 'primary', tipo 'submit', y un número de columnas 4.
     * $label = "Enviar";
     * $value = "submit";
     * $cols = 4;
     * $style = 'primary';
     * $type = 'submit';
     * $resultado = $directiva->btn_action_next_div($label, $value, $cols, $style, $type);
     * echo $resultado;  // Resultado esperado: <div class='col-md-4'><button type='submit' class='btn btn-primary btn-guarda col-md-12' name='btn_action_next' value='submit'>Enviar</button></div>
     *
     * @example
     * // Caso de error: Se pasa un número de columnas inválido (por ejemplo, 13).
     * $label = "Enviar";
     * $value = "submit";
     * $cols = 13;  // Este valor es inválido ya que debe estar entre 1 y 12.
     * $resultado = $directiva->btn_action_next_div($label, $value, $cols);
     * if (is_array($resultado)) {
     *     echo $resultado['mensaje'];  // Resultado: "Error al validar columnas"
     * }
     *
     * @version 1.0.0
     */
    final public function btn_action_next_div(string $label, string $value, int $cols = 6, string $style = 'info',
                                              string $type = 'submit'): array|string
    {
        // Validación de los parámetros del botón
        $valida = $this->valida_btn_next(label: $label, style:  $style, type:  $type, value:  $value);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Validación del número de columnas
        $valida = $this->valida_cols(cols: $cols);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar columnas', data: $valida);
        }

        // Generación del botón
        $btn = $this->btn_action_next(label: $label, value:  $value, style: $style, type: $type);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar btn datos', data: $btn);
        }

        // Retorna el HTML con el botón envuelto en un div con el número de columnas especificado
        return "<div class='col-md-$cols'>$btn</div>";
    }


    /**
     * REG
     * Genera un enlace HTML (`<a>`) para un botón con una etiqueta y los parámetros proporcionados.
     *
     * Esta función recibe varios parámetros como la acción, etiqueta, nombre, marcador de posición, ID de registro, sección, estilo y otros,
     * y genera un enlace HTML que funciona como un botón. Además, valida que todos los parámetros sean correctos. Si alguno de los parámetros es inválido,
     * se devuelve un mensaje de error.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que los parámetros `name` y `place_holder` no estén vacíos utilizando el método `valida_data_label`.
     * 2. Se valida que los parámetros `accion`, `etiqueta`, `seccion`, y `style` no estén vacíos utilizando el método `valida_input`.
     * 3. Si la validación es exitosa, se genera una etiqueta `label` utilizando `label_input`.
     * 4. Se genera el HTML del enlace utilizando el método `button_href` de la clase `html`.
     * 5. Se integra el HTML del enlace y la etiqueta en un `div` con el método `div_label`.
     * 6. Si ocurre algún error en cualquiera de los pasos anteriores, se devuelve un mensaje de error.
     * 7. Si todo es exitoso, se retorna el HTML generado para el `div` con el enlace y la etiqueta.
     *
     * **Parámetros:**
     *
     * @param string $accion La acción que se realizará cuando se haga clic en el botón.
     * @param string $etiqueta El texto que se mostrará en el botón.
     * @param string $name El nombre del campo que se utilizará para generar el identificador de la etiqueta.
     * @param string $place_holder El texto que se muestra como marcador de posición en el campo asociado a la etiqueta.
     * @param int $registro_id El ID del registro que se utilizará para la acción.
     * @param string $seccion El nombre de la sección donde se encuentra el botón.
     * @param string $style El estilo CSS del botón.
     *
     * **Retorno:**
     * - Devuelve el HTML de un `div` que contiene un enlace `<a>` con la etiqueta asociada si todos los parámetros son válidos.
     * - Si ocurre un error durante la validación o la generación del HTML, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Generación exitosa de un enlace**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $name = "guardar_id";
     * $place_holder = "Ingrese ID del usuario";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $resultado = $this->button_href($accion, $etiqueta, $name, $place_holder, $registro_id, $seccion, $style);
     * // Retorna un div con el HTML del enlace y la etiqueta.
     * ```
     *
     * **Ejemplo 2: Error debido a un `place_holder` vacío**
     * ```php
     * $accion = "guardar";
     * $etiqueta = "Guardar cambios";
     * $name = "guardar_id";
     * $place_holder = "";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $resultado = $this->button_href($accion, $etiqueta, $name, $place_holder, $registro_id, $seccion, $style);
     * // Retorna un mensaje de error: 'Error $place_holder debe tener info'.
     * ```
     *
     * **Ejemplo 3: Error debido a un parámetro de entrada vacío**
     * ```php
     * $accion = "";
     * $etiqueta = "Guardar cambios";
     * $name = "guardar_id";
     * $place_holder = "Ingrese ID del usuario";
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $style = "btn-primary";
     * $resultado = $this->button_href($accion, $etiqueta, $name, $place_holder, $registro_id, $seccion, $style);
     * // Retorna un mensaje de error: 'Error al validar datos'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function button_href(string $accion, string $etiqueta, string $name, string $place_holder,
                                int $registro_id, string $seccion, string $style): array|string
    {
        // Validación de los parámetros name y place_holder
        $valida = $this->valida_data_label(name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        // Validación de los parámetros de entrada (accion, etiqueta, seccion, style)
        $valida = $this->html->valida_input(accion: $accion,etiqueta:  $etiqueta, seccion: $seccion,style:  $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Generación de la etiqueta label
        $label = $this->label_input(name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        // Verificación de que el place_holder no esté vacío
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder debe tener info', data: $place_holder, es_final: true);
        }

        // Generación del HTML para el botón
        $html = $this->html->button_href(accion: $accion,etiqueta:  $etiqueta, registro_id: $registro_id,
            seccion:  $seccion, style: $style);

        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar html', data: $html);
        }

        // Integración de la etiqueta label con el HTML del botón en un div
        $div = $this->html->div_label(html: $html, label: $label);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        // Retornar el div generado
        return $div;
    }


    /**
     * REG
     * Crea un botón de enlace (`<a>`) con un estado determinado y un estilo dinámico.
     *
     * Esta función genera un botón de enlace HTML con un estado determinado (activo o inactivo),
     * y un estilo dinámico. El estilo del botón será `danger` si el estado es inactivo y `info`
     * si el estado es activo. Además, valida que los parámetros proporcionados sean correctos
     * y genera un contenedor `div` con el botón correspondiente.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que el parámetro `$seccion` no esté vacío.
     * 2. Se valida que el parámetro `$status` no esté vacío.
     * 3. Se valida el número de columnas (`$cols`) usando el método `valida_cols`.
     * 4. Si el estado es 'activo', el estilo se ajusta a `info`, de lo contrario, se establece como `danger`.
     * 5. Se genera el enlace HTML utilizando el método `button_href` con los parámetros dados.
     * 6. Se genera un contenedor `div` que contiene el botón de enlace.
     * 7. Si ocurre un error en alguno de los pasos, se retorna un mensaje de error detallado.
     * 8. Si todo es exitoso, se retorna el contenedor `div` con el botón de enlace.
     *
     * **Parámetros:**
     *
     * @param int $cols El número de columnas que se utilizarán en el contenedor `div`. Este parámetro es obligatorio.
     * @param int $registro_id El ID del registro que se utilizará para la acción del botón.
     * @param string $seccion El nombre de la sección donde se llevará a cabo la acción.
     * @param string $status El estado del botón, que puede ser 'activo' o 'inactivo'.
     *
     * **Retorno:**
     * - Devuelve el HTML de un `div` que contiene el botón de enlace si todo es válido.
     * - Si ocurre un error durante la validación o la generación, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Generación de un botón de enlace válido**
     * ```php
     * $cols = 6;
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $status = "activo";
     * $resultado = $this->button_href_status($cols, $registro_id, $seccion, $status);
     * // Retorna el HTML de un div con un botón de enlace con estilo 'info'.
     * ```
     *
     * **Ejemplo 2: Error por estado vacío**
     * ```php
     * $cols = 6;
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $status = "";
     * $resultado = $this->button_href_status($cols, $registro_id, $seccion, $status);
     * // Retorna un mensaje de error: 'Error el $status esta vacio'.
     * ```
     *
     * **Ejemplo 3: Error por número de columnas inválido**
     * ```php
     * $cols = -1;
     * $registro_id = 123;
     * $seccion = "usuarios";
     * $status = "activo";
     * $resultado = $this->button_href_status($cols, $registro_id, $seccion, $status);
     * // Retorna un mensaje de error: 'Error al validar cols'.
     * ```
     *
     * **@version 1.0.0**
     */
    public function button_href_status(int $cols, int $registro_id, string $seccion, string $status): array|string
    {
        // Validación del parámetro 'seccion'
        $seccion = trim($seccion);
        if($seccion === ''){
            return $this->error->error(mensaje: 'Error la $seccion esta vacia', data: $seccion, es_final: true);
        }

        // Validación del parámetro 'status'
        $status = trim($status);
        if($status === ''){
            return $this->error->error(mensaje: 'Error el $status esta vacio', data: $status, es_final: true);
        }

        // Validación de las columnas
        $valida = $this->valida_cols(cols: $cols);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar cols', data: $valida);
        }

        // Determinación del estilo en base al estado
        $style = 'danger';
        if($status === 'activo'){
            $style = 'info';
        }

        // Generación del HTML para el enlace
        $html = $this->button_href(accion: 'status',etiqueta: $status,name: 'status',
            place_holder: 'Status',registro_id: $registro_id,seccion: $seccion, style: $style);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $html);
        }

        // Generación del contenedor 'div' que contiene el enlace
        $div = $this->html->div_group(cols: $cols,html:  $html);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar div', data: $div);
        }

        // Retorno del contenedor 'div' generado
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
                               bool $value_vacio, bool $multiple = false): array|string
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
            required: $required, value: $row_upd_->$name,multiple: $multiple);



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
     * REG
     * Genera una etiqueta (`label`) HTML basada en el nombre y el marcador de posición proporcionados.
     *
     * Esta función recibe un nombre y un marcador de posición (place holder) y genera una etiqueta HTML (`label`) utilizando
     * esos valores. Antes de generar la etiqueta, la función valida que los datos proporcionados sean correctos. Si algún dato es
     * inválido, se genera un error con un mensaje descriptivo. Si la validación es exitosa, se procede a generar el HTML de la etiqueta.
     *
     * **Pasos de procesamiento:**
     * 1. Se valida que los parámetros `$name` y `$place_holder` no estén vacíos.
     * 2. Se genera una etiqueta `label` utilizando el método `label` de la clase `html`.
     * 3. Si ocurre un error en cualquiera de estos pasos, se devuelve un mensaje de error con detalles sobre el problema.
     * 4. Si todo es exitoso, se retorna el HTML de la etiqueta generada.
     *
     * **Parámetros:**
     *
     * @param string $name El nombre del campo. Este parámetro es obligatorio y se usa para generar el identificador CSS de la etiqueta.
     * @param string $place_holder El texto que se mostrará como marcador de posición (place holder) en el campo asociado a la etiqueta.
     *
     * **Retorno:**
     * - Devuelve el HTML de la etiqueta `label` si los parámetros son válidos.
     * - Si ocurre un error durante la validación o generación, se devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label_input($name, $place_holder);
     * // Retorna: "<label for='usuario_id'>Ingrese ID del usuario</label>"
     * ```
     *
     * **Ejemplo 2: Error por parámetro vacío**
     * ```php
     * $name = "";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->label_input($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $name debe tener info'.
     * ```
     *
     * **Ejemplo 3: Error por parámetro vacío (place_holder)**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "";
     * $resultado = $this->label_input($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $place_holder debe tener info'.
     * ```
     *
     * **@version 1.0.0**
     */
    final protected function label_input(string $name, string $place_holder): array|string
    {
        // Validación de los parámetros name y place_holder
        $valida = $this->valida_data_label(name: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar datos ', data: $valida);
        }

        // Generar la etiqueta label utilizando los valores proporcionados
        $label = $this->html->label(id_css: $name, place_holder: $place_holder);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar label', data: $label);
        }

        // Retornar el HTML de la etiqueta generada
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
     * REG
     * Genera un mensaje de éxito en formato HTML usando una alerta de Bootstrap.
     *
     * Este método recibe un mensaje de éxito y genera una alerta de éxito si el mensaje no está vacío.
     * Si el mensaje está vacío, no se generará ninguna alerta. Si hay un error durante la creación
     * de la alerta, se devolverá un mensaje de error con detalles.
     *
     * @param string $mensaje_exito El mensaje de éxito que se mostrará en la alerta. Este parámetro debe
     *                              ser una cadena de texto que describa el éxito de una operación.
     *
     * @return array|string Si el mensaje no está vacío, devuelve una cadena de texto que contiene el HTML
     *                      de una alerta de éxito. Si ocurre un error al generar la alerta, devuelve
     *                      un array con un mensaje de error y los datos del error.
     *
     * @example
     * // Caso exitoso: Generación de una alerta de éxito
     * $mensaje = "La operación se completó exitosamente.";
     * $alerta = $directiva->mensaje_exito($mensaje);
     * echo $alerta;  // Resultado: <div class="alert alert-success" role="alert"><strong>Muy bien!</strong> La operación se completó exitosamente.</div>
     *
     * @example
     * // Caso de error: Si ocurre un error al generar la alerta
     * $mensaje = "";
     * $alerta = $directiva->mensaje_exito($mensaje);
     * if (is_array($alerta)) {
     *     echo $alerta['mensaje'];  // Resultado: "Error al generar alerta"
     * }
     *
     * @version 1.0.0
     */
    final public function mensaje_exito(string $mensaje_exito): array|string
    {
        $alert_exito = '';

        // Comprobar si el mensaje de éxito no está vacío
        if ($mensaje_exito !== '') {
            // Generar la alerta de éxito utilizando el método alert_success de la clase html
            $alert_exito = $this->html->alert_success(mensaje: $mensaje_exito);

            // Verificar si hubo un error al generar la alerta
            if (errores::$error) {
                // Si hubo un error, devolver el mensaje de error
                return $this->error->error(mensaje: 'Error al generar alerta', data: $alert_exito);
            }
        }

        // Si todo está bien, devolver el HTML de la alerta de éxito
        return $alert_exito;
    }


    /**
     * REG
     * Genera un mensaje de advertencia en formato HTML utilizando una alerta de Bootstrap.
     *
     * Este método recibe un mensaje de advertencia y genera una alerta de advertencia en formato HTML
     * utilizando el método `alert_warning` de la clase `html`. Si el mensaje no está vacío, se genera
     * la alerta. Si ocurre algún error al generar la alerta, se devuelve un mensaje de error con detalles.
     * Si no hay error, se devuelve el código HTML de la alerta generada.
     *
     * @param string $mensaje_warning El mensaje de advertencia que se mostrará en la alerta.
     *                                Este parámetro debe ser una cadena de texto que describa el
     *                                problema o la advertencia que se está notificando.
     *
     * @return array|string Devuelve el HTML de la alerta de advertencia generada si el mensaje no está vacío.
     *                      Si ocurre un error durante la generación de la alerta, devuelve un array con
     *                      un mensaje de error y los detalles de la causa del error.
     *
     * @throws errores Si ocurre un error durante la generación del mensaje de advertencia.
     *
     * @example
     * // Ejemplo de uso exitoso:
     * $mensaje_warning = "Advertencia: El formulario no se ha enviado correctamente.";
     * $alerta = $directiva->mensaje_warning($mensaje_warning);
     * echo $alerta;
     * // Salida esperada: <div class="alert alert-warning" role="alert">
     * //                   <strong>Advertencia!</strong> El formulario no se ha enviado correctamente.
     * //                   </div>
     *
     * @example
     * // Ejemplo de error: Si ocurre un error al generar la alerta:
     * $mensaje_warning = "";
     * $alerta = $directiva->mensaje_warning($mensaje_warning);
     * if (is_array($alerta)) {
     *     echo $alerta['mensaje'];  // Resultado: "Error al generar alerta"
     * }
     *
     * @version 1.0.0
     */
    final public function mensaje_warning( string $mensaje_warning): array|string
    {
        $alert_warning = '';

        // Comprobar si el mensaje de advertencia no está vacío
        if ($mensaje_warning !== '') {
            // Generar la alerta de advertencia utilizando el método alert_warning de la clase html
            $alert_warning = $this->html->alert_warning(mensaje: $mensaje_warning);

            // Verificar si hubo un error al generar la alerta
            if (errores::$error) {
                // Si hubo un error, devolver el mensaje de error
                return $this->error->error(mensaje: 'Error al generar alerta', data: $alert_warning);
            }
        }

        // Si todo está bien, devolver el HTML de la alerta de advertencia
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
     * REG
     * Valida los datos de entrada de un botón, incluyendo su etiqueta, estilo, tipo y valor.
     *
     * Este método realiza una serie de validaciones para los parámetros `$label`, `$style`, `$type` y `$value`.
     * Primero, valida que el valor y la etiqueta no estén vacíos utilizando la función `valida_data_base`.
     * Luego verifica que los parámetros `$style` y `$type` no estén vacíos, ya que son cruciales para la correcta
     * generación y comportamiento del botón.
     *
     * Si alguna de las validaciones falla, el método devolverá un error con detalles. Si todas las validaciones
     * son exitosas, devolverá `true`.
     *
     * @param string $label La etiqueta que describe el botón. Esta etiqueta se utiliza para identificar el botón
     *                      y también se muestra en la interfaz de usuario. No puede estar vacía.
     *
     * @param string $style El estilo del botón, que normalmente corresponde a una clase de Bootstrap u otra
     *                      librería de estilos. Debe contener un valor válido (por ejemplo, 'primary', 'danger').
     *                      No puede estar vacío.
     *
     * @param string $type El tipo de botón, como 'submit', 'button', etc. Este valor es esencial para determinar
     *                     el comportamiento del botón. No debe estar vacío.
     *
     * @param string $value El valor que el botón enviará al servidor cuando sea presionado. No puede ser vacío.
     *
     * @return true|array Devuelve `true` si todas las validaciones son exitosas. Si alguna validación falla,
     *                    devuelve un array con el mensaje de error correspondiente.
     *
     * @example
     * // Caso exitoso: Todos los parámetros son válidos
     * $label = "Guardar";
     * $style = "primary";
     * $type = "submit";
     * $value = "save";
     * $resultado = $directiva->valida_btn_next($label, $style, $type, $value);
     * var_dump($resultado); // Resultado: true
     *
     * @example
     * // Caso de error: El estilo está vacío
     * $label = "Guardar";
     * $style = "";
     * $type = "submit";
     * $value = "save";
     * $resultado = $directiva->valida_btn_next($label, $style, $type, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error $style esta vacio', 'data' => '')
     *
     * @example
     * // Caso de error: El tipo está vacío
     * $label = "Guardar";
     * $style = "primary";
     * $type = "";
     * $value = "save";
     * $resultado = $directiva->valida_btn_next($label, $style, $type, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error $type esta vacio', 'data' => '')
     *
     * @version 1.0.0
     */
    final public function valida_btn_next(string $label, string $style, string $type, string $value): true|array
    {
        // Se valida que la etiqueta y el valor no estén vacíos
        $valida = $this->valida_data_base(label: $label, value:  $value);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar datos', data: $valida);
        }

        // Se valida que el estilo no esté vacío
        $style = trim($style);
        if ($style === '') {
            return $this->error->error(mensaje: 'Error $style esta vacio', data: $style, es_final: true);
        }

        // Se valida que el tipo no esté vacío
        $type = trim($type);
        if ($type === '') {
            return $this->error->error(mensaje: 'Error $type esta vacio', data: $type, es_final: true);
        }

        // Si todas las validaciones son exitosas, se devuelve true
        return true;
    }


    /**
     * REG
     * Valida los datos de entrada asegurándose de que tanto la etiqueta como el valor no estén vacíos.
     *
     * Este método realiza una validación básica de los parámetros `$label` y `$value`. Verifica que ambos parámetros
     * sean cadenas de texto no vacías. Si alguno de ellos está vacío, devuelve un error detallado.
     * Si ambos parámetros contienen datos válidos, devuelve `true` indicando que la validación fue exitosa.
     *
     * @param string $label La etiqueta a validar. Este parámetro debe ser una cadena de texto no vacía.
     *                      La etiqueta generalmente se usa para describir el campo o la acción que se está realizando.
     *
     * @param string $value El valor a validar. Este parámetro debe ser una cadena de texto no vacía.
     *                      El valor representaría, por ejemplo, el contenido que un usuario ha ingresado.
     *
     * @return true|array Retorna `true` si ambos parámetros son válidos (no vacíos). Si algún parámetro está vacío,
     *                    retorna un array con un mensaje de error y los datos que causaron el error.
     *
     * @example
     * // Caso exitoso: Ambas entradas no están vacías
     * $label = "Nombre";
     * $value = "Juan";
     * $resultado = $directiva->valida_data_base($label, $value);
     * var_dump($resultado); // Resultado: true
     *
     * @example
     * // Caso de error: La etiqueta está vacía
     * $label = "";
     * $value = "Juan";
     * $resultado = $directiva->valida_data_base($label, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error label esta vacio', 'data' => '')
     *
     * @example
     * // Caso de error: El valor está vacío
     * $label = "Nombre";
     * $value = "";
     * $resultado = $directiva->valida_data_base($label, $value);
     * var_dump($resultado); // Resultado: array('mensaje' => 'Error $value esta vacio', 'data' => '')
     *
     * @version 1.0.0
     */
    final public function valida_data_base(string $label, string $value): true|array
    {
        // Eliminamos espacios en blanco al inicio y al final de la etiqueta
        $label = trim($label);

        // Validamos si la etiqueta está vacía
        if ($label === '') {
            return $this->error->error(mensaje: 'Error label esta vacio', data: $label, es_final: true);
        }

        // Eliminamos espacios en blanco al inicio y al final del valor
        $value = trim($value);

        // Validamos si el valor está vacío
        if ($value === '') {
            return $this->error->error(mensaje: 'Error $value esta vacio', data: $value, es_final: true);
        }

        // Si ambos parámetros son válidos, devolvemos true
        return true;
    }




    /**
     * REG
     * Valida el número de columnas proporcionado asegurando que esté dentro de un rango válido.
     *
     * Este método valida que el número de columnas (`$cols`) sea un valor entero dentro del rango permitido
     * de 1 a 12. Si el valor proporcionado es menor o igual a 0 o mayor o igual a 13, el método devuelve un error
     * indicando que el valor de las columnas no es válido. Si el valor es válido, devuelve `true`.
     *
     * @param int $cols El número de columnas a validar. Debe ser un valor entero entre 1 y 12 (inclusive).
     *
     * @return true|array Devuelve `true` si el número de columnas está dentro del rango aceptado (de 1 a 12).
     *                    Si el valor no es válido (menor o igual a 0 o mayor o igual a 13), se devuelve un array
     *                    con el mensaje de error y los detalles de la causa del error.
     *
     * @throws errores Si el número de columnas no está dentro del rango válido, se lanzará un error.
     *
     * @example
     * // Caso exitoso: El número de columnas es válido (por ejemplo, 6)
     * $cols = 6;
     * $resultado = $directiva->valida_cols($cols);
     * var_dump($resultado); // Resultado: true
     *
     * @example
     * // Caso de error: El número de columnas es inválido (por ejemplo, 0)
     * $cols = 0;
     * $resultado = $directiva->valida_cols($cols);
     * if (is_array($resultado)) {
     *     echo $resultado['mensaje'];  // Resultado: "Error cols debe ser mayor a 0"
     * }
     *
     * @example
     * // Caso de error: El número de columnas es demasiado grande (por ejemplo, 15)
     * $cols = 15;
     * $resultado = $directiva->valida_cols($cols);
     * if (is_array($resultado)) {
     *     echo $resultado['mensaje'];  // Resultado: "Error cols debe ser menor o igual a 12"
     * }
     *
     * @version 1.0.0
     */
    final public function valida_cols(int $cols): true|array
    {
        // Si el número de columnas es menor o igual a 0, se genera un error
        if ($cols <= 0) {
            return $this->error->error(mensaje: 'Error cols debe ser mayor a 0', data: $cols, es_final: true);
        }

        // Si el número de columnas es mayor o igual a 13, se genera un error
        if ($cols >= 13) {
            return $this->error->error(mensaje: 'Error cols debe ser menor o igual a 12', data: $cols, es_final: true);
        }

        // Si todo es válido, se devuelve true
        return true;
    }


    /**
     * REG
     * Valida los datos de un nombre y un marcador de lugar (place_holder) para asegurarse de que ambos no estén vacíos.
     *
     * Esta función verifica que los valores de los parámetros `$name` y `$place_holder` no estén vacíos, eliminando cualquier
     * espacio en blanco al principio y al final de los valores antes de hacer la validación. Si alguno de los dos parámetros
     * está vacío, se genera un error con un mensaje específico. Si ambos son válidos, la función devuelve `true`.
     *
     * **Pasos de validación:**
     * 1. Se elimina cualquier espacio en blanco al principio y al final de los valores `$name` y `$place_holder`.
     * 2. Se valida que `$name` no esté vacío.
     * 3. Se valida que `$place_holder` no esté vacío.
     * 4. Si alguna de las validaciones falla, se genera un error con un mensaje descriptivo.
     * 5. Si ambas validaciones pasan correctamente, se devuelve `true`.
     *
     * **Parámetros:**
     *
     * @param string $name El nombre del campo de entrada. Este parámetro es obligatorio y no debe estar vacío.
     *                     Representa el nombre del campo que se utilizará en el formulario.
     * @param string $place_holder El texto que se muestra como marcador de posición en el campo de entrada.
     *                             Este parámetro es obligatorio y no debe estar vacío.
     *
     * **Retorno:**
     * - Devuelve `true` si ambos parámetros no están vacíos y son válidos.
     * - Si alguno de los parámetros está vacío, devuelve un arreglo con el mensaje de error correspondiente.
     *
     * **Ejemplos:**
     *
     * **Ejemplo 1: Validación exitosa**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->valida_data_label($name, $place_holder);
     * // Retorna true porque ambos parámetros son válidos.
     * ```
     *
     * **Ejemplo 2: Error por $name vacío**
     * ```php
     * $name = "";
     * $place_holder = "Ingrese ID del usuario";
     * $resultado = $this->valida_data_label($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $name debe tener info'.
     * ```
     *
     * **Ejemplo 3: Error por $place_holder vacío**
     * ```php
     * $name = "usuario_id";
     * $place_holder = "";
     * $resultado = $this->valida_data_label($name, $place_holder);
     * // Retorna un arreglo con el mensaje de error: 'Error $place_holder debe tener info'.
     * ```
     *
     * **@version 1.0.0**
     */
    final public function valida_data_label(string $name, string $place_holder): true|array
    {
        // Eliminar espacios en blanco al principio y al final de los valores
        $name = trim($name);
        if($name === ''){
            return $this->error->error(mensaje: 'Error $name debe tener info', data: $name, es_final: true);
        }
        $place_holder = trim($place_holder);
        if($place_holder === ''){
            return $this->error->error(mensaje: 'Error $place_holder debe tener info', data: $place_holder, es_final: true);
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
