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
    //console.log("%o", $('#timeline').find('.item_open_content'));
    //$('#timeline').find('.item_open_content').each( function (index, e) {
        console.log("Scroll");
        //var element = $(this);
        var element = e.element;
        console.log("%o", element);
        var height = element.height() - 60 - element.find('h2').height();
        console.log(height);
        element.find('.timeline_open_content span').css('max-height', height).mCustomScrollbar({
            autoHideScrollbar: true,
            theme: "light-thin"
        });
    
    });

});
