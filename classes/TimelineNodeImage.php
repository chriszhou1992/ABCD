<?php

include_once "TimelineNodeContent.php";

/**
 * Description of TimelineNodeImage
 *
 * @author Chris
 */
class TimelineNodeImage extends TimelineNodeContent{
    const WIDTH = 410;
    const HEIGHT = 230;
    
    function __construct($uri, $caption) {
        parent::__construct($uri, $caption);
    }
    
    public function draw() {
        echo "<a class=\"image_rollover_bottom con_borderImage\" data-description=\"ZOOM IN\" ";
        echo "href=\"{$this->uri}\" data-lightbox=\"t\" data-title=\"{$this->caption}\">";
        echo "<img width=\"" . TimelineNodeImage::WIDTH . "\" height=\"" . TimelineNodeImage::HEIGHT . "\" src=\"{$this->uri}\" alt=\"\"/>";
        echo "</a>";
    }
}
