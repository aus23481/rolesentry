//Left Sidebar Favorites Actions

function getUserFavorites(table_id) {
    //if (!table_id) table_id = 1;
    //alert(table_id);

    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/user-favorites',
        data: "table_id=" + table_id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                if (table_id == 1) loadFavoriteCompanies(res);
                if (table_id == 2) loadFavoriteHiringManager(res);

                if (table_id == 3) getSavedSearchItems(); //loadFavoriteSavedSearches(res);
                if (table_id == 4) loadFavoriteOpenings(res);
                if (table_id == 5) loadFavoriteCandidates(res);
            }
            $("#loading").hide();
        }
    });
}

//get user favorites search

function getUserFavoritesSearch(table_id) {
    //if (!table_id) table_id = 1;
    //alert(table_id);

    if (table_id == 1) search_text = $("#searchbox_company").val();
    if (table_id == 2) search_text = $("#searchbox_hiring_manager").val();
    if (table_id == 3) search_text = $("#searchbox_saved_search").val();
    if (table_id == 4) search_text = $("#searchbox_opening").val();


    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/user-favorites',
        data: "search_text=" + search_text + "&table_id=" + table_id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                if (table_id == 1) loadFavoriteCompanies(res);
                if (table_id == 2) loadFavoriteHiringManager(res);

                if (table_id == 3) loadFavoriteSavedSearches(res);
                if (table_id == 4) loadFavoriteOpenings(res);
                setTimeout(function() {
                    makeFavoriteSearchTextBold(table_id);

                }, 2000);
            }
            $("#loading").hide();
        }
    });
}

//favorite_search_with_opening()

function favorite_search_with_opening(ssid) {

    //-1 means here action if exists in favorite and search from leftsidebar
    //2 table id

    clear_search();

    var search_text = $.trim($("#ss-" + ssid).text());
    $("input[type='checkbox']").prop("checked", false);
    //alert(search_text);
    $("#search").val(search_text);
    searchPlatform(1);
}


//Companies
function loadFavoriteCompanies(res) {
    // console.log(res);
    var $favorite_html = '';
    $("#ul_favorite_companies").html("");

    if (res.favorites === undefined || res.favorites.length == 0) {
        $favorite_html = "<span style='color:#c2c9d4;font-size:small;line-height:.9'><span style='color:white'>You are not tracking any companies</span>.  Click on the star (<span style='margin-right:0px' class='glyphicon glyphicon-star'></span>) icon to track a company.</span>";
    } else {
        $.each(res.favorites, function(k, favorite) {
            //onclick=" addUserFavoriteItem(' + favorite.id + ', 1, -1); favorite_search_with_opening(' + favorite.id + ')"
            $favorite_html += '<li class="favorite_li"  onclick="favorite_search_with_opening(' + favorite.id + ')" ><span ><span class="glyphicon glyphicon-star"></span><span id="ss-' + favorite.id + '" > ' + favorite.name + '</span></span><span  id="remove-item">&nbsp;&nbsp;&nbsp;<img class="favorite_remove_img" onclick="addUserFavoriteItem(' + favorite.id + ', 1)"  src="images/remove.png"/></span></li>';
        });
    }
    $("#ul_favorite_companies").html($favorite_html);
}
//HM
function loadFavoriteHiringManager(res) {
    // console.log(res);     
    var $favorite_html = '';
    $("#ul_favorite_hiring_managers").html("");

    if (res.favorites === undefined || res.favorites.length == 0) {
        $favorite_html = "<span style='color:#c2c9d4;font-size:small;line-height:.9'><span style='color:white'>You are not tracking any hiring managers</span>.  Click on the star (<span class='glyphicon glyphicon-star' style='margin-right:0px'></span>) icon to track a hiring manager.</span>";
    } else {
        var actions = 0;
        $.each(res.favorites, function(k, favorite) {
            if (res.pa[favorite.prospect_id] === undefined) actions = 0;
            else actions = res.pa[favorite.prospect_id];
            $favorite_html += '<li class="favorite_li" onclick="favorite_search_with_opening(' + favorite.id + ')"><span  ><span class="glyphicon glyphicon-star"></span><span  style="cursor:pointer" onClick="window.open(\'' + baseurl + '/prospect?id=' + favorite.prospect_id + '\', \'_blank\');" id="ss-' + favorite.id + '" >' + favorite.name + ((actions == 0) ? ('<span class="numberCircle">' + 1 + '</span>') : ('') ) + '</span> </span> <span style="text-align: center !important;"  id="remove-item">&nbsp;&nbsp&nbsp;<img onclick="addUserFavoriteItem(' + favorite.id + ', 2);" class="favorite_remove_img" style="" src="images/remove.png"/></span></li>';
        });
    }
    $("#ul_favorite_hiring_managers").html($favorite_html);
}



