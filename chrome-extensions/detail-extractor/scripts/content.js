chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
    console.log("something happening from the extension");
    //var data = request.data || {};

    //var linksList = document.querySelectorAll(data);
    //var name = document.getElementsByClassName("pv-top-card-section__name")[0].innerHTML;
    var name = $.trim($(".pv-top-card-section__name:first").html());
    var title = $.trim($(".pv-top-card-section__headline:first").html());
    var company = $.trim($(".pv-top-card-v2-section__company-name:first").html());


    if (name) {
        company = company.split(/[^A-Za-z ]/);
        var data = { name: name, title: title, company: company[0] };
        sendResponse({ data: data, success: true });
        console.log("name:" + name + ",title:" + title + ",company:" + company[0]);
    }


    /*$.get("https://www.staffingadvisors.com",
       function(data) {
           var res = $(data).find('a:contains("Team")').attr("href");
           console.log(res);
       });
    */

    // var company = $(".pv-top-card-section__name:first").html();

    //document.getElementsByTagName("h1")[0].innerHTML;
    /*[].forEach.call(linksList, function(header) {
        header.innerHTML = request.data;
    }); */

});