<?php

/*
 * The MIT License
 *
 * Copyright (c) 2015 Toha <tohenk@yahoo.com>
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

namespace NTLAB\JS;

abstract class Compressor
{
    /**
     * @var string
     */
    protected $content = null;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var bool
     */
    protected $compressed = null;

    /**
     * Constructor.
     *
     * @param string $content  The content to compress
     * @param array $options  Compressor options
     */
    public function __construct($content, $options = array())
    {
        $this->content = $content;
        $this->options = $options;
    }

    /**
     * Compress content.
     *
     * @param string $content  The content to compress
     * @return string
     */
    abstract public function compress($content);

    /**
     * Get the compressed content.
     *
     * @return string
     */
    public function output()
    {
        if (null === $this->compressed) {
            $this->compressed = true;
            $this->content = $this->compress($this->content);
        }

        return $this->content;
    }
}