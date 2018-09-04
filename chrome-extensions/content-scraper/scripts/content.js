chrome.runtime.onMessage.addListener( function(request, sender, sendResponse) {
    console.log("something happening from the extension");
    var data = request.data || {};

    var linksList = document.querySelectorAll(data);
	var h1text = document.getElementsByTagName(data)[0].innerHTML;
    /*[].forEach.call(linksList, function(header) {
        header.innerHTML = request.data;
    }); */
    sendResponse({data: h1text, success: true});
});

