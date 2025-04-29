<?php

/*
 * The MIT License
 *
 * Copyright (c) 2015-2025 Toha <tohenk@yahoo.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace NTLAB\JS\Test;

use PHPUnit\Framework\TestCase;
use NTLAB\JS\Util\Escaper;
use NTLAB\JS\Util\JSValue;

class EscaperTest extends TestCase
{
    protected function setUp(): void
    {
        Escaper::setEol("\r\n");
    }

    public function testEscapeValue()
    {
        $this->assertEquals('null', Escaper::escapeValue(null, true), 'Proper escape value of null');
        $this->assertEquals('true', Escaper::escapeValue(true, true), 'Proper escape value of boolean');
        $this->assertEquals('49', Escaper::escapeValue(49, true), 'Proper escape value of integer');
        $this->assertEquals('49.5', Escaper::escapeValue(49.5, true), 'Proper escape value of float');
        $this->assertEquals('\'49\'', Escaper::escapeValue('49', true), 'Proper escape value of numeric string');
        $this->assertEquals("'It\'s me'", Escaper::escapeValue('It\'s me', true), 'Proper escape value of string');
    }

    public function testEscape()
    {
        $this->assertEquals('{}', Escaper::escape([]), 'Proper escape empty array');
        $this->assertEquals("[1, 'test']", Escaper::escape([1, 'test'], null, 0, true), 'Proper escape numeric indexed array');
        $this->assertEquals("{me: 'test', you: 100}", Escaper::escape(['me' => 'test', 'you' => 100], null, 0, true), 'Proper escape indexed array');
    }

    public function testNesting()
    {
        $this->assertEquals(<<<EOF
{
    test() {},
    testFn(a, b) {}
}
EOF
            , Escaper::escape([
                'test' => JSValue::createRaw('function() {}'),
                'testFn' => 'function(a, b) {}'
            ]), 'Proper escape nested');
    }
}
