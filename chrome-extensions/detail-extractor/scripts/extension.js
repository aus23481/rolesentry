document.addEventListener('DOMContentLoaded', function() {
    //document.getElementById('status').textContent = "Extension loaded";
    var button = document.getElementById('changelinks');
    button.addEventListener('click', function() {
        //$('#status').html('Clicked change links button');
        var text = "h1"; //$('#linkstext').val();
        /*if (!text) {
            $('#status').html('Invalid text provided');
            return;
        }*/
        chrome.tabs.query({ active: true, currentWindow: true }, function(tabs) {
            chrome.tabs.sendMessage(tabs[0].id, { data: text }, function(response) {
                //$('#status').html('changed data in page '+tabs[0].url+' '+tabs[0].title);
                name = response.data.name;
                $('#name').html("Name:: " + response.data.name);
                $('#title').html("Title:: " + response.data.title);
                $('#company').html("Company:: " + response.data.company);
                //$('#website').html("Web:: " + response.data.website);
                $.ajax({
                    url: 'https://company.clearbit.com/v1/domains/find?name=' + response.data.company,
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Bearer sk_bc7d384f49c0de68ddd2c586d37a4cd2');
                    },
                    data: {},
                    success: function(res) {
                        var website = res.domain;
                        $('#website').html("Web:: " + website);
                        //alert(res.domain);
                        //console.log(res.domain);
                        //bob extract
                        if (website == "staffingadvisors.com") {
                            $.get("https://www." + website,
                                function(data) {
                                    var team_url = $(data).find('a:contains("Team")').attr("href");
                                    $("#status").html("team url: " + team_url);

                                    $.get(team_url,
                                        function(data) {
                                            // name = name.split(" ");
                                            var name2 = name.split(/[^A-Za-z]/);
                                            name2 = name2[0].toLowerCase();
                                            var email = $(data).find('a:contains("' + name2 + '@")').html();
                                            var phone = $(data).find('a:contains("' + name2 + '@")').parent("h3").next('h3').find("a").html();
                                            //$("#status").html("Email: " + team_url);
                                            $("#status2").html("Email: " + email);
                                            $("#status3").html("phone: " + phone);
                                        });
                                });
                        } //Bob extract for  trial   
                        //extract
                    },
                    error: function() {},
                });

                console.log('success');
            });
        });
    });
});


/*
$.get('xxx', 
function(data) {
    var pageDivs = $(data).find('div');
});
*/