//Candidate
function loadFavoriteCandidates(res) {
    console.log(res);
    var $favorite_html = '';
    $("#ul_favorite_candidates").html("");

    if (res.favorites === undefined || res.favorites.length == 0) {
        $favorite_html = "<span style='color:#c2c9d4;font-size:small;line-height:.9'><span style='color:white'>You are not tracking any hiring managers</span>.  Click on the star (<span class='glyphicon glyphicon-star' style='margin-right:0px'></span>) icon to track a hiring manager.</span>";
    } else {
        var actions = 0;
        $.each(res.favorites, function(k, favorite) {
            // console.log(favorite.first_name + " " + favorite.last_name);
            if (res.pa[favorite.prospect_id] === undefined) actions = 0;
            else actions = res.pa[favorite.prospect_id];
            $favorite_html += '<li class="favorite_li" onclick="favorite_search_with_opening(' + favorite.id + ')"><span  ><span class="glyphicon glyphicon-star"></span><span  style="cursor:pointer" onClick="window.open(\'' + baseurl + '/prospect?id=' + favorite.prospect_id + '\', \'_blank\');" id="ss-' + favorite.id + '" >' + favorite.first_name + ' ' + favorite.last_name + '(' + actions + ')' + '</span> </span> <span style="text-align: center !important;"  id="remove-item">&nbsp;&nbsp&nbsp;<img onclick="addUserFavoriteItem(' + favorite.id + ', 5);" class="favorite_remove_img" style="" src="images/remove.png"/></span></li>';
        });
    }
    $("#ul_favorite_candidates").html($favorite_html);
}

//searches
function loadFavoriteSavedSearches(res) {
    var $favorite_html = '';
    $("#ul_favorite_saved_searches").html("");
    $.each(res.favorites, function(k, favorite) {
        $favorite_html += '<li><span class="glyphicon glyphicon-star"></span>' + favorite.term + '<span onclick="addUserFavoriteItem(' + favorite.id + ', 3)"   id="remove-item"><img src="images/remove.png"/></span></li>';
    });

    $("#ul_favorite_saved_searches").html($favorite_html);
}

//Openings

function loadFavoriteOpenings(res) {
    var $favorite_html = '';
    $("#ul_favorite_openings").html("");


    if (res.favorites === undefined || res.favorites.length == 0) {
        $favorite_html = "<span style='color:#c2c9d4;font-size:small;line-height:.9'><span style='color:white'>You are not tracking any openings</span>.  Click on the star (<span style='margin-right:0px' class='glyphicon glyphicon-star'></span>) icon to track an opening.</span>";
    } else {

        $.each(res.favorites, function(k, favorite) {
            $favorite_html += '<li><span class="glyphicon glyphicon-star"></span><span onclick="favorite_search_with_opening(' + favorite.id + ')"  id="ss-' + favorite.id + '">' + favorite.title + '</span><span onclick="addUserFavoriteItem(' + favorite.id + ', 4)" id="remove-item"><img src="images/remove.png"/></span></li>';
        });
    }

    $("#ul_favorite_openings").html($favorite_html);
}

function makeFavoriteSearchTextBold(table_id) {

    var search_text;
    var result_container;

    if (table_id == 1) {
        search_text = $("#searchbox_company").val();
        result_container = "ul_favorite_companies";
    }
    if (table_id == 2) {
        search_text = $("#searchbox_hiring_manager").val();
        result_container = "ul_favorite_hiring_managers";
    }
    if (table_id == 3) {
        search_text = $("#searchbox_saved_search").val();
        result_container = "ul_favorite_saved_searches";
    }
    if (table_id == 4) {
        search_text = $("#searchbox_opening").val();
        result_container = "ul_favorite_openings";
    }


    if (search_text != "") {
        $("#" + result_container + " li:contains('" + search_text + "')").each(function() {
            $(this).html(
                $(this).html().replace(search_text, '<strong>$&</strong>')
            );
        });
    }
}



getUserFavorites(1); //company
getUserFavorites(2); //Hiring Manager

//getUserFavorites(3); //Searches
//getUserFavorites(4); //Openings
getUserFavorites(5); //candidate
