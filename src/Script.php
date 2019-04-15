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

use NTLAB\JS\Util\Asset;
use NTLAB\JS\Util\Escaper;

/**
 * A base class to write javascript code easily in PHP.
 *
 * Using object approach to write javascript code to create well
 * maintained code and automatic dependency inclusion.
 * 
 * @author Toha
 */
abstract class Script
{
    /**
     * @var string
     */
    protected $position = Repository::POSITION_LAST;

    /**
     * @var array
     */
    protected $dependencies = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected static $included = array();

    /**
     * @var array
     */
    protected static $maps = array();

    /**
     * @var array
     */
    protected static $defaultOptions = array();

    /**
     * @var \NTLAB\JS\Util\Asset
     */
    protected $defaultAsset = null;

    /**
     * @var \NTLAB\JS\Util\Asset
     */
    protected $asset = null;

    /**
     * @var int
     */
    protected $priority = null;

    /**
     * @var array
     */
    protected $assets = array();

    /**
     * @var array
     */
    protected $props = array();

    /**
     * @var array
     */
    protected static $priorities = array();

    /**
     * @var array
     */
    protected static $defaults = array();

    /**
     * Create script object.
     *
     * @param string $name  The script name
     * @throws \InvalidArgumentException
     * @return \NTLAB\JS\Script
     */
    public static function create($name)
    {
        if (!isset(static::$maps[$name])) {
            if (null == $class = Manager::getInstance()->resolveDependency($name)) {
                throw new \RuntimeException(sprintf('Can\'t resolve script "%s".', $name));
            }
            static::$maps[$name] = array('class' => $class);
        }
        if (!isset(static::$maps[$name]['obj'])) {
            $class = static::$maps[$name]['class'];
            $options = isset(static::$defaultOptions[$name]) ? static::$defaultOptions[$name] : array();
            static::$maps[$name]['obj'] = new $class($options);
        }

        return static::$maps[$name]['obj'];
    }

    /**
     * Get class alias.
     *
     * @param string $class  Class name (full qualified)
     * @return string
     */
    public static function alias($class)
    {
        foreach (static::$maps as $name => $map) {
            if (isset($map['class']) && $class == $map['class']) {
                return $name;
            }
        }
    }

    /**
     * Set script prefered priority.
     *
     * @param string $name  The script name
     * @param int $priority  Script priority
     */
    public static function setPreferedPriority($name, $priority)
    {
        static::$priorities[$name] = $priority;
    }

    /**
     * Register default script.
     *
     * @param string $name  The script name
     * @param int $priority  Stylesheet priority
     */
    public static function registerDefault($name, $priority = null)
    {
        if (!in_array($name, static::$defaults)) {
            static::$defaults[] = $name;
            if (null !== $priority) {
                static::$priorities[$name] = $priority;
            }
        }
    }

