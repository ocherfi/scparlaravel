<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;
use App\custumClass\Manga;
use Dotenv\Result\Result;
use Facade\FlareClient\Http\Response;

class MainController extends Controller

{

    public function scrap()
    {
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
                $item_title = $item->find('h3', 0)->find('a[class="tooltip"]', 0);
                $item_detail = $item_title->attr['href'];
                $item_title = $item_title->text();

                $item_chap = $item->find('span', 0);
                foreach ($item->find('span') as $element) {
                    array_push($item_chaps, $element->text());
                    // print_r($item_chaps);
                    // echo"<br>";

                }
                foreach ($item->find('i') as $element)
                    array_push($item_publish, $element);
                $item_img = $item->find('img', 0)->src;

                $result = [
                    'title'   => $item_title,
                    'messages'  => $item_chaps,
                    'img'      => $item_img
                ];
                $results=[];
                array_push($results, $result);



                //     echo $item_title."------>".$item_chaps . '<br>';
                //     echo"------------------<BR>";
                //echo $item_title."---->".$item_chaps[0]."--->".$item_publish[0]."------>".$item_img."--->".$item_detail.'<br>';

                // $manga->title = $item_title;
                // $manga->chapitre = $item_chaps;
                // $manga->img = $item_img;


            }
        }
      return $result;
    }
}
