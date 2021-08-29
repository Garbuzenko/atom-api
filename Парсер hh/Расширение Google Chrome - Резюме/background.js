// настройки

var defaultsearchengine = null;
var ifrmmode = "";
var keyconfig = {};
var tabaobject = {};
var spkwindowid = null;
var targettabwid = null;
var targettabid = null;

function sendMessage(msg, txt){

    if (msg == 'start'){

        const request = new XMLHttpRequest();
        const url = "https://c01.dvrp.ru/parserhh.php?m=getUrl";
        request.open('GET', url);
        request.setRequestHeader('Content-Type', 'application/x-www-form-url');
        request.addEventListener("readystatechange", () => {
        	if (request.readyState === 4 && request.status === 200){
        	    if (request.responseText != 'NULL'){
        	       chrome.tabs.update(targettabid,{url: request.responseText});
        	    }
            }
        });
        request.send();
    }
}


function createWindow(){
    chrome.windows.getCurrent({populate:false}, function(cwind){
        targettabwid = cwind.id;
        chrome.windows.create({
            type:"popup",
            url:chrome.extension.getURL("vinput.html"),
            width:600,
            top:cwind.top+cwind.height-150,
            left:cwind.width-620,
            height:150,
            focused:true
        },function(wnd){
            var tab = wnd.tabs[0];
            spkwindowid = wnd.id;
            chrome.tabs.query({active:true,windowId:cwind.id},function(tabs){
                if (targettabid === null) {
                    targettabid = tabs[0].id;
                }
            });
        });
    });
}

chrome.browserAction.onClicked.addListener(function(e){
    if(!spkwindowid){
        createWindow();
    }else{
        chrome.windows.update(spkwindowid,{focused:true});
    }
});

chrome.windows.onRemoved.addListener(function (windowId){
    if(spkwindowid === windowId){
        spkwindowid = null;
        targettabid = null;
        targettabwid = null;
    }
    chrome.windows.getAll({populate:false},function(windows){
        if(windows.length === 1){
            if(windows[0].id === spkwindowid)chrome.windows.remove(spkwindowid);
        }
    });
});

chrome.runtime.onMessage.addListener(function(msg, sender, sendResponse){

    if (msg.sendmsg == 'parseSend'){

        var getString = 'col='+msg.count;

        const request = new XMLHttpRequest();
        const url = "https://c01.dvrp.ru/parserhh.php?m=parseTake&"+getString;
        request.open('GET', url);
        request.setRequestHeader('Content-Type', 'application/x-www-form-url');
        request.addEventListener("readystatechange", () => {
        	if (request.readyState === 4 && request.status === 200){
        	    sendMessage("start", null);
            }
        });
        request.send();

    }

});
