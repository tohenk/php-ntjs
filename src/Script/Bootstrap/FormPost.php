<?php

/*
 * The MIT License
 *
 * Copyright (c) 2016 Toha <tohenk@yahoo.com>
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

namespace NTLAB\JS\Script\Bootstrap;

use NTLAB\JS\Script\JQuery\FormPost as Base;
use NTLAB\JS\Util\JSValue;

/**
 * Handling form submission using ajax.
 *
 * Usage:
 * <?php
 * 
 * use NTLAB\JS\Script;
 * 
 * $script = Script::create('Bootstrap.FormPost');
 * $script->call('#myform');
 * ?>
 *
 * @author Toha
 */
class FormPost extends Base
{
    protected function configure()
    {
        $this->addDependencies(array('JQuery.FormPost', 'Bootstrap.Dialog', 'Bootstrap.Dialog.Wait'));
    }

    protected function getErrHelperOptions()
    {
        return array(
            'errorContainer' => '.alert-danger',
            'errorFormat' => JSValue::createRaw('$.errformat.INPLACE'),
            'parentSelector' => '.form-group',
            'parentClass' => 'has-error',
            'errClass' => 'is-invalid',
            'toggleClass' => 'd-none',
            'listClass' => 'list-unstyled mb-0',
            'inplace' => JSValue::createRaw(<<<EOF
function(el, error) {
            if (el.hasClass('alert-danger')) {
                el.html(error);
            } else {
                // don't add tooltip on hidden input
                if (el.is('input[type="hidden"]')) {
                    var el = el.siblings('input');
                }
                var tooltip = el.data('bs.tooltip');
                if (tooltip != undefined) {
                    tooltip.config.title = error;
                } else {
                    el.tooltip({title: error, placement: 'right'});
                }
            }
        }
EOF
            ),
            'onErrReset' => JSValue::createRaw(<<<EOF
function(helper) {
            if (helper.container) {
                helper.container.find(helper.parentSelector + ' .' + helper.errClass).each(function() {
                    var el = $(this);
                    var tooltip = el.data('bs.tooltip');
                    if (tooltip != undefined) {
                        tooltip.options.title = '';
                    }
                    el.removeClass(helper.errClass);
                });
            }
        }
EOF
            ),
        );
    }
}