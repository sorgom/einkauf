var uid      = undefined;;
var numItems = undefined;
var bTop     = undefined;
var clicked  = false;
var cont     = undefined;
var topId    = undefined;

function setUid(u)
{
    uid = u;
    cont = document.getElementById('items');
    var items = cont.getElementsByTagName('div');
    numItems = items.length;
    for (var item of items)
    {
        item.onclick = function() { dea(this) };
        item.firstChild.onclick = function() { ck(this) };
    }
    bTop = document.getElementById('top').firstChild;
    count();
}

//  disable / enable item
function dea(obj)
{
    if (clicked)
    {
        clicked = false;
        return;
    }
    obj.classList.remove('x');
    obj.classList.toggle('y');
    send(obj);
    count();
}

//  check all done
function count()
{
    var cn = bTop.className;
    var ncn = '';
    var ix = cont.getElementsByClassName('x').length;
    var iy = cont.getElementsByClassName('y').length;
    if (ix + iy == numItems)
        ncn = iy > 0 ? 'y' : 'x';
    if (ncn != cn)
    {
        bTop.className = ncn;
        send(bTop);
    }
}

//  send object state (class)
function send(obj)
{
    var data = 'uid=' + uid + '&id=' + obj.id + '&ck=' + obj.className;
    console.log('data:', data);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'done.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}

//  check item
function ck(obj)
{
    clicked = true;
    var p = obj.parentElement;
    if (p.classList.contains('y')) return;

    p.classList.toggle('x');
    send(p);
    count();
}
