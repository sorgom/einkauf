function checkme(obj) 
{
    var id = obj.id;
    var checked = obj.checked ? 1 : 0;
    var data = "id=" + id + "&ck=" + checked;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "checked.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}