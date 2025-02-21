var usr;
var numItems = undefined;
var numDone = 0;

function setusr(u)
{
    usr = u;
}

function getNums()
{
    if (numItems === undefined)
    {
        var items = document.getElementsByTagName('li');
        numItems = items.length;
        numDone = 0;
        for (var item of items)
        {
            if (item.classList.contains('x')) ++numDone;
        }
        console.log('numItems: ', numItems, ' numDone: ', numDone);
    }
}

function sendId(id, checked, func=undefined)
{
    console.log('sendId: ', id, ' checked: ', checked);
    var data = "usr=" + usr + "&id=" + id + "&ck=" + (checked ? 1 : 0);
    console.log('data: ', data);
    var xhr = new XMLHttpRequest();
    if (func)
    {
        xhr.onreadystatechange = function()
        {
            if (xhr.readyState == XMLHttpRequest.DONE)
            {
                func();
            }
        }
    }
    xhr.open("POST", "done.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}

function chapId(id)
{
    return id.match(/(\d+)\./)[1];
}

function ck(obj)
{
    getNums();
    var p = obj.parentElement;
    p.classList.toggle('x');
    var checked = p.classList.contains('x');
    sendId(p.id, checked);

    numDone += checked ? 1 : -1;
    if (numDone == numItems) sendId(chapId(p.id), true);
}

function chapDone(obj)
{
    sendId(obj.id, true, function() { window.location.replace('/?' + usr); });
}

function checkinput(obj, ...ids)
{
    var txt = obj.value.trim();
    var dis = !txt.length;
    for (var id of ids)
    {
        var but = document.getElementById(id);
        if (but) but.disabled = dis;
    }
}
