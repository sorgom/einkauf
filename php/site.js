var usr;

function setusr(u)
{
    usr = u;
}

function cks(obj, func = undefined)
{
    obj.classList.toggle('x');
    var checked = obj.classList.contains('x') ? 1 : 0;
    var id = obj.id;
    // console.log('id: ', id, ' checked: ', checked);

    var data = "usr=" + usr + "&id=" + id + "&ck=" + checked;
    // console.log('data: ', data);
    var xhr = new XMLHttpRequest();
    if (func)
    {
        console.log('with func');
        xhr.onreadystatechange = function()
        {
            if (xhr.readyState == XMLHttpRequest.DONE)
            {
                func(checked);
            }
        }
    }
    xhr.open("POST", "done.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}

function ck(obj)
{
    cks(obj.parentElement);
}

function ckc(obj)
{
    cks(obj,
        function(checked) { if (checked) window.location.replace('/?' + usr);}
    );
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
