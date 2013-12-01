jQuery(document).ready(function($) {
    var kDebugHeight= jQuery(window).height();
    
    function updateContainer(kDebugHeight){        
        $('#kDebug').css('height',kDebugHeight);
        $('#kDebug').css('bottom',"-"+kDebugHeight+"px");
        $('#kDebug div.kDebugToggle-content').css('height',kDebugHeight);
        $('#kDebug .contentHolder').css('height',kDebugHeight-100);
    }   
    
    updateContainer(kDebugHeight);
    
    $('#kDebug div.kDebugToggle').click(function () {
        if ($(this).hasClass('kDebugToggleActive')) {
            $('#kDebug div.kDebugToggle').css('margin-top','-28px');
            $('#kDebug div.kDebugToggle').removeClass('kDebugToggleActive');
            $("#kDebug").animate({
                "bottom": "-"+kDebugHeight+"px"
            }, 500, function () {
                jQuery.cookie('kDebugToggle', 0, { expires: 60, path: '/' });                 
            });
        } else {
            $('#kDebug div.kDebugToggle').css('margin-top','0px');
            $('#kDebug div.kDebugToggle').addClass('kDebugToggleActive');
            $("#kDebug").animate({
                "bottom": "0"
            }, 500, function () {
                jQuery.cookie('kDebugToggle', 1, { expires: 60, path: '/' });                
            });
        }
    });
    
    var kDebugToggleActive = ($.cookie('kDebugToggle') ? $.cookie('kDebugToggle') : '0');
    if(kDebugToggleActive == 1)
        $('#kDebug div.kDebugToggle').trigger('click');
        
    $("#kDebugAccordion .accord-header").click(function() {
        if($(this).next("div").is(":visible")){
            $(this).next("div").slideUp("slow");
            $( "#kDebug" ).draggable("enable");
        } else {
            $("#kDebugAccordion .accord-content").slideUp("slow");
            $(this).next("div").slideToggle("slow");
            $( "#kDebug" ).draggable("disable");
        }
    });    

    
    $('#kDebug .contentHolder').perfectScrollbar({minScrollbarLength:100});    
});
