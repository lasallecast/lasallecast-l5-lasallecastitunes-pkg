<?php

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

return [

    /*
    |--------------------------------------------------------------------------
    | Do you want to use the Creative Commons NC-ND 4.0 license?
    |--------------------------------------------------------------------------
    |
    | Use Attribution-NonCommercial-NoDerivatives 4.0 International (CC BY-NC-ND 4.0)?
    |
    | 'no'  = nothing happens
    | 'yes' = the following tags are added to your itunes feed, near the top to describe your show:
    |
    | <copyright>
    |   This work is licensed under a Creative Commons License - Attribution-NonCommercial-NoDerivatives 4.0 International
    |   http://creativecommons.org/licenses/by-nc-nd/4.0/
    | </copyright>
    | <creativeCommons:license>http://creativecommons.org/licenses/by-nc-nd/4.0/</creativeCommons:license>
    |
    | http://creativecommons.org/licenses/by-nc-nd/4.0/
    | (exacmple at http://feeds.twit.tv/brickhouse.xml)
    |
    */
    //'use_creative_commons_nc_nd_40_license' => 'no',
    'use_creative_commons_nc_nd_40_license' => 'yes',

];