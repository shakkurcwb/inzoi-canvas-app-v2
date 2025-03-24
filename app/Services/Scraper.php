<?php

namespace App\Services;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;

class Scraper
{
    protected bool $debug;

    protected ChromeDriver $driver;
    protected ChromeDriverService $service;
    protected DesiredCapabilities $capabilities;

    const CHROME_DRIVER_PATH = "C:\Program Files\chromedriver\chromedriver.exe";

    public function __construct(bool $headless = true, $debug = false)
    {
        $this->debug = $debug;

        $options = new ChromeOptions();
        $options->addArguments($headless ? ['--headless'] : []);

        $this->capabilities = DesiredCapabilities::chrome();
        $this->capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->service = new ChromeDriverService(self::CHROME_DRIVER_PATH, 9515);

        $this->driver = ChromeDriver::start($this->capabilities, $this->service);
    }

    public function scrape(string $url): string
    {
        try {
            $this->driver->get($url);

            // wait for javascript to load
            sleep(2);

            $html = $this->driver->getPageSource();

            if (false) {
                // save on files (debugging purposes)
                $filepath = storage_path('app/public/scraped.html');
                file_put_contents($filepath, $html);
            }

            return $html;
        } finally {
            if ($this->driver && !$this->debug) {
                $this->driver->quit();
            }
        }
    }
}
