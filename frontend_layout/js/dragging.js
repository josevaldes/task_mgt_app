function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData('text', ev.target.id);
}

function drop(ev, status_id) {
    ev.preventDefault();
    ev.stopPropagation();
    var data = ev.dataTransfer.getData('text');
    var element = ev.target;
    while(element.id !== "1" && element.id !== "2" && element.id !== "3")
    {
      element = element.parentElement;
    }
    if(element)
    {
       element.appendChild(document.getElementById(data));
       var task_id = document.getElementById(data).id;

       $.post('./updateStatus.php', {'statusId': status_id, 'taskId': task_id}, function(data)
       {
         /*
          if(data != "1")
          {
            alert ("Error in updating the values. Please contact administrator");
          }
          */
       });
    }
}
