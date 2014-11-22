<?php
include_once "TimelineNodeContent";

/**
 * Description of TimelineNodeVideo
 *
 * @author Chris
 */
class TimelineNodeVideo extends TimelineNodeContent{
    const WIDTH = 410;
    const HEIGHT = 315;
    
    public function draw() {
        echo "<embed width=\"" . TimelineNodeVideo::WIDTH . "\" height=\"" . TimelineNodeVideo::HEIGHT . "\" src=\"{$this->uri}\">";
        echo $this->caption;
    }
}
