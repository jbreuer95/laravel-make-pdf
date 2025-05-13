<?php

namespace Breuer\MakePDF;

use Breuer\MakePDF\Enums\Format;
use Breuer\MakePDF\Enums\Orientation;
use Breuer\MakePDF\Enums\Unit;
use Facebook\WebDriver\Chrome\ChromeDevToolsDriver;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Client
{
    protected ChromeDriver $browser;

    protected ChromeDevToolsDriver $devTools;

    protected string $filename = 'download.pdf';

    protected Orientation $orientation = Orientation::PORTRAIT;

    protected float $paperWidth = 8.27;

    protected float $paperHeight = 11.69;

    protected float $marginTop = 0;

    protected float $marginBottom = 0;

    protected float $marginLeft = 0;

    protected float $marginRight = 0;

    protected string $html = '';

    protected string $footerHtml = '';

    protected string $headerHtml = '';

    protected string $viewName = '';

    protected string $headerViewName = '';

    protected string $footerViewName = '';

    /** @var array<mixed> */
    protected array $viewData = [];

    /** @var array<mixed> */
    protected array $headerViewData = [];

    /** @var array<mixed> */
    protected array $footerViewData = [];

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

    public function save(string $path): self
    {
        File::put($path, $this->getContent());

        return $this;
    }

    public function raw(): string
    {
        return $this->getContent();
    }

    /** @param array<mixed> $data */
    public function view(string $view, array $data = []): self
    {
        $this->viewName = $view;

        $this->viewData = $data;

        return $this;
    }

    /** @param array<mixed> $data */
    public function headerView(string $view, array $data = []): self
    {
        $this->headerViewName = $view;

        $this->headerViewData = $data;

        return $this;
    }

    /** @param array<mixed> $data */
    public function footerView(string $view, array $data = []): self
    {
        $this->footerViewName = $view;

        $this->footerViewData = $data;

        return $this;
    }

    public function html(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function headerHtml(string $html): self
    {
        $this->headerHtml = $html;

        return $this;
    }

    public function footerHtml(string $html): self
    {
        $this->footerHtml = $html;

        return $this;
    }

    public function name(string $filename): self
    {
        $this->filename = Str::finish($filename, '.pdf');

        return $this;
    }

    public function landscape(): self
    {
        $this->orientation = Orientation::LANDSCAPE;

        return $this;
    }

    public function format(Format $format): self
    {
        $this->paperHeight = $format->heightInInches();
        $this->paperWidth = $format->widthInInches();

        return $this;
    }

    public function paperSize(float $height, float $width, Unit $unit = Unit::INCH): self
    {
        $this->paperHeight = $unit->toInches($height);
        $this->paperWidth = $unit->toInches($width);

        return $this;
    }

    public function margins(float $top = 0, float $right = 0, float $bottom = 0, float $left = 0, Unit $unit = Unit::INCH): self
    {
        $this->marginTop = $unit->toInches($top);
        $this->marginBottom = $unit->toInches($bottom);
        $this->marginLeft = $unit->toInches($left);
        $this->marginRight = $unit->toInches($right);

        return $this;
    }

    protected function getContent(): string
    {
        if ($this->viewName) {
            $this->html = view()->exists($this->viewName) ? view($this->viewName, $this->viewData)->render() : '';
        }

        if ($this->headerViewName) {
            $this->headerHtml = view()->exists($this->headerViewName) ? view($this->headerViewName, $this->headerViewData)->render() : '';
        }

        if ($this->footerViewName) {
            $this->footerHtml = view()->exists($this->footerViewName) ? view($this->footerViewName, $this->footerViewData)->render() : '';
        }

        $this->browser = $this->startBrowser();

        try {
            $this->browser->get('data:text/html;charset=utf-8,'.rawurlencode($this->html));

            $displayHeaderFooter = ! empty($this->footerHtml) || ! empty($this->headerHtml);

            $this->devTools = $this->browser->getDevTools();
            $response = $this->devTools->execute('Page.printToPDF', [
                'landscape' => $this->orientation === Orientation::LANDSCAPE,
                'printBackground' => true,
                'displayHeaderFooter' => $displayHeaderFooter,
                'headerTemplate' => $this->headerHtml,
                'footerTemplate' => $this->footerHtml,
                'paperWidth' => $this->paperWidth,
                'paperHeight' => $this->paperHeight,
                'marginTop' => $this->marginTop,
                'marginBottom' => $this->marginBottom,
                'marginLeft' => $this->marginLeft,
                'marginRight' => $this->marginRight,
            ]);
        } finally {
            $this->browser->quit();
        }

        return base64_decode($response['data']);
    }

    protected function startBrowser(): ChromeDriver
    {
        $chrome_driver_binary = $this->chromeDriverBinary();
        $chrome_headless_binary = $this->chromeHeadlessBinary();

        if (! File::exists($chrome_driver_binary) || ! File::exists($chrome_headless_binary)) {
            throw new \Exception('chrome binary not found, please run: php artisan make-pdf:install');
        }

        putenv('WEBDRIVER_CHROME_DRIVER='.$chrome_driver_binary);

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
        $chromeOptions->setBinary($chrome_headless_binary);

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
