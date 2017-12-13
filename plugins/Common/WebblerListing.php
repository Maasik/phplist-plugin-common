<?php

namespace phpList\plugin\Common;

/**
 * CommonPlugin for phplist
 * 
 * This file is a part of CommonPlugin.
 *
 * @category  phplist
 * @package   CommonPlugin
 * @author    Duncan Cameron
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This class overrides some methods of the WebblerListing class
 * 
 */
class WebblerListing extends \WebblerListing
{
    /*
     * Constructor.
     * Work-around for Trevelyn theme to stop links being displayed as buttons
     */
    public function __construct($title = '', $help = '')
    {
        global $pagefooter;

        parent::__construct($title, $help);
        $pagefooter[basename(__FILE__)] = <<<'END'
<script>
$(document).ready(function(){
    $('a.nobutton').removeClass('btn btn-xs btn-primary');
});
</script>
END;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Extend parent method to convert url to html entities.
     */
    public function addElement($element, $url = '', $colsize = '')
    {
        parent::addElement($element, htmlspecialchars($url), $colsize);
        parent::setClass($element, 'row1');
    }

    /**
     * Extend parent method.
     * Construct the link here in order to be able to specify attributes and fallback to 'nobutton' class.
     */
    public function addColumn($name, $column_name, $value, $url = '', $align = '', array $attributes = [])
    {
        $columnValue = htmlspecialchars($value, ENT_QUOTES);

        if ($url) {
            if (!isset($attributes['class'])) {
                $attributes['class'] = 'nobutton';
            }
            $columnValue = new PageLink($url, $columnValue, $attributes);
        }
        parent::addColumn($name, $column_name, $columnValue, '', $align);
    }

    /**
     * Extend parent method to convert value and url to html entities.
     */
    public function addRow($name, $row_name, $value, $url = '', $align = '', $class = '')
    {
        parent::addRow($name, $row_name, nl2br(htmlspecialchars($value, ENT_QUOTES)), htmlspecialchars($url), $align, $class);
    }

    /**
     * Convenience method to shorten an email address when used as the value.
     */
    public function addColumnEmail($name, $column_name, $value, $url = '', $align = '')
    {
        $maxLength = 29;

        if (strlen($value) > $maxLength) {
            $middle = '…';
            $outerLength = (int)(($maxLength - strlen($middle)) / 2);
            $shortValue = sprintf(
                '<span title="%s">%s</span>',
                $value,
                htmlspecialchars(substr($value, 0, $outerLength)) . $middle . htmlspecialchars(substr($value, -$outerLength))
            );
        } else {
            $shortValue = htmlspecialchars($value);
        }
        $this->addColumn($name, $column_name, $shortValue, $url, $align);
    }

    /**
     * Convenience method when the value is already valid html.
     */
    public function addColumnHtml($name, $column_name, $value, $url = '', $align = '')
    {
        parent::addColumn($name, $column_name, $value, htmlspecialchars($url), $align);
    }

    /**
     * Convenience method when the value is already valid html.
     */
    public function addRowHtml($name, $row_name, $value, $url = '', $align = '', $class = '')
    {
        parent::addRow($name, $row_name, $value, htmlspecialchars($url), $align, $class = '');
    }
}
