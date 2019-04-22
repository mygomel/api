<?php
/**
 * Created by Maus 12.03.2019 7:17 mygomel@gmail.com
 */


namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


abstract class Controller
{
    protected $twig, $name;

    public function __construct($parts)
    {
        $action = $parts[2];

        $loader = new FilesystemLoader('templates');
        $this->twig = new Environment($loader);

        $array = explode('\\', get_class($this));
        $this->name = strtolower($array[count($array) - 1]);

        $action ? $this->$action($parts[3]) : $this->index();
    }

    public function index()
    {
        echo $this->twig->render('index.html.twig', ['name' => $this->name]);
    }

    public function news()
    {
        $json = file_get_contents(static::URL.'driver_newsletters/?format=json&limit=10');
        $news = json_decode($json, true);
        $count_news = $news['meta']['total_count'];

        echo $this->twig->render('news.html.twig', ['name' => $this->name, 'news' => $news['objects'], 'count_news' => $count_news]);
    }

    public function works()
    {
        $json = file_get_contents(static::URL.'drivers/location/?format=json');
        $driver_working = json_decode($json, true);

        $taxi_count=0; $pause=0;
        foreach ($driver_working['drivers'] as $key => $val) {
            $drivers[$val['call_sign']] = $val['free'];
            if ($val['call_sign']<700) $taxi_count++;
            if ($val['free']=="") $pause++;
        }
        ksort($drivers);


        echo $this->twig->render('works.html.twig', [
            'name' => $this->name,
            'taxi_count' => $taxi_count,
            'all_count' => count($drivers),
            'pause' => $pause,
            'drivers' => $drivers
        ]);
    }

    public function driver()
    {
        $driver =(int) $_GET['driver'];
            $json = @file_get_contents(static::URL.'drivers/'.$driver.'/info/?format=json');
            $driver_info = json_decode($json, true);

            echo $this->twig->render('driver.html.twig', ['name' => $this->name, 'driver' => $driver, 'driver_info' => $driver_info]);

    }

    public function order($driver)
    {
        $json = file_get_contents(static::URL.'drivers/'.$driver.'/archive/?format=json&limit=1');
        $order = json_decode($json, true);

        echo $this->twig->render('order.html.twig', ['order' => $order['objects'][0]]);
    }

    public function history($driver)
    {
        $part =(int) $_GET['part'];
        $json = file_get_contents(static::URL.'drivers/'.$driver.'/archive/?offset='.$part.'&limit=20&format=json'); // ?offset=20&limit=20&format=json
        $driver_history = json_decode($json, true);

        echo $this->twig->render('history.html.twig', ['driver' => $driver, 'part' => $part, 'driver_history' => $driver_history['objects']]);
    }

}