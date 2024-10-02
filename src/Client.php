<?php

namespace Breuer\PDF;

use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class Client
{
    protected ChromeDriver $browser;

    protected ChromeDevToolsDriver $devTools;

    protected string $filename = 'download.pdf';

    protected string $html = '';

    public string $viewName = '';

    /**
     * @var array<mixed>
     */
    public array $viewData = [];

    public function __construct()
    {
        $this->browser = $this->startBrowser();
        $this->devTools = $this->browser->getDevTools();
    }

    public function __destruct()
    {
        try {
            $this->browser->quit();
        } catch (\Throwable $th) {
        }
    }

    public function response(): Response
    {
        return response($this->getContent(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$this->filename.'"',
        ]);
    }

    public function download(): Response
    {
        return response($this->getContent(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$this->filename.'"',
        ]);
    }

    /**
     * @param  array<mixed>  $data
     */
    public function view(string $view, array $data = []): self
    {
        $this->viewName = $view;

        $this->viewData = $data;

        return $this;
    }

    public function html(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function name(string $filename): self
    {
        $this->filename = Str::finish($filename, '.pdf');

        return $this;
    }

    protected function getContent(): string
    {
        if ($this->viewName) {
            $this->html = view($this->viewName, $this->viewData)->render();
        }

        $this->browser->get('data:text/html;charset=utf-8,'.rawurlencode($this->html));

        $response = $this->devTools->execute('Page.printToPDF', [
            'printBackground' => true,
            'displayHeaderFooter' => false,
            'paperWidth' => 8.27,
            'paperHeight' => 11.69,
            'marginTop' => 0,
            'marginBottom' => 0,
            'marginLeft' => 0,
            'marginRight' => 0,
        ]);

        return base64_decode($response['data']);
    }

    protected function startBrowser(): ChromeDriver
    {
        putenv('WEBDRIVER_CHROME_DRIVER='.$this->chromeDriverBinary());

        $chromeOptions = new ChromeOptions;
        $chromeOptions->addArguments(['--disable-gpu']);
        $chromeOptions->addArguments(['--disable-translate']);
        $chromeOptions->addArguments(['--disable-extensions']);
        $chromeOptions->addArguments(['--disable-sync']);
        $chromeOptions->addArguments(['--disable-background-networking']);
        $chromeOptions->addArguments(['--disable-software-rasterizer']);
        $chromeOptions->addArguments(['--disable-default-apps']);
        $chromeOptions->addArguments(['--disable-dev-shm-usage']);
        $chromeOptions->addArguments(['--safebrowsing-disable-auto-update']);
        $chromeOptions->addArguments(['--run-all-compositor-stages-before-draw']);
        $chromeOptions->addArguments(['--no-first-run']);
        $chromeOptions->addArguments(['--no-sandbox']);
        $chromeOptions->addArguments(['--hide-scrollbars']);
        $chromeOptions->addArguments(['--ignore-certificate-errors']);
        $chromeOptions->setBinary($this->chromeHeadlessBinary());

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        return ChromeDriver::start($capabilities);
    }

    public static function chromeDriverBinary(): string
    {
        if (self::onWindows32()) {
            return package_path('browser', 'chromedriver-win32', 'chromedriver.exe');
        } elseif (self::onWindows64()) {
            return package_path('browser', 'chromedriver-win64', 'chromedriver.exe');
        } elseif (self::onLinux()) {
            return package_path('browser', 'chromedriver-linux64', 'chromedriver');
        } elseif (self::onMacARM()) {
            return package_path('browser', 'chromedriver-mac-arm64', 'chromedriver');
        } elseif (self::onMacIntel()) {
            return package_path('browser', 'chromedriver-mac-x64', 'chromedriver');
        }

        throw new \Exception('Platform not supported');
    }

    public static function chromeHeadlessBinary(): string
    {
        if (self::onWindows32()) {
            return package_path('browser', 'chrome-headless-shell-win32', 'chrome-headless-shell.exe');
        } elseif (self::onWindows64()) {
            return package_path('browser', 'chrome-headless-shell-win64', 'chrome-headless-shell.exe');
        } elseif (self::onLinux()) {
            return package_path('browser', 'chrome-headless-shell-linux64', 'chrome-headless-shell');
        } elseif (self::onMacARM()) {
            return package_path('browser', 'chrome-headless-shell-mac-arm64', 'chrome-headless-shell');
        } elseif (self::onMacIntel()) {
            return package_path('browser', 'chrome-headless-shell-mac-x64', 'chrome-headless-shell');
        }

        throw new \Exception('Platform not supported');
    }

    public static function onWindows32(): bool
    {
        return PHP_OS_FAMILY === 'Windows' && PHP_INT_SIZE == 4;
    }

    public static function onWindows64(): bool
    {
        return PHP_OS_FAMILY === 'Windows' && PHP_INT_SIZE != 4;
    }

    public static function onMacARM(): bool
    {
        return PHP_OS_FAMILY === 'Darwin' && php_uname('m') === 'arm64';
    }

    public static function onMacIntel(): bool
    {
        return PHP_OS_FAMILY === 'Darwin' && php_uname('m') !== 'arm64';
    }

    public static function onLinux(): bool
    {
        return PHP_OS_FAMILY === 'Linux';
    }
}
