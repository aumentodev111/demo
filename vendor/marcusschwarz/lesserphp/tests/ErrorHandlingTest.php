<?php

/**
 * lesserphp
 * https://www.maswaba.de/lesserphp
 *
 * LESS CSS compiler, adapted from http://lesscss.org
 *
 * Copyright 2013, Leaf Corcoran <leafot@gmail.com>
 * Copyright 2016, Marcus Schwarz <github@maswaba.de>
 * Licensed under MIT or GPLv3, see LICENSE
 * @package LesserPhp
 */
class ErrorHandlingTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var LesserPhp\Compiler
     */
    private $less;

    public function setUp()
    {
        $this->less = new \LesserPhp\Compiler();
    }

    public function compile()
    {
        $source = implode("\n", func_get_args());

        return $this->less->compile($source);
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage .parametric-mixin is undefined
     */
    public function testRequiredParametersMissing()
    {
        $this->compile(
            '.parametric-mixin (@a, @b) { a: @a; b: @b; }',
            '.selector { .parametric-mixin(12px); }'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage .parametric-mixin is undefined
     */
    public function testTooManyParameters()
    {
        $this->compile(
            '.parametric-mixin (@a, @b) { a: @a; b: @b; }',
            '.selector { .parametric-mixin(12px, 13px, 14px); }'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage unrecognised input
     */
    public function testRequiredArgumentsMissing()
    {
        $this->compile('.selector { rule: e(); }');
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage variable @missing is undefined
     */
    public function testVariableMissing()
    {
        $this->compile('.selector { rule: @missing; }');
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage .missing-mixin is undefined
     */
    public function testMixinMissing()
    {
        $this->compile('.selector { .missing-mixin; }');
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage .flipped is undefined
     */
    public function testGuardUnmatchedValue()
    {
        $this->compile(
            '.flipped(@x) when (@x =< 10) { rule: value; }',
            '.selector { .flipped(12); }'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage .colors-only is undefined
     */
    public function testGuardUnmatchedType()
    {
        $this->compile(
            '.colors-only(@x) when (iscolor(@x)) { rule: value; }',
            '.selector { .colors-only("string value"); }'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage    expecting at least 1 arguments, got 0
     */
    public function testMinNoArguments()
    {
        $this->compile(
            '.selector{ min: min(); }'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage    expecting at least 1 arguments, got 0
     */
    public function testMaxNoArguments()
    {
        $this->compile(
            '.selector{ max: max(); }'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage    Cannot convert % to px
     */
    public function testMaxIncompatibleTypes()
    {
        $this->compile(
            '.selector{ max: max( 10px, 5% ); }'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage    Cannot convert px to s
     */
    public function testConvertIncompatibleTypes()
    {
        $this->compile(
            '.selector{ convert: convert( 10px, s ); }'
        );
    }
}
