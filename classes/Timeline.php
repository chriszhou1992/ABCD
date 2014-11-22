<?php

/**
You can modify timeline by using optional parameters argument:
$('#myTimeline').timeline({
  name : value
});

List of available parameters:

Name	Default	Description
itemClass	'.item'	Class used for items
itemOpenClass	'.item_open'	Class used for item details
openTriggerClass	'.item'	Class of read more element (default uses whole '.item' to trigger open event), if you change it have in mind that element you use it on should have 'data-id' with value same as parrent '.item'.
closeText	'Close'	Text of 'close' button in open item (can be left blank)
itemMargin	10	Spacing between items
scrollSpeed	500	Transition speed between two elements.
startItem	'last'	Timeline start item 'data-id' ('last' or 'first' can be used insted).
easing	'easeOutSine'	JQuery.easing function for animations
hideTimeline	false	Remove timeline
hideControles	false	Remove left/right controles
swipeOn	true	Turn on swipe moving function
closeItemOnTransition	false	Doesn't open item after transition (if true)
ajaxFailMessage	'Ajax request has failed.'	Message shown when content of opened item fails to load
Timeline categorising parameters:

Name	Default
categories	['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
nuberOfSegments	[31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
yearsOn	true
'categories' are used for reorganizing timeline to your needs, it doesn't have to be "date timeline" anymore. You can set your personal array which will be used to generate timeline. If 'categories' are set to false, timeline will be categorized only by one number. 'nuberOfSegments' represents number of days/items that can be placed in each category (if no categories set as number, not array). If you don't need years you can turn them off by setting 'yearsOn' to false. Years can be used as special numbering to your categories. 

data-ids should be formated like this "itemNumber/category/yearOrSomeOtherNumber" or "itemNumber/category". If 'categories' parameter is false, data-ids should look like "itemNumber"
*/

include_once 'TimelineNode.php';
include_once 'connecToDB.php';
    
/**
 * Description of Timeline
 *
 * @author Chris
 */
class Timeline {
    public $nodes = [];
    protected $con;
    
    public function __construct() {
        $con = connectToDB();
        if($con === false):
            return;
        endif;
        $this->con = $con;
    }
}
