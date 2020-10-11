<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;
use App\custumClass\Manga;

class MainController extends Controller

{

    public function scrap()
    {
        $results = [];

        $manga = new Manga();
        $url = "https://mangakakalot.com/";
        $client = new Client();
        $response = $client->request(
            'GET',
            $url
        );
        $response_status_code = $response->getStatusCode();
        $html = $response->getBody()->getContents();
        if ($response_status_code == 200) {
            $dom = HtmlDomParser::str_get_html($html);
            $items = $dom->find('div[class="first"]');
            foreach ($items as $item) {

                $item_chaps = [];
                $item_publish = [];
                $item_link_chaps = [];
                $item_title = $item->find('h3', 0)->find('a[class="tooltip"]', 0);
                $item_detail = $item_title->attr['href'];
                $item_title = $item_title->text();

                $item_chap = $item->find('span', 0);
                foreach ($item->find('span') as $element)
                    array_push($item_chaps, $element->text());
                foreach ($item->find('a[class="sts_1"]') as $element)
                    array_push($item_link_chaps, $element->attr['href']);



                foreach ($item->find('i') as $element)
                    array_push($item_publish, $element->text());
                //  echo $item_publish;
                $item_img = $item->find('img', 0)->src;

                $result = [
                    'title'   => $item_title,
                    'link' => $item_detail,
                    'chapitres'  => $item_chaps,
                    'link_chapitres' => $item_link_chaps,
                    'posted' => $item_publish,
                    'img'      => $item_img
                ];
                array_push($results, $result);



                //     echo $item_title."------>".$item_chaps . '<br>';
                //     echo"------------------<BR>";
                //echo $item_title."---->".$item_chaps[0]."--->".$item_publish[0]."------>".$item_img."--->".$item_detail.'<br>';

                $manga->title = '';
                // $manga->chapitre = $item_chaps;
                // $manga->img = $item_img;
                // array_push($manga->title,$item_title);
                // array_push($manga->chapitre,$item_chaps);
                // array_push( $manga->img,$item_img);




            }
        }
        return $results;
    }
}
