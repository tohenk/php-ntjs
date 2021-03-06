<?php

namespace NTLAB\JS\Test;

use NTLAB\JS\Manager;
use NTLAB\JS\Backend;
use NTLAB\JS\Script;

class ScriptTest extends BaseTest
{
    protected $script;

    protected function setUp(): void
    {
        Manager::getInstance()->setBackend(new Backend());
        $this->script = Script::create('JQuery');
        $this->script->getRepository()
          ->setWrapper(<<<EOF
(function($) {%s})(jQuery);
EOF
          )
          ->setWrapSize(1)
        ;
    }

    public function testCreate()
    {
        $this->assertEquals('NTLAB\JS\Script\JQuery', get_class($this->script), 'Resolver can resolve script name to class name');
    }

    public function testScript()
    {
        $this->script->add('$.test();');
        $this->assertTrue(Manager::getInstance()->has('jquery'), 'Repository properly initialized when script added');
        $this->assertEquals(<<<EOF
(function($) {
    $.test();
})(jQuery);
EOF
, $this->script->getRepository()->getContent(), 'Script properly added');
    }
}