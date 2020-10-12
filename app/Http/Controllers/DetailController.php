<?php

namespace App\Http\Controllers;

use Dotenv\Result\Result;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Sunra\PhpSimple\HtmlDomParser;


class DetailController extends Controller
{
    public function getdata(Request $request)
    {
       $url= $request->link;
       error_log($url);

        $item_name_chapitres=[];
        $item_link_chapitres=[];
        $item_view_chapitres=[];
        $item_time_chapitres=[];



       // $url = "https://manganelo.com/manga/xm922999";
        $client = new  Client();
        $response = $client->request(
            'GET',
            $url
        );
        $response_status_code = $response->getStatusCode();
        $html = $response->getBody()->getContents();
        if ($response_status_code == 200) {
            $dom = HtmlDomParser::str_get_html($html);
            $items=$dom->find('div[class="panel-story-info"]');
            foreach ($items as $item) {
                $item_img=$item->find('img[class="img-loading"]', 0)->src;
                $item_title=$item->find('h1',0)->text();
                $item_properties=[];
                foreach($item->find('td[class="table-value"]') as $element)
                array_push($item_properties,$element->text());
            }
            $item_description=$dom->find('div[class="panel-story-info-description"]',0)->text();

            $items=$dom->find('div[class="panel-story-chapter-list"]');
            foreach($items as $item){
                foreach($item->find('a[class="chapter-name"]') as $element){
                    array_push($item_name_chapitres, $element->text());
                }
                foreach($item->find('a[class="chapter-name"]') as $element){
                    array_push($item_link_chapitres, $element->attr['href']);
                }
                foreach($item->find('span[class="chapter-view"]') as $element){
                    array_push($item_view_chapitres, $element->text());
                }
                foreach($item->find('span[class="chapter-time"]') as $element){
                    array_push($item_time_chapitres, $element->text());
                }

            }
            $result=[
                "title"=>$item_title,
                "img"=>$item_img,
                "properties"=>$item_properties,
                "author"=> $item_properties[1],
                "status"=> $item_properties[2],
                "genre"=> $item_properties[3],
                "description"=>$item_description,
                "chapitres"=>$item_name_chapitres,
                "link_chapitres"=>$item_link_chapitres,
                "views_chapitres"=>$item_view_chapitres,
                "time_chapitres"=>$item_time_chapitres,
                "request"=>$request
            ];



           // $item_side= $dom->find('span[class="info-image"]');


        }
        // print_r($item_img);
        // print_r($item_title);
        // print_r($item_properties);
        // echo"<br>";
        // print_r($item_description);
        // echo"<br>";
        // print_r($item_name_chapitres);
        return $result;
    }
}
