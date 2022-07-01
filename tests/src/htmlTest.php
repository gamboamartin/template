<?php
namespace tests\src;

use gamboamartin\errores\errores;
use gamboamartin\template\html;
use gamboamartin\test\test;
use stdClass;


class htmlTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();

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

