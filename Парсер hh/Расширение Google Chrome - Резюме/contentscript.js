chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {

});

window.addEventListener("load",function(e){
	if (document.location.host == 'perm.hh.ru'){

        var name = $('h1').css({'background': '#DDDDDD'}).text();
        var countArr = name.replace(/\s/g, '').split('резюме');
        var countArr2 = countArr[0].replace('Найдено', '');              
        console.log(countArr2);
                
        chrome.runtime.sendMessage({sendmsg: "parseSend", count: countArr2});
        
    }
 });

