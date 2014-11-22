$("#companyField").on("click", "#showTimeline", function () {
    //alert("event triggered!");
    $("#showTimeline").hide();
    
    $('#timeline').timeline({
        openTriggerClass: '.read_more',
        //startItem: '01/08/2000',
        closeText: 'x',
        //ajaxFailMessage: 'This ajax fail is made on purpose. You can add your own message here, just remember to escape single quotes if you\'re using them.'
        ajaxFailMessage: "AJAX Failed."
        //yearsOn: false,
        //categories: []
    });
    $('#timeline').on('ajaxLoaded.timeline', function (e) {
    //$('#timeline').find('.item_open').each( function (index, e) {
        console.log("%o", e);
        console.log("Scroll");
        var height = e.element.height() - 60 - e.element.find('h2').height();
        e.element.find('.timeline_open_content span').css('max-height', height).mCustomScrollbar({
            autoHideScrollbar: true,
            theme: "light-thin"
        });
    
    });

});
