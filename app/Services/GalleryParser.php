<?php

namespace App\Services;

class GalleryParser
{
    public function parse(string $html): array
    {
        $dom = new \DOMDocument();

        @$dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);

        $images = $this->getImages($xpath);

        $data = $this->getInfo($xpath);

        return array_merge($data, ['images' => $images]);
    }

    protected function getImages(\DOMXPath $xpath): array
    {
        $data = [];

        $images = $xpath->query('//img[@class="relative z-10 opacity-0 shadow-black/5 data-[loaded=true]:opacity-100 shadow-none transition-transform-opacity motion-reduce:transition-none !duration-300 h-auto w-full max-w-[786px] rounded-[8px] object-cover"]');

        /** @var \DOMElement $element */
        foreach ($images as $element) {
            $data[] = $element->getAttribute('src');
        }

        return $data;
    }

    protected function getInfo(\DOMXPath $xpath): array
    {
        $data = [];

        $sections = $xpath->query('//div[@class="w-[500px]"]/div');

        $stringToDate = function (string $creation) {
            $creation = str_replace(' ago', '', $creation);

            list($value, $unit) = explode(' ', $creation);

            if ($unit === 'd') {
                $unit = 'days';
            }

            return date('Y-m-d', strtotime("-$value $unit"));
        };

        $extractUserId = function ($avatar_url) {
            $url = $avatar_url;

            // remove http or https
            $url = str_replace('http://', '', $url);
            $url = str_replace('https://', '', $url);

            // find start of user ID
            $start = strpos($url, 'acc-');

            // find emd of user ID
            $end = strpos($url, '/', $start);

            // extract user ID
            return substr($url, $start, $end - $start);
        };

        /** @var \DOMElement $section */
        foreach ($sections as $idx => $section) {
            // get creation date
            if ($idx === 0) {
                $creation_str = trim($section->textContent);

                $creation_date = $stringToDate($creation_str);
    
                $data['creation_date'] = $creation_date;
            }

            // get picture name and description
            if ($idx === 1) {
                $paragraphs = $section->getElementsByTagName('p');

                $title = trim($paragraphs->item(0)->nodeValue);
                $description = str_replace('  ', '', trim($paragraphs->item(1)->nodeValue));

                $data['title'] = $title;
                $data['description'] = $description;
            }

            // get downloads + likes counter
            if ($idx === 2) {
                $paragraphs = $section->getElementsByTagName('p');

                $downloads = (int) $paragraphs->item(1)->textContent;
                $likes = (int) $paragraphs->item(2)->textContent;

                $data['downloads'] = $downloads;
                $data['likes'] = $likes;
            }

            // get creator name
            if ($idx === 4) {
                $avatar = $section->getElementsByTagName('img')->item(0);

                $avatar_url = $avatar->getAttribute('src');

                $author_id = $extractUserId($avatar_url);

                $profile_url = "https://canvas.playinzoi.com/en-US/profile/$author_id";

                $name = trim($section->getElementsByTagName('p')->item(1)->textContent);

                $data['creator_id'] = $author_id;
                $data['creator_name'] = $name;                
                $data['creator_avatar_url'] = $avatar_url;
                $data['creator_profile_url'] = $profile_url;
            }
        }

        return $data;
    }
}