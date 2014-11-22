<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimelineNodeContent
 *
 * @author Chris
 */
abstract class TimelineNodeContent {
    public $uri;
    public $caption;
    function __construct($uri, $caption) {
        $this->uri = $uri;
        $this->caption = $caption;
    }
    abstract public function draw();
}
