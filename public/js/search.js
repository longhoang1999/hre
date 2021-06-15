var hreViet = true;
$(document).ready(function(){
    $('#inputSearch').keyup(delay(function (e) {
        if($(this).val() != "")
        {
            $(this).css("border-radius","7px 7px 0 0");
            $(".block_search ul").show();
            let value = $(this).val();
            callAjax(value);
        }
        else
        {
            $(this).css("border-radius","7px");
            $(".block_search ul").hide();
        }
    }, 50));
    $(".block_search ul").on('click','li',function() {
        let hreSound  = $(this).find(".hreSound").text();
        let vnSound = $(this).find(".vnSound").text();
        $(".text_zone_hre").text(hreSound);
        $(".text_zone").text(vnSound);
        $("#inputSearch").val("");
        $("#inputSearch").css("border-radius","7px");
        $(".block_search ul").hide();

    })
    $(".two-way-arrow").click(function() {
        hreViet = !hreViet;
        changeStatusPage(hreViet);
    })
})
function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function callAjax(value) {
    let getUrl = window.location.href;
    $.ajax({
        method: 'POST',
        url: `${getUrl}demo/ajax-search`,
        data: {key: value, status: hreViet},
        success: function (data) {
            if(data[0] == "true")
            {
                $(".block_search ul").empty();
                data[1].forEach((item, index) => {
                    $(".block_search ul").append(`<li class="liResult"><span class="hreSound">${item.hre}</span> -> <span class="vnSound">${item.vn}</span></li>`);
                })
            }
            else if(data[0] == "false")
            {
                $(".block_search ul").empty();
                data[1].forEach((item, index) => {
                    $(".block_search ul").append(`<li class="liResult"><span class="vnSound">${item.vn}</span> -> <span class="hreSound">${item.hre}</span></li>`);
                })
            }
        }
    });
}
function changeStatusPage(status) {
    $("#inputSearch").val("");
    let lang_two = $(".lang_two").text();
    let lang_one = $(".lang_one").text();
    $(".lang_one").text(lang_two);
    $(".lang_two").text(lang_one);
    let placeholder = $("#inputSearch").attr("placeholder");
    if(placeholder.search("hre") != -1)
        placeholder = "Tìm kiếm tiếng việt";
    else
        placeholder = "Tìm kiếm tiếng hre";
    $("#inputSearch").attr("placeholder",placeholder);
    $("#inputSearch").css("border-radius","7px");
    $(".block_search ul").hide();
}