<?php

namespace Lasallecast\Lasallecastitunes;

/**
 *
 * iTunes feed package for the LaSalleCast e-broadcasting platform
 * Copyright (C) 2015 - 2016  The South LaSalle Trading Corporation
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package    iTunes feed package for the LaSalleCast e-broadcasting platform
 * @link       http://LaSalleCast.com
 * @copyright  (c) 2015 - 2016, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */



// http://feedvalidator.org/


// LaSalle Software classes
use Lasallecast\Lasallecastitunes\Categories;

// Laravel Facades
use Illuminate\Support\Facades\DB;;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class Lasallecastitunes
{
    /**
     * @var Lasallecast\Lasallecastitunes\Categories
     */
    protected $categories;


    /**
     *
     */
    public function __construct(Categories $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns new instance of Lasallecastitunes class
     *
     * @return Lasallecastitunes
     */
    public function make()
    {
        return new Lasallecastitunes();
    }


    /**
     * Creates the itunes feed
     *
     * @param  collection   $show      The show
     * @param  collection   $episodes  The episodes to include in the feed
     * @return view
     */
    public function render($show, $episodes)
    {
        // for show, get the TITLE of the explicit lookup table's ID
        $show->itunes_explicit_title = $this->getLookupitunesexplicitTitleById($show->itunes_explicit_id);

        // for episodes: instead of the ID, display the TITLE of the explicit lookup table
        $this->assignLookupitunesexplicitTitle($episodes);

        // for show, the date the the itunes feed was created (ie, built)
        // format: Mon, 04 May 2015 19:03:17 +0000
        $lastBuildDate = $this->formatDate(date('D, d M Y H:i:s O'));

        // for show, the feed generator
        $version = \Lasallecast\Lasallecastitunes\Version::VERSION;
        $feedGenerator = "LaSalleCast iTunes feed generation pacakge ".$version;


        // for episodes, the formatted publish_date
        // format: Mon, 04 May 2015 19:03:17 +0000
        $this->assignFormattedPublishDate($episodes);


        // Note that itunes category tags are created in a separate class, via a blade injectable service


        // If we just return the view as is, the browser may just render the rss tags as pure html. Meaning,
        // the browser will intrepret the rss tags, as if they were regular html tags. What we want is to
        // know that the browser will treat the rss tags as, well... as rss tags. So, first we get the contents
        // of the view. Then, we return a response with the specific rss header

        // Thanks to http://blog.kongnir.com/2014/06/27/getting-laravel-to-return-a-view-as-rss-or-xml/

        $content =  view('lasallecastitunes::itunes', [
            'show'                       => $show,
            'episodes'                   => $episodes,
            'lastBuildDate'              => $lastBuildDate,
            'feedGenerator'              => $feedGenerator,
        ]);

        return Response::make($content, '200')->header('Content-Type', 'text/xml');
    }





    /**
     * Assign the itunes explicit name to each episode
     *
     * @param  collection   $episodes  The episodes to include in the feed
     * @return collection
     */
    public function assignLookupitunesexplicitTitle($episodes)
    {
        foreach ($episodes as $episode)
        {
            $episode->itunes_explicit_title = $this->getLookupitunesexplicitTitleById($episode->itunes_explicit_id);
        }

        return $episodes;
    }

    /**
     * What is the title of a lookup_itunes_explicit id
     *
     * @param  int  $lookup_itunes_explicit_id      The lookup table's id
     * @return string
     */
    public function getLookupitunesexplicitTitleById($lookup_itunes_explicit_id)
    {
        $explicit = DB::table('lookup_itunes_explicit')
            ->where('id', '=', $lookup_itunes_explicit_id)
            ->first()
        ;

        return $explicit->title;
    }


    /**
     * Assign the new publish_date_formatted property to each episode
     *
     * @param  collection   $episodes  The episodes to include in the feed
     * @return collection
     */
    public function assignFormattedPublishDate($episodes)
    {
        foreach ($episodes as $episode)
        {
            $episode->publish_date_formatted = $this->formatDate($episode->publish_date);
        }

        return $episodes;
    }


    /**
     * Give me a date, and I'll give you the date format that iTunes wants in its feed
     *
     * @param  date
     * @return string
     */
    public function formatDate($date)
    {
        return $date = date('D, d M Y H:i:s O', strtotime($date));
    }



}