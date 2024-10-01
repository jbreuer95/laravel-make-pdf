<?php

namespace Breuer\PDF\Commands;

use Breuer\PDF\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ZipArchive;

use function Breuer\PDF\package_path;

class DownloadCommand extends Command
{
    public $signature = 'headless-pdf:download';

    public $description = 'Download latest stable chrome-headless-shell and chromedriver';

    public function handle(): int
    {
        File::ensureDirectoryExists(package_path('browser'));

        $this->info('Removing old browser installations');
        File::deleteDirectory(package_path('browser/chrome-linux64'));
        File::deleteDirectory(package_path('browser/chromedriver-linux64'));

        $this->info('Fetching latest browser build information');
        $response = Http::get('https://googlechromelabs.github.io/chrome-for-testing/last-known-good-versions-with-downloads.json');

        foreach ($response->object()->channels->Stable->downloads->{'chrome-headless-shell'} as $download) {
            if ($download->platform !== $this->getPlatformKey()) {
                continue;
            }

            dd($download);
            $zipfile = storage_path('browser/chrome-headless-shell.zip');

            $this->info('Downloading chrome version: '.$response->object()->channels->Stable->version);

            Http::sink($zipfile)->get($download->url);

            $this->info('Unzipping');
            $zip = new ZipArchive;
            $zip->open($zipfile);
            $zip->extractTo(storage_path('chrome'));
            $zip->close();

            File::delete($zipfile);
        }

        // foreach ($response->object()->channels->Stable->downloads->chromedriver as $download) {
        //     if ($download->platform !== 'linux64') {
        //         continue;
        //     }
        //     $zipfile = storage_path('chrome/chromedriver-linux64.zip');

        //     $this->info('Downloading chromedriver version: '.$response->object()->channels->Stable->version);

        //     Http::sink($zipfile)->get($download->url);

        //     $this->info('Unzipping');
        //     $zip = new ZipArchive;
        //     $zip->open($zipfile);
        //     $zip->extractTo(storage_path('chrome'));
        //     $zip->close();

        //     File::delete($zipfile);
        // }

        // $this->info('Fixing permissions');
        // chmod(storage_path('chrome/chromedriver-linux64/chromedriver'), 0755);
        // chmod(storage_path('chrome/chrome-linux64/chrome'), 0755);

        // $this->info('Success');

        return self::SUCCESS;
    }

    protected function getPlatformKey()
    {
        if (Client::onWindows()) {
            return PHP_INT_SIZE == 4 ? 'win32' : 'win64';
        } elseif (Client::onLinux()) {
            return 'linux64';
        } elseif (Client::onMac()) {
            return php_uname('m') === 'arm64' ? 'mac-arm64' : 'mac-x64';
        } else {
            throw new \Exception('Platform not supported');
        }
    }
}
