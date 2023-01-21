/*
    Datei: tooltip.js
    Datum: 02.12.2004
    Autor: J. Strübig <struebig@gmx.net>
    Web: http://javascript.jstruebig.de/source/tooltip.html
*/

/////////////////////////////////////////////////
// Globale Variabeln

var tt_className         = 'tooltip';
var tt_id                = 'tooltip';
var tt_abstand           = 10;
var tt_wait              = 150;

var tt_styles            = new Object();
tt_styles.width          = '15em';
tt_styles.backgroundColor = '#000000';
tt_styles.color           = '#8897cf';
tt_styles.fontSize        = '1em';
tt_styles.padding         = '2px';
tt_styles.border          = '1px solid #FFFFFF';
//...


function tooltip(el, text, e)
{
    if( !window.tObj ) return alert('Fehler');
    window.tObj.innerHTML = text;
    window.tObj.style.display = 'block';
    moveTooltip(e);
}

function moveTooltip(e)
{
    if(!window.tObj ) return;
    var p = getMousePos(e);
    var s = getSize(window.tObj);
    var offSet = pageOffset();
    var dLeft = (s.width + tt_abstand)
    var dTop = (s.height + tt_abstand)

    if(p.left - dLeft < 0)
    {
        // p.left +=  dLeft;
    }
    else
    {
        p.left -= dLeft;
    }

    if(p.top - dTop < offSet.top)
    {
       p.top += tt_abstand;
    }
    else
    {
       p.top -= dTop;
    }
    window.tObj.style.top = p.top + 'px';
    window.tObj.style.left= p.left + 'px';
}

function hideTooltip()
{
    if( window.tObj && window.tObj.hide) window.tObj.style.display = 'none';
}

////////////////////////////////////////////////////////////
// Hilfsfunktionen:

function getMousePos(e)
{
    if(!e) e = window.event;
    var pos = new Object();

    pos.left = e.clientX;
    pos.top = e.clientY;

    var b = getBody(window)
    if (b) {
        pos.left += b.scrollLeft;
        pos.top += b.scrollTop;
    }
    return pos;
}


function getSize(o)
{
    if(!o) return;
    return { width: o.offsetWidth, height:  o.offsetHeight};
}
////////////////////////////////////////////////////////////
// offset(window)
function pageOffset(win)
{
    if(!win) win = window;
    var pos = {left:0,top:0};

    if(typeof win.pageXOffset != 'undefined')
    {
         // Mozilla/Netscape
         pos.left = win.pageXOffset;
         pos.top = win.pageYOffset;
    }
    else
    {
         var obj = getBody(win);
         pos.left = obj.scrollLeft;
         pos.top = obj.scrollTop;
    }
    return pos;
}


////////////////////////////////////////////////////////////
// speziell für den IE 6.
function getBody(w)
{
    return w.document.compatMode && w.document.compatMode == "CSS1Compat" ?
           w.document.documentElement : w.document.body || null;
}

window.onload = function()
{
    var all = document.all ? document.all :
    document.getElementsByTagName ? document.getElementsByTagName('*') : null;

    addLayer(tt_id);

    window.tObj = document.getElementById ? document.getElementById(tt_id)
    : document.all ? document.all[tt_id] : null;
    window.tObj.hide = true;
    hideTooltip();

    for(var s in tt_styles) window.tObj.style[s] = tt_styles[s];

    for(var i = 0;i < all.length; i++)
    {
        if(all[i].className == tt_className)
        {
              all[i].onmouseover = function(e) { tooltip(this, this.tooltip, e);  };
              all[i].onmouseout = function(e) { window.setTimeout("hideTooltip()", tt_wait); };
              all[i].tooltip = all[i].title;
              all[i].title = '';
       }
    }
    window.tObj.onmouseover = function() { this.hide = false; }
    window.tObj.onmouseout = function() { this.hide = true; hideTooltip()}
}
/////////////////////////////////////////////////
function addLayer(id)
/////////////////////////////////////////////////
{
    if (window.document.body.appendChild)
    {
         var test = document.createElement('div');
         test.id = id;
         test.style.position = 'absolute';
         test.style.display = 'none';
         window.document.body.appendChild(test);
    }
    else if (document.body.insertAdjacentHTML)
    {
         window.document.body.insertAdjacentHTML.binsertAdjacentHTML("afterBegin", '<div style="display:none;position:absolute" id="' + id + '"></div>');
    }
    else if (window.innerHTML) window.innerHTML += '<div style="position:absolutedisplay:none;" id="' + id + '"></div>';
}