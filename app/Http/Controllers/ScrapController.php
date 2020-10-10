<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use manga;


class ScrapController extends Controller
{
    private $titles=[];
    public function getData(){
        $client = new Client();


        $crawler = $client->request('GET', 'https://mangakakalot.com/');
        // echo`<pre>`;
        // print_r($crawler);
        $items=$crawler->filter('.first')->text();
       $crawler->filter('.first')->each(function ($title) {
            array_push($this->titles, $title);
        });

return $this->titles;
        // $crawler->filter('li > h3')->each(function ($title) {
        //     print $title->text()."<br>";
        // });

    }
}
