/**
 * Global variable containing the query we'd like to pass to Flickr. In this
 * case, kittens!
 *
 * @type {string}
 */
var QUERY = 'kittens';

var debugGenerator = {
    requestDebug:function(){
        
    }
};


window.addEventListener("load", function() {
  chrome.debugger.onEvent.addListener(onEvent);
});

function onEvent(debuggeeId, message, params) {
    alert(params.request.url);
}

// Run our kitten generation script as soon as the document's DOM is ready.
document.addEventListener('DOMContentLoaded', function () {
    
    alert(window.location.search.substring(1));
});
