
function listMenu(urlencode) {
  urlencode = urlencode || true;
  var node, list, arrValue;
  list = [];
  for (node = document.getElementById('sort_menu').firstChild;
      node;
      node = node.nextSibling) {
      if (node.nodeType == 1 && node.tagName == 'LI') {
          str = node.innerHTML;
          if(urlencode) {
            str = encodeURIComponent(str);            
          }
          list.push(str);
      }
  }
  return list;
}

function startSort() {
  var sortable = document.getElementById("sort_menu");
  nativesortable(sortable, { change: onchange });
}

function btnVis() {
  document.getElementById("menu_buttons").style.display="block";
}

function toggleNew(me, path){
  hideNew();
  var addNewLi = me.parentNode;
  var addButton = '<div id="addNew_box">';
  addButton += ' Title:<input id="new_item_title" type="text">\
  <br>Type:<select id="new_select_type"> \
  <option value="page">Page</option>\
  <option value="category">Category</option>\
  </select> <span class="mra_small_button" onclick="hideNew();">Cancel</span>\
  <span class="mra_button" onclick="newLink(\'';
  addButton += path; 
  addButton += '\')">Add new</span></div>' ; 
  addNewLi.innerHTML += addButton;
  addNewLi.firstChild.style.display = 'none';        
}
function hideNew() {
  if(document.getElementById('addNew_box')) {
    var newBox = document.getElementById('addNew_box');
    var newLi = newBox.parentNode;
    newLi.removeChild(newBox);
    newLi.firstChild.style.display = 'inline'
  }
}
function newLink(dir) {
  var title = document.getElementById("new_item_title").value;
  if(title == ''){ 
    alert('You must enter a title.');
    exit;
  };
  var url = '?action=new&type=';
  var e = document.getElementById('new_select_type');
  var type =  e.options[e.selectedIndex].value;
  url += type+'&dir=';  
  url += encodeURIComponent(dir);
  url += '&title=';  
  var newtitle = title;
  url += encodeURIComponent(newtitle);

  window.location.href = url;
}
function renameLink(oldpath) {
  var url = '?action=rename&old_path=';
  url += encodeURIComponent(oldpath);
  url += '&new_title=';  
  var newtitle = document.getElementById("title_field").value;
  url += encodeURIComponent(newtitle);

  window.location.href = url;
}
function deleteLink(path) {
  var answer = window.confirm('Are you sure you want to do this?\
  \n It will delete the page or category (and all its content) FOREVER\nProceed?');
  if(answer) {
  var url = '?action=delete&file=';
  url += encodeURIComponent(path);
  window.location.href = url;
  }
}
function ajaxMenu(action,path,title) {
  action = action || false;
  path = path || 0;
  title = title || 0;
  url = "menu_maker.php";
  buttons = document.getElementById("menu_buttons");
  xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("menu_wrap").innerHTML = xmlhttp.responseText;
      startSort();
      buttons.style.display="none"; 
    }
  }
  if (action=='save') {
    url += '?menu[]=';
    list = listMenu();
    url += list.join('&menu[]=');
  }
  if (action=='edit') {
    url +='?edit[]='+encodeURIComponent(path);
    url +='&edit[]='+encodeURIComponent(title);
  }
  xmlhttp.open("GET",url,true);
  xmlhttp.send();
}

function ajaxEdit(action,path,value) {
  action = action || false;
  path = path || 0;
  value = value || 0;
  url = "edit.php";
  xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("main-frame").innerHTML = xmlhttp.responseText;
      (function () {
        var converter = new Markdown.Converter();
        var help = function () { alert("Do you need help?"); }
        var options = {
          helpButton: { handler: help },
          strings: { quoteexample: "put you're quote right here" }
          };
        var editor = new Markdown.Editor(converter, "", options);
        editor.run();
      })();
      var wmdInput = document.getElementById("wmd-input").value;
    }
  }
  if (action=='save') {
    url += '?page='+encodeURIComponent(path);
    url += '&action=save';
    var wmdInput = document.getElementById("wmd-input").value;
    url += '&wmd-input='+encodeURIComponent(wmdInput);
  }
  if (action=='rename') {
    url +='?old_path='+encodeURIComponent(path);
    url +='&new_title='+encodeURIComponent(value);
  }
  if (action=='edit') {
    url +='?page='+encodeURIComponent(path);
  }
  xmlhttp.open("GET",url,true);
  xmlhttp.send();

}


