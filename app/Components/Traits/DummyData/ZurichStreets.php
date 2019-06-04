<?php

namespace App\Components\Traits\DummyData;

trait ZurichStreets
{
    /**
     * List of Zurich street addresses
     *
     * @return void
     */
    public static function getStreets ()
    {
        return collect([
            'Brandschenkestrasse 64 8002 Zürich Switzerland, 8048', '3 15, 8003 Zürich, Switzerland', 'Zweierstrasse 129, 8003 Zürich, Switzerland', 'Kalkbreitestrasse 84, 8003 Zürich, Switzerland',
            'Bertastrasse 56, 8003 Zürich, Switzerland', 'Küngenmatt 20-26, 8055 Zürich, Switzerland', 'Paul-Clairmont-Strasse 8-16, 8055 Zürich, Switzerland', 'In den Rütenen 8, 8055 Zürich, Switzerland',
            'Unnamed Road, 8063 Zürich, Switzerland', 'Schweigmatt 54, 8055 Zürich, Switzerland', 'Schweigmatt 54, 8055 Zürich, Switzerland', 'Wasserschöpfi 2-8, 8055 Zürich, Switzerland',
            'Bühlstrasse, 8055 Zürich, Switzerland', 'Meiliweg, 8055 Zürich, Switzerland', 'Zweierstrasse 177-165, 8003 Zürich, Switzerland'
        ]);
    }

    /**
     * List of restaurants addresses
     *
     * @return void
     */
    public static function getRestaurantAddress ()
    {
        return collect([
            'Hallwylstrasse 43, 8004 Zürich, Switzerland', 'Bahnhofstrasse 28A, 8001 Zürich, Switzerland', 'Rössligasse 3, 8001 Zürich, Switzerland',
            'Manessestrasse 208, 8045 Zürich, Switzerland', 'Gertrudstrasse 26, 8003 Zürich, Switzerland', 'Neumühlequai 42, 8006 Zürich, Switzerland', 'Römergasse 7-9, 8001 Zürich, Switzerland',
            'Rotwandstrasse 38, 8004 Zürich, Switzerland', 'Brauerstrasse 15, 8004 Zürich, Switzerland', 'Schöneggpl. 9, 8004 Zürich, Switzerland', 'Zollstrasse 80, 8005 Zürich, Switzerland',
            'Sihlfeldstrasse 49, 8003 Zürich, Switzerland', 'Geroldstrasse 5, 8005 Zürich, Switzerland', 'Schiffbaustrasse 4, 8005 Zürich, Switzerland', 'Hardturmstrasse 126A, 8005 Zürich, Switzerland'
        ]);
    }

    /**
     * List of dropoff addresses
     *
     * @return void
     */
    public static function getDropoffAddresses ()
    {
        return collect([
            'Badenerstrasse 141-125, 8004 Zürich, Switzerland', 'Lagerstrasse 11, 8004 Zürich, Switzerland', 'Stampfenbachstrasse 19-15, 8001 Zürich, Switzerland', 'Toblerstrasse 6-16, 8044 Zürich, Switzerland',
            'Susenbergstrasse 149-133, 8044 Zürich, Switzerland', 'Restelbergstrasse 109, 8044 Zürich, Switzerland', 'Möhrlistrasse 20-28, 8006 Zürich, Switzerland', 'Scheuchzerstrasse 107-87, 8006 Zürich, Switzerland',
            'Seminarstrasse 32-34, 8057 Zürich, Switzerland', 'Geibelstrasse 49-23, 8037 Zürich, Switzerland', 'Wunderlistrasse 51, 8037 Zürich, Switzerland', 'Am Börtli 6, 8049 Zürich, Switzerland',
            'Gsteigstrasse 2, 8049 Zürich, Switzerland', 'Lachenzelgstrasse, 8049 Zürich, Switzerland', 'Regensdorferstrasse 149-113, 8049 Zürich, Switzerland'
        ]);
    }
}
