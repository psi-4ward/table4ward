<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  4ward.media 2009
 * @author     Christoph Wiechert <christoph.wiechert@4wardmedia.de>
 * @package    table4ward
 * @license    LGPL 
 * @filesource
 */



/**
 * Table tl_content
 */
// Palette
 // $GLOBALS['TL_DCA']['tl_content']['palettes']['table'] = '{type_legend},type,headline;{table_legend},tableitems;{tconfig_legend},summary,thead,tfoot;{sortable_legend:hide},sortable;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

// Fields
$GLOBALS['TL_DCA']['tl_content']['fields']['tableitems']['inputType'] = 'tableWizard4ward';



?>