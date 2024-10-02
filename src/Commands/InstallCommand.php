<?php

namespace Breuer\PDF\Commands;

use Breuer\PDF\Client;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ZipArchive;

use function Breuer\PDF\package_path;

class InstallCommand extends Command
{
    public $signature = 'headless-pdf:install';

    public $description = 'Download latest stable chrome-headless-shell and chromedriver';

    public function handle(): int
    {
        if (! File::exists(package_path('browser'))) {
            $this->info('Creating directory: '.package_path('browser'));
            File::ensureDirectoryExists(package_path('browser'));
        } else {
            $this->info('Removing old browser installations');
            File::deleteDirectory(package_path('browser'), true);
        }

        $this->info('Fetching latest chrome build information');
        $response = Http::get('https://googlechromelabs.github.io/chrome-for-testing/last-known-good-versions-with-downloads.json');
        $headless_chrome_downloads = $this->findHeadlessChromeDownloadsInResponse($response);
        $chromedriver_downloads = $this->findChromeDriveDownloadsInResponse($response);

        foreach ($headless_chrome_downloads as $download) {
            if ($download->platform !== $this->getPlatformKey()) {
                continue;
            }

            $this->info('Downloading latest stable headless chrome');
            $zipfile = package_path('browser/chrome-headless-shell.zip');
            Http::sink($zipfile)->get($download->url);

            $this->info('Unzipping');
            $zip = new ZipArchive;
            $zip->open($zipfile);
            $zip->extractTo(package_path('browser'));
            $zip->close();

            File::delete($zipfile);

            break;
        }
        foreach ($chromedriver_downloads as $download) {
            if ($download->platform !== $this->getPlatformKey()) {
                continue;
            }

            $this->info('Downloading latest stable chromedriver');
            $zipfile = package_path('browser/chromedriver.zip');
            Http::sink($zipfile)->get($download->url);

            $this->info('Unzipping');
            $zip = new ZipArchive;
            $zip->open($zipfile);
            $zip->extractTo(package_path('browser'));
            $zip->close();

            File::delete($zipfile);

            break;
        }

        $this->info('Fixing permissions');
        chmod(Client::chromeDriverBinary(), 0755);
        chmod(Client::chromeHeadlessBinary(), 0755);

        $this->info('Installation complete');

        return self::SUCCESS;
    }

    /**
     * @return array<int, object{platform: string, url: string}>
     */
    protected function findChromeDriveDownloadsInResponse(Response $response): array
    {
        if (! $response->ok()) {
            throw new \Exception('Problem connecting to googlechromelabs.com');
        }
        if (empty($response->object()->channels->Stable->downloads->chromedriver)) {
            throw new \Exception('Problem parsing response from googlechromelabs.com');
        }

        return $response->object()->channels->Stable->downloads->chromedriver;
    }

    /**
     * @return array<int, object{platform: string, url: string}>
     */
    protected function findHeadlessChromeDownloadsInResponse(Response $response): array
    {
        if (! $response->ok()) {
            throw new \Exception('Problem connecting to googlechromelabs.com');
        }
        if (empty($response->object()->channels->Stable->downloads->{'chrome-headless-shell'})) {
            throw new \Exception('Problem parsing response from googlechromelabs.com');
        }

        return $response->object()->channels->Stable->downloads->{'chrome-headless-shell'};
    }

    protected function getPlatformKey(): string
    {

        if (Client::onWindows32()) {
            return 'win32';
        } elseif (Client::onWindows64()) {
            return 'win64';
        } elseif (Client::onLinux()) {
            return 'linux64';
        } elseif (Client::onMacARM()) {
            return 'mac-arm64';
        } elseif (Client::onMacIntel()) {
            return 'mac-x64';
        }

        throw new \Exception('Platform not supported');
    }
}
