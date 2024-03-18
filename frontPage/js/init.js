$(document).ready(function() {

    var heightBrowse = $(window).height()-10;
    if ($(window).width()<1750) heightBrowse = heightBrowse - 120;
    $.ajax({
        type:"GET",
        url:"frontPage/css/varebl.php",
        dataType: 'text',
        data:{heightBrowse:heightBrowse
        },
        success: function(data){
            integrationsScriptCSS("head",data);
            $.ajax({
                type:"GET",
                url:"index_ajax.php",
                data:{r0:"skeletonApp"},
                dataType: 'text',
                success: function(data){ integrationsScriptCSS("head",data)}
            });
//            setCookie("StartAPP","Start",0);
        }
    });

        loadscript();
});

function RunMenu(route,parent){
    $.ajax({
        type:"GET",
        url:"index_ajax.php",
        dataType: 'text',
        data:{r0:route,
            parent:parent},
        success: function(data){integrationsScriptCSS("mainContent",data)}
    });
}

function getStyleSheet(unique_title) {
    for(var i=0; i<document.styleSheets.length; i++) {
        var sheet = document.styleSheets[i];
        var mcssrules=sheet.cssRules;
        for (key in mcssrules){
            var inobj=mcssrules[key];
            key1='selectorText';
            mselectorText=inobj[key1];
            key='cssText';
            mcssText=inobj[key];
            if (mselectorText==unique_title){
                var regexp = /\{([\s\S]*?)}/;
                var matches = mcssText.match(regexp);
                return matches[1];
            }
        }
        if(sheet.title == unique_title) {
            return sheet;
        }
    }
}
function integrationsScriptCSS(IDDOMObject,data){
    if (data.search( /outputBlockAPP/i )!=-1){
        if (!$('div').is('#BlockAPP')){
            BlockAPP();
        }
        IDDOMObject="BlockAPP";
    }
    var macroCSS = [ /*Ниже описаны классы которые будут заменятся в пришедшем коде CSS*/
        ".borderRadiusBottom",
        ".shadowNormal",
        ".borderTop",
        ".borderBottom",
        ".BorderAll",
        ".borderRadiusTop",
        ".borderRadiusBottom",
        ".backgroundNormal",
        ".backgroundInsert",
        ".backgroundAlert",
        ".textColorWhite",
        ".textColorBlack",
        ".textFontBig",
        ".textFontNormal",
        ".textFontSmall",
        ".background-Menu",
        ".background-Insert-Menu"
    ];
    var regexp = /<style>([\s\S]*?)<\/style>/g//;
    var matches1 = data.match(regexp);
    if (matches1!==null) { //если имеются стили
        for (var $ii = 0; $ii < matches1.length; $ii++) { // крутим пока стили не кончатся
            var regexp = /<style[^>]*>([\s\S]*?)<\/style>/;
            var matches = matches1[$ii].match(regexp);
            var newStyle = matches[1];
            macroCSS.forEach(function (item) {
                regexp = new RegExp("/\\*" + item + "\\*/", 'g');
                var styleSheetObject = getStyleSheet(item);
                newStyle = newStyle.replace(regexp, styleSheetObject);
            });
            if (matches !== null) {
                var style = document.createElement('style');
                style.textContent = newStyle;
                style.type = "text/css";
                document.getElementsByTagName("head")[0].appendChild(style);
                /*            document.head.innerHTML += '<style type="text/css">' + newStyle + '</style>'*/
            }
        }
    }
    //Далее грузится HTML
    var regexp = /<code[^>]*>([\s\S]*?)<\/code>/;
    var matches = data.match(regexp);
    if (matches!==null) {
        var srtMatches = matches[1];

        var regexp2 = new RegExp("<style[^>]*>([\\s\\S]*?)<\/style>", 'g');
        srtMatches = srtMatches.replace(regexp2, "");

        var regexp3 = new RegExp("<script[^>]*>([\\s\\S]*?)<\/script>", 'g');
        srtMatches = srtMatches.replace(regexp3, "");

        if (IDDOMObject == "head") {
            if (srtMatches.length > 2) {
                document.getElementsByTagName("body")[0].innerHTML = srtMatches;
            }
        } else {
            document.getElementById(IDDOMObject).innerHTML = srtMatches;
        }
    }
    //далее грузятся скрипты
    var regexp= new RegExp("<script[^>]*>([\\s\\S]*?)<\\/script>",'g');
    var matches = data.match(regexp);
    if(matches!==null){
        for (var $ii=0;$ii<matches.length;$ii++){
            var e = document.createElement("script");
            var mach = matches[$ii] ;
            regexp= new RegExp("<script>",'g');
            mach=mach.replace(regexp,"");
            regexp= new RegExp("</script>",'g');
            mach=mach.replace(regexp,"");
            e.text = mach;
            e.type="text/javascript";
            document.getElementsByTagName("head")[0].appendChild(e);
        }
    }



    loadscript();
}

