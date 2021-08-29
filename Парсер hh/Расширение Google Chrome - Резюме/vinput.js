// настройки

var SENDPORT = chrome.runtime.connect();
var bg = chrome.extension.getBackgroundPage();

$('body').on('click', '#start', function(){
    bg.sendMessage("start", null);
});

SENDPORT.postMessage({sendmsg: "testtest"});
