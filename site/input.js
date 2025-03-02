
function checkInput(obj, ...ids)
{
    var txt = obj.value.trim();
    var dis = !txt.length;
    for (var id of ids)
    {
        var but = document.getElementById(id);
        if (but) but.disabled = dis;
    }
}
