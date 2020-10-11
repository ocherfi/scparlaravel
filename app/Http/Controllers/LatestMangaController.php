<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;
use GuzzleHttp\Client;


class LatestMangaController extends Controller
{
    public function getdata()
    {
        $results = [];

        $url = "https://manganelo.com/";
        $client = new Client();
        $response = $client->request(
            'GET',
            $url
        );
        $response_status_code = $response->getStatusCode();
        $html = $response->getBody()->getContents();
        if ($response_status_code == 200) {
            $dom = HtmlDomParser::str_get_html($html);
            $items = $dom->find('div[class="content-homepage-item"]');
            foreach ($items as $item) {

                $item_name_chapitres = [];
                $item_publish = [];
                $item_link_chapitres = [];
                $item_title = $item->find('h3[class="item-title"]', 0)->text();
                $item_detail =$item->find('a[class="tooltip"]',0)->attr['href'];
                $item_chapitres=$item->find('p[class="item-chapter"]');
                foreach($item_chapitres as $item_chapitre){
                    $item_name_chapitre=$item_chapitre->find('a',0)->text();
                    array_push($item_name_chapitres,$item_name_chapitre);
                    $item_link_chapitre=$item_chapitre->find('a',0)->attr['href'];
                    array_push($item_link_chapitres, $item_link_chapitre);

                }

                foreach ($item->find('i') as $element)
                    array_push($item_publish, $element->text());
                //  echo $item_publish;
                $item_img = $item->find('img', 0)->src;

                $result = [
                    'title'   => $item_title,
                    'link' => $item_detail,
                    'chapitres'  => $item_name_chapitres,
                    'link_chapitres' => $item_link_chapitres,
                    'posted' => $item_publish,
                    'img'      => $item_img
                ];
                array_push($results, $result);





            }
        }
        return $results;
    }
}