    /**
     * Add script default options.
     *
     * @param string $name  The script name
     * @param array $options  Script options
     */
    public static function addOptions($name, $options)
    {
        static::$defaultOptions[$name] = $options;
    }

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = $options;
        $this->defaultAsset = new Asset($this->getRepositoryName());
        $this->initialize();
        $this->configure();
    }

    /**
     * Get the script manager instance.
     *
     * @return \NTLAB\JS\Manager
     */
    public function getManager()
    {
        return Manager::getInstance();
    }

    /**
     * Get the script repository instance.
     *
     * @return \NTLAB\JS\Repository
     */
    public function getRepository()
    {
        if (null === ($repoName = $this->getRepositoryName())) {
            throw new \Exception(sprintf('Repository "%s" is not implemented yet.', $repoName));
        }
        $exist = $this->getManager()->has($repoName);
        $repo = $this->getManager()->get($repoName);
        if (!$exist) {
            $this->initRepository($repo);
        }

        return $repo;
    }

    /**
     * Get script id.
     *
     * @return string
     */
    protected function getRepositoryName()
    {
    }

    /**
     * Initialize script repository.
     *
     * @param \NTLAB\JS\Repository $repo  The script repository name
     */
    protected function initRepository(Repository $repo)
    {
    }

    /**
     * Do initialization.
     */
    protected function initialize()
    {
    }

    /**
     * Do configuration.
     */
    protected function configure()
    {
    }

    /**
     * Get script backend.
     *
     * @return \NTLAB\JS\BackendInterface
     */
    protected function getBackend()
    {
        return $this->getManager()->getBackend();
    }

    /**
     * Add script dependencies.
     *
     * @param array $dependencies  The dependencies
     * @return \NTLAB\JS\Script
     */
    protected function addDependencies()
    {
        foreach (func_get_args() as $deps) {
            $this->dependencies = array_merge($this->dependencies, is_array($deps) ? $deps : array($deps));
        }

        return $this;
    }

    /**
     * Set script position.
     *
     * @param string $position  The position
     * @return \NTLAB\JS\Script
     */
    protected function setPosition($position = Repository::POSITION_LAST)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Check if script is already included.
     *
     * @return boolean
     */
    protected function isIncluded($class = null)
    {
        return array_key_exists(null === $class ? get_class($this) : $class, static::$included);
    }

    /**
     * Mark script as already included.
     *
     * @return \NTLAB\JS\Script
     */
    protected function markAsIncluded()
    {
        static::$included[get_class($this)] = $this;

        return $this;
    }

    /**
     * Include script dependencies.
     *
     * @param array $dependencies  The dependencies to load
     * @return \NTLAB\JS\Script
     */
    protected function includeDepedencies($dependencies = null)
    {
        $dependencies = null === $dependencies ? $this->dependencies : $dependencies;
        foreach ((array) $dependencies as $class) {
            $this->create($class)
                ->includeScript();
        }

        return $this;
    }

    /**
     * Include defaults script.
     *
     * @return \NTLAB\JS\Script
     */
    public function includeDefaults()
    {
        $this->includeDepedencies(static::$defaults);

        return $this;
    }

    /**
     * Include script content if its not already included.
     *
     * @return \NTLAB\JS\Script
     */
    public function includeScript()
    {
        if (!$this->isIncluded()) {
            $this->markAsIncluded();
            $this->includeDefaults();
            $this->includeDepedencies();
            $this->buildScript();
        }

        return $this;
    }

    /**
     * Build script.
     *
     * @return \NTLAB\JS\Script
     */
    protected function buildScript()
    {
        $this->includeAssets();
        if ($script = $this->getScript()) {
            $this->useScript($script);
        }
        $this->getInitScript();

        return $this;
    }

    /**
     * Use script.
     *
     * @param string $script  The script to include
     * @param string $position  Script position
     * @return \NTLAB\JS\Script
     */
    protected function useScript($script, $position = null)
    {
        $this->getRepository()->add($script, null === $position ? $this->position : $position);

        return $this;
    }

    /**
     * Include script assets.
     */
    protected function includeAssets()
    {
        foreach ($this->assets as $asset) {
            switch ($asset[0]) {
                case Asset::ASSET_JAVASCRIPT:
                    if (count($asset) > 4) {
                        $this->useLocaleJavascript($asset[1], $asset[4], null, $asset[2], $asset[3]);
                    } else {
                        $this->useJavascript($asset[1], $asset[2], $asset[3]);
                    }
                    break;
                case Asset::ASSET_STYLESHEET:
                    $this->useStylesheet($asset[1], $asset[2], $asset[3]);
                    break;
            }
        }
    }

    /**
     * Get script content.
     *
     * @return string
     */
    public function getScript()
    {
    }

    /**
     * Get script initialization code.
     */
    public function getInitScript()
    {
    }

    /**
     * Set script priority.
     *
     * @param int $priority  Script priority
     * @return \NTLAB\JS\Script
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get script priority (limited to stylesheet).
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get script prefered priority.
     *
     * @return int
     */
    public function getPreferedPriority()
    {
        if ($alias = static::alias(get_class($this))) {
            return isset(static::$priorities[$alias]) ? static::$priorities[$alias] : null;
        }
    }

    /**
     * Add script option.
     *
     * @param string $name  The option name
     * @param mixed $value  The option value
     * @return \NTLAB\JS\Script
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Get script option.
     *
     * @param string $name  The option name
     * @param mixed $default  The default value
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * Set script asset.
     *
     * @param \NTLAB\JS\Util\Asset  $asset
     * @return \NTLAB\JS\Script
     */
    public function setAsset($asset)
    {
        $this->asset = $asset;

        return $this;
    }

    /**
     * Get asset helper.
     *
     * @return \NTLAB\JS\Util\Asset
     */
    public function getAsset()
    {
        return $this->asset ?: $this->defaultAsset;
    }

    /**
     * Generate asset name.
     *
     * @param string $name  Asset name
     * @param int $type  Asset type
     * @param \NTLAB\JS\Util\Asset $asset  Asset helper
     * @return string
     */
    public function generateAsset($name, $type, $asset = null)
    {
        $asset = $asset ?: $this->getAsset();

        return $asset->get($type, $name);
    }

    /**
     * Add javascript.
     *
     * @param string $js  Javascript to include
     * @param int $priority  Javascript priority
     * @return \NTLAB\JS\Script
     */
    public function addJavascript($js, $priority = null)
    {
        $this->getBackend()->addAsset($js, BackendInterface::ASSET_JS, $priority ?: BackendInterface::ASSET_PRIORITY_DEFAULT);

        return $this;
    }

    /**
     * Remove javascript.
     *
     * @param string $js  Javascript to remove
     * @return \NTLAB\JS\Script
     */
    public function removeJavascript($js)
    {
        $this->getBackend()->removeAsset($js, BackendInterface::ASSET_JS);

        return $this;
    }

    /**
     * Add stylesheet.
     *
     * @param string $css  Stylesheet to include
     * @param int $priority  Stylesheet priority
     * @return \NTLAB\JS\Script
     */
    public function addStylesheet($css, $priority = null)
    {
        $this->getBackend()->addAsset($css, BackendInterface::ASSET_CSS, $priority ?: BackendInterface::ASSET_PRIORITY_DEFAULT);

        return $this;
    }

    /**
     * Remove stylesheet.
     *
     * @param string $css  Stylesheet to remove
     * @return \NTLAB\JS\Script
     */
    public function removeStylesheet($css)
    {
        $this->getBackend()->removeAsset($css, BackendInterface::ASSET_CSS);

        return $this;
    }

    /**
     * Include javascript. The javascript name accepted with the following
     * format:
     *   %name%-%version%.%minified%.js
     *
     * @param string $name  The javascript name, e.q. jquery.form
     * @param \NTLAB\JS\Util\Asset $asset  Asset helper
     * @param int $priority  Script priority
     * @return \NTLAB\JS\Script
     */
    public function useJavascript($name, $asset = null, $priority = null)
    {
        $this->addJavascript($this->generateAsset($name, Asset::ASSET_JAVASCRIPT, $asset), $priority);

        return $this;
    }

    /**
     * Get all possible locale variants.
     *
     * @param string $culture
     * @param string $default
     * @return array
     */
    protected function getLocales($culture, $default)
    {
        $locales = array();
        $delimeter = null;
        if ($culture) {
            $locales[] = $culture;
            foreach (array('-', '_') as $delim) {
                if (false != strpos($culture, $delim)) {
                    $delimeter = $delim;
                    break;
                }
            }
            if ($delimeter) {
                $locales[] = strtr($culture, array('-' => '_', '_' => '-'));
                $locales[] = substr($culture, 0, strpos($culture, $delimeter));
            }
        }
        if ($default && !in_array($default, $locales)) {
            $locales[] = $default;
        }

        return $locales;
    }

    /**
     * Include locale javascript.
     *
     * @param string $name     Javascript name
     * @param string $culture  The user culture
     * @param string $default  Default culture
     * @param \NTLAB\JS\Util\Asset $asset  Asset helper
     * @param int $priority  Script priority
     * @return \NTLAB\JS\Script
     */
    public function useLocaleJavascript($name, $culture = null, $default = 'en', $asset = null, $priority = null)
    {
        if (false == strpos($name, '%s')) {
            $name .= '%s';
        }
        $asset   = $asset ?: $this->getAsset();
        $baseDir = $this->getBackend()->getConfig('base-dir').DIRECTORY_SEPARATOR.$asset->getDir();
        $culture = $culture ?: $this->getBackend()->getConfig('default-culture');
        // check file
        foreach ($this->getLocales($culture, $default ?: 'en') as $locale) {
            $localeJs = sprintf($name, $locale).$asset->getExtension(Asset::ASSET_JAVASCRIPT);
            $realJs = $baseDir;
            if ($dirName = $asset->getDirName(Asset::ASSET_JAVASCRIPT)) {
                $realJs .= DIRECTORY_SEPARATOR.$dirName;
            }
            $realJs .= DIRECTORY_SEPARATOR.$localeJs;
            if (is_readable($realJs)) {
                $this->useJavascript($localeJs, $asset, $priority);
                break;
            }
        }

        return $this;
    }

    /**
     * Include stylesheet.
     *
     * @param string $name  The stylesheet name, e.q. ui
     * @param \NTLAB\JS\Util\Asset $asset  Asset helper
     * @param int $priority  Stylesheet priority
     * @return \NTLAB\JS\Script
     */
    public function useStylesheet($name, $asset = null, $priority = null)
    {
        $this->addStylesheet($this->generateAsset($name, Asset::ASSET_STYLESHEET, $asset), $priority);

        return $this;
    }

    /**
     * Add script asset.
     *
     * @param string $type  Asset type
     * @param string $name  Asset name
     * @param int $priority  Priority
     * @return \NTLAB\JS\Script
     */
    public function addAsset($type, $name, $priority = null)
    {
        $asset = $this->getAsset();
        $key = implode(':', array($type, $asset->getRepository(), $name));
        if (!isset($this->assets[$key])) {
            $this->assets[$key] = array($type, $name, $asset, $priority);
        }

        return $this;
    }

    /**
     * Add script locale asset.
     *
     * @param string $type
     * @param string $name
     * @param string $culture
     * @param int $priority
     * @return \NTLAB\JS\Script
     */
    public function addLocaleAsset($type, $name, $culture = null, $priority = null)
    {
        $asset = $this->getAsset();
        $key = implode(':', array($type, $asset->getRepository(), $name, $culture));
        if (!isset($this->assets[$key])) {
            $this->assets[$key] = array($type, $name, $asset, $priority, $culture);
        }

        return $this;
    }

    /**
     * Remove asset.
     *
     * @param string $type
     * @param string $name
     * @return \NTLAB\JS\Script
     */
    public function removeAsset($type, $name)
    {
        $asset = $this->getAsset();
        $key = implode(':', array($type, $asset->getRepository(), $name));
        if (isset($this->assets[$key])) {
            unset($this->assets[$key]);
        }

        return $this;
    }

    /**
     * Add script dependency.
     *
     * @param string $dependencies  The dependency script name
     * @return \NTLAB\JS\Script
     */
    public function includeDependency($dependencies)
    {
        $this->addDependencies(is_array($dependencies) ? $dependencies : array($dependencies));

        return $this;
    }

    /**
     * Translate text.
     *
     * @param string $text  Text to translate
     * @param array $vars  Text variables
     * @param string $domain  Text domain
     * @return string
     */
    protected function trans($text, $vars = array(), $domain = null)
    {
        return $this->getBackend()->trans($text, $vars, $domain);
    }

    /**
     * Translate URL.
     *
     * @param string $url  Raw url
     * @param array $options  URL options
     * @return string
     */
    protected function url($url, $options = array())
    {
        return $this->getBackend()->url($url, $options);
    }

    /**
     * Get property value.
     *
     * @param string $name  Property name
     * @param mixed $default  Default values if property doesn't already set
     * @return mixed
     */
    public function getProp($name, $default = null)
    {
        return isset($this->props[$name]) ? $this->props[$name] : $default;
    }

    /**
     * Set property value.
     *
     * @param string $name  Property name
     * @param mixed $value  Property value
     * @return \NTLAB\JS\Script
     */
    public function setProp($name, $value)
    {
        $this->props[$name] = $value;

        return $this;
    }

    /**
     * Indent lines of code.
     *
     * @param string $lines  The code
     * @param int $size  Indentation size
     * @return string
     */
    public static function indentLines($lines, $size = 4)
    {
        $result = array();
        $pad = str_repeat(' ', $size);
        foreach (explode(Escaper::getEol(), $lines) as $line) {
            if (strlen($line)) {
                $line = $pad.$line;
            }
            $result[] = $line;
        }

        return implode(Escaper::getEol(), $result);
    }
}