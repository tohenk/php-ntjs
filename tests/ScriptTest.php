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
use NTLAB\JS\DependencyResolver;
use NTLAB\JS\Manager;
use NTLAB\JS\Backend;
use NTLAB\JS\Script;
use NTLAB\JS\Util\Escaper;
use NTLAB\JS\Test\Script\TestScript;

class ScriptTest extends TestCase
{
    protected function setUp(): void
    {
        Manager::getInstance()
            ->setBackend(new Backend())
            ->addResolver(new DependencyResolver('NTLAB\JS\Test\Script'));
        Escaper::setEol("\r\n");
    }

    public function testCreate()
    {
        $this->assertEquals(TestScript::class, get_class(Script::create('TestScript')), 'Resolver can resolve script name to class name');
    }

    public function testCall()
    {
        $script = Script::create('TestScript');
        $script
            ->add('do_something()')
            ->call('a message');
        $this->assertEquals(<<<EOF
do_something();
// a message
EOF
        , $script->getRepository()->getContent(), 'Script call() should include script doCall()');
    }

    public function testAutoIncludeOff()
    {
        Script::setDefaultOption('autoInclude', false);

        $script = Script::create('IncludeScript');
        $script
            ->add('call_include_script()');
        $this->assertEquals(<<<EOF
call_include_script();
EOF
        , $script->getRepository()->getContent(), 'Script auto include off should not add script content');
    }

    public function testAutoIncludeOn()
    {
        Script::setDefaultOption('autoInclude', true);

        $script = Script::create('IncludeScript');
        $script
            ->add('call_include_script()');
        $this->assertEquals(<<<EOF
// include script test content
call_include_script();
EOF
        , $script->getRepository()->getContent(), 'Script auto include on should add script content');
    }

    public function testInitializer()
    {
        $script = Script::create('NoRepoScript');
        $script->add('$.test();');
        $this->assertEquals(<<<EOF
(function() {
    $.test();
})();
EOF
        , $script->getRepository()->getContent(), 'Script properly decorated with default repository initializer');
    }
}