function BlockAPP() {

    var div1 = document.createElement("div");
    div1.id="BlockAPP";
    div1.setAttribute("class", "BlockAPP");
    /*
     var div2 = document.createElement("div");
     div2.id="BlockAPP";
     div1.appendChild(div2);
     */

    document.body.appendChild(div1);
}

function BlockAPPWait()
{
    var div1 = document.createElement("div");
    div1.id="msgWait";
    div1.setAttribute("class", "MsgWait");
    div1.setAttribute("style", "top:0px;left:0px;width:447px;height:465px");
    $("#BlockAPP").empty();
    $("#BlockAPP").append(div1);
}

function closeBlockAPP(){
    $("#BlockAPP").empty();
    $("#BlockAPP").remove();
}
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}
function _G_buttonSelect(id_greed,callFunction_txt)
{

    _G_varVal = _G_foundCheckedRowInGrid_returnObject(id_greed);
    eval(callFunction_txt);
}

function _G_refreshFilter(parent,className,name_div_greed,filterBlockName)
{
    var data = {};
    data['parent'] = parent;
    data['r0'] = className;
    data['r1'] = "getListFilter";
    var allFilterElement = $("#"+filterBlockName).find(".filterElement");
    for (var i = 0; i < allFilterElement.length; i++) {
        item = allFilterElement[i];
        data[item.name] = item.value
    }
    $.ajax({
        type:"GET",
        url:"index_ajax.php",
        data:data,
        dataType: 'text',
        success: function(data){ integrationsScriptCSS(name_div_greed,data)}
    });
}

function saveDateOutClassMethod(className,methodName,varName,varVal)
{
    $.ajax({
        type:"GET",
        url:"index_ajax.php",
        data:{r0:className,r1:methodName,
            varName:varName,
            varVal:varVal
        },
        dataType: 'text',
        success: function(data){ integrationsScriptCSS("body",data)}
    });
}
function _G_downloadReport(id_report)
{

    $.ajax({
        type: "GET",
        url: "index_ajax.php",
        dataType: 'text',
        data: {
            r0:"SYS",
            r1:"downloadReport",
            id_report: id_report
        },
        success: function (data) {
            var dataArray = JSON.parse(data);


            /*            var path_download="/upload/GISJKH/" + dir + "/" + downloadFileName + ".xlsx";
             var nowDate = new Date();
             newFileName = newFileName+nowDate+".xlsx";
             var link = document.createElement('a');
             link.setAttribute('href', path_download);
             link.setAttribute('download', newFileName);
             link.click();

             //document.location.href = path_download ;
             */
            closeBlockAPP();
        }
    });
}

function _G_BlockAppMessage( message )
{
    BlockAPP();
    $.ajax({
        type:"GET",
        url:"index_ajax.php",
        data:{r0:"inputEditVariable",r1:"messageReadeOnly",
            message:message},
        dataType: 'text',
        success: function(data){
            integrationsScriptCSS('BlockAPP',data)
        }
    });
}
function _G_BlockAppQuestion( message, callFunction)
{
    BlockAPP();
    $.ajax({
        type:"GET",
        url:"index_ajax.php",
        data:{r0:"inputEditVariable",r1:"yesOrNotButtons",
            message:message,
            callFunction:callFunction},
        dataType: 'text',
        success: function(data){
            integrationsScriptCSS('BlockAPP',data)
        }
    });
}

$( document).mouseup(function() {
    _G_moveIdBlock = false;
});

$(document).mousemove(function (event) {
    if (_G_moveIdBlock !== false){
        var deltaX = (event.pageX -_G_mouseMemX);
        var deltaY = (event.pageY -_G_mouseMemY);
        var X =  _G_windowMemX + deltaX;
        var Y =  _G_windowMemY + deltaY;
        $("#WinMain__"+_G_moveIdBlock).offset({top:Y, left:X})
    }
});


function loadscript() {

}