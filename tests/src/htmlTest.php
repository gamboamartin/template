<?php
namespace tests\src;

use gamboamartin\errores\errores;
use gamboamartin\template\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JetBrains\PhpStorm\NoReturn;
use JsonException;
use stdClass;


class htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();

    }

    /**
     */
    #[NoReturn] public function test_button_href(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $accion = 'b';
        $etiqueta = 'd';
        $registro_id = '-1';
        $seccion = 'a';
        $style = 'c';



        $resultado = $html->button_href($accion, $etiqueta, $registro_id, $seccion, $style);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<a |role| href='index.php?seccion=a&accion=b&registro_id=-1&session_id=1' |class|>d</a>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_div_control_group_cols(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $contenido = 'x';
        $cols = 5;


        $resultado = $html->div_control_group_cols($cols, $contenido);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div class='control-group col-sm-5'>x</div>", $resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_div_group(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;
        $cols = 1;
        $html_txt = '';


        $resultado = $html->div_group($cols, $html_txt);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<div |class|></div>", $resultado);

        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_div_label(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $html_ = 'b';
        $label = 'd';


        $resultado = $html->div_label($html_, $label);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("d<div |class|>b</div>", $resultado);

    }

    /**
     */
    #[NoReturn] public function test_div_select(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'b';
        $options_html = 'd';

        $resultado = $html->div_select($name, $options_html);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<select class='form-control selectpicker color-secondary b' id='b' name='b' >d</select>",$resultado);

        errores::$error = false;

        $name = 'b';
        $options_html = 'd';
        $required = "required";

        $resultado = $html->div_select($name, $options_html, $required);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<select class='form-control selectpicker color-secondary b' id='b' name='b' required>d</select>",$resultado);

        errores::$error = false;

        $name = 'b';
        $options_html = 'd';
        $required = true;

        $resultado = $html->div_select($name, $options_html, $required);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;
    }

    public function test_label(): void
    {
        errores::$error = false;
        $html = new html();
        //$inicializacion = new liberator($inicializacion);

        $id_css = 'a';
        $place_holder = 'c';
        $resultado = $html->label($id_css, $place_holder);


        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("", $resultado);


        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_params_txt(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;
        $disabled = false;
        $id_css = 'b';
        $name = 'a';
        $place_holder = 'c';
        $required = false;


        $resultado = $html->params_txt($disabled, $id_css, $name, $place_holder, $required);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

    }

    #[NoReturn] public function test_email(): void
    {
        errores::$error = false;
        $html = new html();
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'c';
        $name = 'a';
        $place_holder = 'c';
        $required = false;
        $value = '';

        $resultado = $html->email($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('<input type="text" name="a" value="" |class|   id="c" placeholder="c" pattern="[a-z0-9!#$%&\'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$" />',$resultado);

    }

    #[NoReturn] public function test_fecha(): void
    {
        errores::$error = false;
        $html = new html();
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'a';
        $name = 'a';
        $place_holder = 'a';
        $required = false;
        $value = '';

        $resultado = $html->fecha($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='date' name='a' value='' |class|   id='a' placeholder='a' />",$resultado);

        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_selected(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $value = '5';
        $id_selected = 5;


        $resultado = $html->selected($value, $id_selected);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;

        $value = '6';
        $id_selected = 5;


        $resultado = $html->selected($value, $id_selected);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertNotTrue($resultado);
        errores::$error = false;
    }


    #[NoReturn] public function test_option(): void
    {
        errores::$error = false;
        $html = new html();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $descripcion = "a";
        $selected = false;
        $value = "-1";

        $resultado = $html->option($descripcion, $selected, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='' >a</option>", $resultado);

        $descripcion = "campo";
        $selected = false;
        $value = "campo";
        $resultado = $html->option($descripcion, $selected, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<option value='campo' >campo</option>", $resultado);

        errores::$error = false;
    }

    /**
     */
    #[NoReturn] public function test_text(): void
    {
        errores::$error = false;
        $html = new html();
        //$html = new liberator($html);
        $_GET['session_id'] = 1;

        $disabled = false;
        $id_css = 'c';
        $name = 'a';
        $place_holder = 'c';
        $required = false;
        $value = '';


        $resultado = $html->text($disabled, $id_css, $name, $place_holder, $required, $value);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<input type='text' name='a' value='' |class|   id='c' placeholder='c' />",$resultado);
        errores::$error = false;
    }


    public function test_valida_input(): void
    {
        errores::$error = false;
        $html = new html();
        //$inicializacion = new liberator($inicializacion);

        $accion = '';
        $etiqueta = '';
        $seccion = '';
        $style = '';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $seccion esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = '';
        $etiqueta = '';
        $seccion = 'a';
        $style = '';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $accion esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = 'a';
        $etiqueta = '';
        $seccion = 'a';
        $style = '';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $style esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = 'a';
        $etiqueta = '';
        $seccion = 'a';
        $style = 'a';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error la $etiqueta esta vacia', $resultado['mensaje']);

        errores::$error = false;

        $accion = 'a';
        $etiqueta = 'a';
        $seccion = 'a';
        $style = 'a';

        $resultado = $html->valida_input($accion, $etiqueta, $seccion, $style);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;

    }







}

