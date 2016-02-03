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


/* ==================================================================
   iTunes category tags has its twists and turns, so I am creating a
   separate class to handle them.

   If the category is a top-level category only, the tag looks like:
         <itunes:category text="News & Politics"/>

   If the category is a sub-category, then the tag looks like:
     <itunes:category text="Technology">
        <itunes:category text="Gadgets"/>
     </itunes:category>

   Sub-categories are in the lookup table as "Technology > Gadgets".

   I am extracting the category tag handling here, out of the
   Lasallecastitunes class, to keep that class lean; and, to make it
   easier (or, perhaps, just more obvious) to use blade's service injection.
   ================================================================== */


// Laravel Facades
use Illuminate\Support\Facades\DB;;
use Illuminate\Support\Facades\Config;

class Categories
{

    public function testing()
    {
        return '<itunes:category text="News & Politics"/>';
    }




    /**
     * Give me the show, and I'll give you its itunes category TITLEs
     *
     * @param  int     $show_id    The show's ID
     * @return array
     */
    public function getItunescategoryTitlesByShowId($show_id)
    {
        // get the itunes category id's from the shows_itunes_category pivot table
        $itunes_category_ids = $this->getCategoryIdsByShowId($show_id);

        // initialize
        $html = "";

        foreach ($itunes_category_ids as $itunes_category_id)
        {
            // get the itunes category name (ie, title)
            $title = $this->getCategoryTitleById($itunes_category_id->lookup_itunes_category_id);

            // evaluate if this is a subcategory
            $isSubcategory = $this->isSubcategory($title->title);

            // prepare the tag(s)
            // Apple wants the html special
            if (!$isSubcategory) {

                // top-level category
                $html .= '<itunes:category text="'.htmlspecialchars($title->title).'"/>';

            } else {

                // sub-category

                // create the top-level category tag
                $html .= '<itunes:category text="'.htmlspecialchars($this->getTopLevelCategoryByTitle($title->title)).'">';

                // create the sub-category tags
                $html .= '<itunes:category text="'.htmlspecialchars($this->getSubcategoryByTitle($title->title)).'"/>';
                $html .= '</itunes:category>';
            }
        }

        return $html;
    }


    /**
     * Get the itunes category id's from the shows_itunes_category pivot table
     *
     * @param  int          $show_id    The show's ID
     * @return collection
     */
    public function getCategoryIdsByShowId($show_id)
    {
        return DB::table('show_itunes_category')
            ->where('show_id', '=', $show_id)
            ->get()
        ;
    }

    /**
     * Get the itunes category title (ie, name) by ID from the lookup table
     *
     * @param  int     $lookup_itunes_category_id          The lookup table's ID
     * @return string
     */
    public function getCategoryTitleById($lookup_itunes_category_id)
    {
        return DB::table('lookup_itunes_category')
            ->select('title')
            ->where('id', '=', $lookup_itunes_category_id)
            ->first()
        ;
    }


    /**
     * Is this category a sub-category?
     *
     * A example of a sub-category name is "Technology > Gadgets". "Technology" is the top-level category; and
     * "Gadgets" is the sub-category. There are no sub-sub-categories.
     *
     * The ">" acts as the separator.
     *
     * @param  string   $title      The itunes category name (ie, title)
     * @return bool
     */
    public function isSubcategory($title)
    {
        if (strpos($title, ">")) {
            return true;
        }
        return false;
    }


    /**
     * Get the top-level category when the TITLE is a sub-category
     *
     * @param  string   $title              The itunes sub-category name
     * @return string
     */
    public function getTopLevelCategoryByTitle($title)
    {
        $position = strpos($title, ">");

        // the separator is assumed to be " > "
        // trim, just to be sure
        return trim(substr($title, 0, $position - 1));
    }

    /**
     * Get the sub-category when the TITLE is a sub-category
     *
     * @param  string   $title              The itunes sub-category name
     * @return string
     */
    public function getSubcategoryByTitle($title)
    {
        $position = strpos($title, ">");

        // the separator is assumed to be " > "
        // trim, just to be sure
        return trim(substr($title, $position + 1, strlen($title)));
    }
}