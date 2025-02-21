var usr;

function setusr(u)
{
    usr = u;
}

function ck(obj)
{
    var p = obj.parentElement;
    p.classList.toggle('x');
    var checked = p.className == 'x' ? 1 : 0;
    var id = p.id;
    // console.log('id: ', id, ' checked: ', checked);
    var data = "usr=" + usr + "&id=" + id + "&ck=" + checked;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "done.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
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
