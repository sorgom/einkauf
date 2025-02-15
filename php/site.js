function note(obj) {
    var id = obj.id;
    var checked = obj.checked ? 1 : 0;
    var data = "i=" + id + "&c=" + checked;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}