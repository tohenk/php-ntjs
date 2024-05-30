Code Javascript Everywhere!
===========================

PHP-NTJS allows to dynamically manage your javascripts, stylesheets, and
scripts so you can focus on your code. You can code your javascript using
PHP class, or write directly in the PHP code, even on template.

JQuery and Bootstrap
--------------------

Support for popular javascript like JQuery, Bootstrap, and FontAwesome.

CDN
---

To speed up your page, CDN can be enabled, PHP-NTJS will automatically do it
for you. Just loads needed CDN information and assets will loaded from CDN.

Minified Output
---------------

On production, you can enable script output compression either by using JSMin
or JShrink. On development, you can add script debug information to easily
locate problematic code.

Integrate With Your Code
------------------------

To integrate PHP-NTJS with your code, you need to enable [Composer](https://getcomposer.org)
support in your project.

* Require `ntlab/ntjs` and install dependencies.

```shell
php composer.phar require ntlab/ntjs
php composer.phar install
```

* Clone the assets somewhere in your public web folder.

```shell
git clone https://github.com/tohenk/ntjs-web-assets /path/to/www/cdn
```

* Create your script backend, which is responsible for collecting assets, it must
  be implements `NTLAB\JS\BackendInterface` or extends `NTLAB\JS\Backend`.
  An example of backend is available [here](https://github.com/tohenk/php-ntjs-demo/blob/master/src/Backend.php).

* Create script dependency resolver, which us responsible for resolving namespace
  when the script referenced. It must be implements `NTLAB\JS\DependencyResolverInterface`.
  An example of resolver is available [here](https://github.com/tohenk/php-ntjs-demo/blob/master/src/Backend.php).

* Optionally, create script compressor which implements `NTLAB\JS\CompressorInterface`.
  An example of compressor is available [here](https://github.com/tohenk/php-ntjs-demo/blob/master/src/Backend.php).

* Connect it together, see [example](https://github.com/tohenk/php-ntjs-demo/blob/master/src/Demo.php).

```php
use NTLAB\JS\Manager;
use NTLAB\JS\Script;

class MyClass
{
    protected $useCDN = true;
    protected $minifyScript = false;
    protected $debugScript = true;

    public function initialize()
    {
        $manager = Manager::getInstance();
        // create backend instance
        $backend = new Backend($this->useCDN);
        // set script backend
        $manager->setBackend($backend);
        // register script resolver, the backend also a resolver
        $manager->addResolver($backend);
        // register script compressor, the backend also a compressor
        if ($this->minifyScript) {
            $manager->setCompressor($backend);
        }
        // set script debug information
        if ($this->debugScript) {
            Script::setDebug(true);
        }
    }
}
```

* Start write your javascript code, see [example](https://github.com/tohenk/php-ntjs-demo/blob/master/src/Script/MyDemo.php).

```php
use NTLAB\JS\Script;

class MyDemoClass
{
    public function something()
    {
        Script::create('JQuery')
            ->add(<<<EOF
alert('Do something');
EOF
            );
    }
}
```

* Add a helper to include stylesheets, javascripts and script to the HTML response,
  see this [example](https://github.com/tohenk/php-ntjs-demo/blob/master/src/Helper.php) and
  this [example](https://github.com/tohenk/php-ntjs-demo/blob/master/view/layout.php).

Available Scripts
-----------------

This table below contains collection of scripts and its usage.

| Script                                                                                                 | Usage                                                                    |
|--------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------|
| [`BigNumber`](/src/Script/BigNumber.php)                                                               | Include BigNumber assets                                                 |
| [`Bloodhound`](/src/Script/Bloodhound.php)                                                             | Include Bloodhound assets                                                |
| [`BlueimpGallery`](/src/Script/BlueimpGallery.php)                                                     | Include BlueImp Gallery assets                                           |
| [`Bootstrap`](/src/Script/Bootstrap.php)                                                               | Include Bootstrap assets                                                 |
| [`Bootstrap.DateTimePicker`](/src/Script/Bootstrap/DateTimePicker.php)                                 | Include Bootstrap DateTimePicker assets                                  |
| [`Bootstrap.DateTimePickerLocale`](/src/Script/Bootstrap/DateTimePickerLocale.php)                     | Include Bootstrap DateTimePicker locale assets                           |
| [`Bootstrap.DateTimePickerLocaleCustom`](/src/Script/Bootstrap/DateTimePickerLocaleCustom.php)         | Include Bootstrap DateTimePicker custom locale assets                    |
| [`Bootstrap.DateTimePickerPlugins`](/src/Script/Bootstrap/DateTimePickerPlugins.php)                   | Include Bootstrap DateTimePicker plugins assets                          |
| [`Bootstrap.Dialog`](/src/Script/Bootstrap/Dialog.php)                                                 | Bootstrap modal wrapper to create and handle dialog                      |
| [`Bootstrap.Dialog.Confirm`](/src/Script/Bootstrap/Dialog/Confirm.php)                                 | Bootstrap confirm modal                                                  |
| [`Bootstrap.Dialog.Iframe`](/src/Script/Bootstrap/Dialog/Iframe.php)                                   | Bootstrap iframe modal                                                   |
| [`Bootstrap.Dialog.IframeLoader`](/src/Script/Bootstrap/Dialog/IframeLoader.php)                       | Bootstrap iframe loader helper                                           |
| [`Bootstrap.Dialog.IframeRedir`](/src/Script/Bootstrap/Dialog/IframeRedir.php)                         | Bootstrap redirection helper to allow an Ajax iframe to reload           |
| [`Bootstrap.Dialog.IframeResize`](/src/Script/Bootstrap/Dialog/IframeResize.php)                       | Bootstrap iframe dialog auto-height                                      |
| [`Bootstrap.Dialog.Input`](/src/Script/Bootstrap/Dialog/Input.php)                                     | Bootstrap input modal                                                    |
| [`Bootstrap.Dialog.Message`](/src/Script/Bootstrap/Dialog/Message.php)                                 | Bootstrap message modal                                                  |
| [`Bootstrap.Dialog.Wait`](/src/Script/Bootstrap/Dialog/Wait.php)                                       | Bootstrap modal to show a waiting dialog while in progress               |
| [`Bootstrap.FormPost`](/src/Script/Bootstrap/FormPost.php)                                             | Handling form submission using ajax                                      |
| [`Bootstrap.Notify`](/src/Script/Bootstrap/Notify.php)                                                 | Handle notification either using Web Notification or Bootstrap Toast     |
| [`Bootstrap.Select`](/src/Script/Bootstrap/Select.php)                                                 | Include Bootstrap Select assets                                          |
| [`Bootstrap.StarRating`](/src/Script/Bootstrap/StarRating.php)                                         | Include Bootstrap StarRating assets                                      |
| [`Bootstrap.StarRatingLocale`](/src/Script/Bootstrap/StarRatingLocale.php)                             | Include Bootstrap StarRating locale assets                               |
| [`Bootstrap.StarRatingTheme`](/src/Script/Bootstrap/StarRatingTheme.php)                               | Include Bootstrap StarRating theme assets                                |
| [`Bootstrap.StarRatingThemeFa`](/src/Script/Bootstrap/StarRatingThemeFa.php)                           | Include Bootstrap StarRating FontAwesome theme assets                    |
| [`Bootstrap.StarRatingThemeFas`](/src/Script/Bootstrap/StarRatingThemeFas.php)                         | Include Bootstrap StarRating FontAwesome Solid theme assets              |
| [`Bootstrap.StarRatingThemeSvg`](/src/Script/Bootstrap/StarRatingThemeSvg.php)                         | Include Bootstrap StarRating SVG theme assets                            |
| [`Bootstrap.StarRatingThemeUnicode`](/src/Script/Bootstrap/StarRatingThemeUnicode.php)                 | Include Bootstrap StarRating Unicode theme assets                        |
| [`Bootstrap.Theme.DefaultTheme`](/src/Script/Bootstrap/Theme/DefaultTheme.php)                         | Bootstrap default theme                                                  |
| [`Bootstrap.Typeahead`](/src/Script/Bootstrap/Typeahead.php)                                           | Include Bootstrap Typeahead assets                                       |
| [`BootstrapIcons`](/src/Script/BootstrapIcons.php)                                                     | Include Bootstrap Icons assets                                           |
| [`Bootswatch`](/src/Script/Bootswatch.php)                                                             | Include Bootswatch assets                                                |
| [`CanvasToBlob`](/src/Script/CanvasToBlob.php)                                                         | Include BlueImp Javascript-Canvas-to-Blob assets                         |
| [`CKEditor`](/src/Script/CKEditor.php)                                                                 | Include CKEditor assets                                                  |
| [`Cropper`](/src/Script/Cropper.php)                                                                   | Include Cropper.js assets                                                |
| [`DataTables`](/src/Script/DataTables.php)                                                             | Include DataTables assets                                                |
| [`DataTablesI18N`](/src/Script/DataTablesI18N.php)                                                     | Include DataTables plugins I18N assets                                   |
| [`DataTablesResponsive`](/src/Script/DataTablesResponsive.php)                                         | Include DataTables Responsive extension assets                           |
| [`DataTablesRowGroup`](/src/Script/DataTablesRowGroup.php)                                             | Include DataTables RowGroup extension assets                             |
| [`DataTablesSelect`](/src/Script/DataTablesSelect.php)                                                 | Include DataTables Select extension assets                               |
| [`FontAwesome`](/src/Script/FontAwesome.php)                                                           | Include FontAwesome assets                                               |
| [`GoogleFonts`](/src/Script/GoogleFonts.php)                                                           | Include Google Fonts assets                                              |
| [`Highcharts`](/src/Script/Highcharts.php)                                                             | Include Highcharts assets                                                |
| [`Hotkeys`](/src/Script/Hotkeys.php)                                                                   | Include JQuery Hotkeys assets                                            |
| [`Interact`](/src/Script/Interact.php)                                                                 | Include Interact assets                                                  |
| [`JqGrid`](/src/Script/JqGrid.php)                                                                     | Include JqGrid assets                                                    |
| [`JqGridDefault`](/src/Script/JqGridDefault.php)                                                       | Provide Bootstrap Icons integration in JqGrid                            |
| [`JQuery`](/src/Script/JQuery.php)                                                                     | Include JQuery assets                                                    |
| [`JQuery.AjaxHelper`](/src/Script/JQuery/AjaxHelper.php)                                               | Ajax request helper                                                      |
| [`JQuery.Blob`](/src/Script/JQuery/Blob.php)                                                           | Retrieve content from an URL and transform it as blob for download       |
| [`JQuery.Callback.SetHtml`](/src/Script/JQuery/Callback/SetHtml.php)                                   | A callback handler to set html content of an element                     |
| [`JQuery.Callback.SetValue`](/src/Script/JQuery/Callback/SetValue.php)                                 | A callback handler to set the value of an element, such as an input tag  |
| [`JQuery.Dialog`](/src/Script/JQuery/Dialog.php)                                                       | JQuery UI dialog wrapper to create and handling dialog                   |
| [`JQuery.Dialog.Confirm`](/src/Script/JQuery/Dialog/Confirm.php)                                       | JQuery UI confirm dialog                                                 |
| [`JQuery.Dialog.Iframe`](/src/Script/JQuery/Dialog/Iframe.php)                                         | JQuery UI iframe dialog                                                  |
| [`JQuery.Dialog.IframeResize`](/src/Script/JQuery/Dialog/IframeResize.php)                             | JQuery UI iframe dialog auto-height                                      |
| [`JQuery.Dialog.Input`](/src/Script/JQuery/Dialog/Input.php)                                           | JQuery UI input dialog                                                   |
| [`JQuery.Dialog.Message`](/src/Script/JQuery/Dialog/Message.php)                                       | JQuery UI message dialog                                                 |
| [`JQuery.Dialog.Wait`](/src/Script/JQuery/Dialog/Wait.php)                                             | JQuery UI wait dialog to show a waiting dialog while in progress         |
| [`JQuery.FormPost`](/src/Script/JQuery/FormPost.php)                                                   | Handling form submission using ajax                                      |
| [`JQuery.NS`](/src/Script/JQuery/NS.php)                                                               | JQuery namespace helper, to avoid javascript function redefine           |
| [`JQuery.NumberFormat`](/src/Script/JQuery/NumberFormat.php)                                           | A JQuery number formatter helper                                         |
| [`JQuery.NumberFormatJQueryNumberFormatter`](/src/Script/JQuery/NumberFormatJQueryNumberFormatter.php) | Provide number formatting internal using JQuery Number Formatter         |
| [`JQuery.NumberFormatNumeralJs`](/src/Script/JQuery/NumberFormatNumeralJs.php)                         | Provide number formatting internal using NumeralJs                       |
| [`JQuery.Observer`](/src/Script/JQuery/Observer.php)                                                   | Observer and event handling                                              |
| [`JQuery.Overflow`](/src/Script/JQuery/Overflow.php)                                                   | Document body overflow utility                                           |
| [`JQuery.PostErrorHelper`](/src/Script/JQuery/PostErrorHelper.php)                                     | Handling ajax form submission error                                      |
| [`JQuery.PostHandler`](/src/Script/JQuery/PostHandler.php)                                             | Ajax POST handler                                                        |
| [`JQuery.RemoveAnchor`](/src/Script/JQuery/RemoveAnchor.php)                                           | Replace html tag into span tag. e.g. an anchor tag                       |
| [`JQuery.Resolver`](/src/Script/JQuery/Resolver.php)                                                   | Javascript namespace resolver                                            |
| [`JQuery.ScrollTo`](/src/Script/JQuery/ScrollTo.php)                                                   | Update window scroll top based on element                                |
| [`JQuery.Spinner`](/src/Script/JQuery/Spinner.php)                                                     | Include JQuery Spinner assets and provide a helper                       |
| [`JQuery.Swipe`](/src/Script/JQuery/Swipe.php)                                                         | Provide swipe support to an element                                      |
| [`JQuery.Typeahead`](/src/Script/JQuery/Typeahead.php)                                                 | A JQuery typeahead (auto complete)                                       |
| [`JQuery.UI`](/src/Script/JQuery/UI.php)                                                               | JQuery UI base class for script that is depends on JQuery UI             |
| [`JQuery.UI.AutoComplete`](/src/Script/JQuery/UI/AutoComplete.php)                                     | A JQuery UI auto complete                                                |
| [`JQuery.UI.FormPost`](/src/Script/JQuery/UI/FormPost.php)                                             | Handling form submission using ajax                                      |
| [`JQuery.Util`](/src/Script/JQuery/Util.php)                                                           | Common utility for javascript                                            |
| [`JsCookie`](/src/Script/JsCookie.php)                                                                 | Include JsCookie assets                                                  |
| [`Jhashtable`](/src/Script/Jshashtable.php)                                                            | Include Jhashtable assets                                                |
| [`Jstree`](/src/Script/Jstree.php)                                                                     | Include Jstree assets                                                    |
| [`Keyboard`](/src/Script/Keyboard.php)                                                                 | Include an on-screen keyboard javascript and assets                      |
| [`Leaflet`](/src/Script/Leaflet.php)                                                                   | Include Leaflet assets                                                   |
| [`LoadImage`](/src/Script/LoadImage.php)                                                               | Include BlueImp JavaScript-Load-Image assets                             |
| [`MomentJs`](/src/Script/MomentJs.php)                                                                 | Include MomentJs assets                                                  |
| [`NumberFormatter`](/src/Script/NumberFormatter.php)                                                   | Include JQuery NumberFormatter assets                                    |
| [`NumeralJs`](/src/Script/NumeralJs.php)                                                               | Include NumeralJs assets                                                 |
| [`Pdfjs`](/src/Script/Pdfjs.php)                                                                       | Include PdfJs assets                                                     |
| [`PdfjsViewer`](/src/Script/PdfjsViewer.php)                                                           | Include PdfJs Viewer assets                                              |
| [`Popper`](/src/Script/Popper.php)                                                                     | Include Popper.js assets                                                 |
| [`ReCaptchaV2`](/src/Script/ReCaptchaV2.php)                                                           | Include Google ReCaptcha V2 assets                                       |
| [`ReCaptchaV3`](/src/Script/ReCaptchaV3.php)                                                           | Include Google ReCaptcha V3 assets                                       |
| [`SCEditor`](/src/Script/SCEditor.php)                                                                 | Include SCEditor assets                                                  |
| [`SocketIO`](/src/Script/SocketIO.php)                                                                 | Include Socket.io assets                                                 |
| [`Strophe`](/src/Script/Strophe.php)                                                                   | Include Strophe assets                                                   |
| [`Templates`](/src/Script/Templates.php)                                                               | Include BlueImp JavaScript-Templates assets                              |
| [`TinyMCE`](/src/Script/TinyMCE.php)                                                                   | Include TinyMCE assets                                                   |
| [`Upload`](/src/Script/Upload.php)                                                                     | Include BlueImp JQuery-File-Upload assets                                |

Live Demo
---------

Live demo is available [here](https://apps.ntlab.id/demo/php-ntjs).
