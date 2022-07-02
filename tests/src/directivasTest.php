<?php
namespace tests\src;

use gamboamartin\errores\errores;
use gamboamartin\template\directivas;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use JetBrains\PhpStorm\NoReturn;
use JsonException;


class directivasTest extends test {
    public errores $errores;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();


    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_label_input(): void
    {
        errores::$error = false;
        $html = new directivas();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $name = 'a';
        $place_holder = 'c';


        $resultado = $html->label_input($name, $place_holder);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('', $resultado);
        errores::$error = false;
    }



    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_valida_cols(): void
    {
        errores::$error = false;
        $html = new directivas();
        $html = new liberator($html);
        $_GET['session_id'] = 1;

        $cols = -1;


        $resultado = $html->valida_cols($cols);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error cols debe ser mayor a 0', $resultado['mensaje']);

        errores::$error = false;

        $cols = 13;


        $resultado = $html->valida_cols($cols);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error cols debe ser menor o igual a  12', $resultado['mensaje']);

        errores::$error = false;

        $cols = 1;


        $resultado = $html->valida_cols($cols);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);
        errores::$error = false;
    }

    /**
     * @throws JsonException
     */
    #[NoReturn] public function test_valida_data_label(): void
    {
        errores::$error = false;
        $html = new directivas();
        $html = new liberator($html);
        $_GET['session_id'] = 1;
        $name = '';
        $place_holder = '';


        $resultado = $html->valida_data_label($name, $place_holder);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $name debe tener info', $resultado['mensaje']);
        errores::$error = false;

        $_GET['session_id'] = 1;
        $name = 'a';
        $place_holder = '';


        $resultado = $html->valida_data_label($name, $place_holder);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error $place_holder debe tener info', $resultado['mensaje']);

        errores::$error = false;

        $_GET['session_id'] = 1;
        $name = 'a';
        $place_holder = 'c';


        $resultado = $html->valida_data_label($name, $place_holder);
        $this->assertIsBool($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertTrue($resultado);

        errores::$error = false;
    }


}

