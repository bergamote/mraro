
function listMenu(urlencode) {
  urlencode = urlencode || true;
  var node, list, arrValue;
  list = [];
  for (node = document.getElementById('menu_ul').firstChild;
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
  var sortable = document.getElementById("menu_ul");
  nativesortable(sortable, { change: onchange });
}

function btnVis() {
  document.getElementById("menu_buttons").style.display="block";
}

function togFileTree() {
  var tree = document.getElementById("fileTree");
  var btn = document.getElementById("btn_files");
  if(tree.className=="hidden"){
    tree.className="shown";
    btn.innerHTML = "Hide files";
  } else {
    tree.className="hidden";
    btn.innerHTML = "Show files";
  }
}

function checkFileTree() {
  var url = window.location.href;
  var parser = document.createElement('a');
  parser.href = url;
  var action = parser.search.substring(8,11);
  if ((action == "new")||(action == "del")) {
    togFileTree();
  }
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

function deleteLink(path) {
  var answer = window.confirm('Are you sure you want to do this?\
  \n It will delete the page or category (and all its content) FOREVER\nProceed?');
  if(answer) {
  var url = '?action=delete&file=';
  url += encodeURIComponent(path);
  window.location.href = url;
  }
}
function ajaxMenu(action,path,title) { // Menu editing
  action = action || false;
  path = path || 0;
  title = title || 0;
  url = "menu_maker.php";
  buttons = document.getElementById("menu_buttons");
  xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("menu_ul").innerHTML = xmlhttp.responseText;
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

function saveEdit( path ) {
  var url = '?action=save';
  url += '&page='+encodeURIComponent(path);
  var wmdInput = document.getElementById("wmd-input").value;
  url += '&wmd-input='+encodeURIComponent(wmdInput);
  var newtitle = document.getElementById("title_field").value;    
  url += '&new_title='+encodeURIComponent(newtitle); 
  window.location.href = url;
}
function editPage(path) {
  var url = "?action=edit&page="+path;
  window.location.href = url;
}

// ---------------------------------- CTRL+S save document in edit mode


function addEv(path){
    window.addEventListener('keydown', function(e){saveShortcut(e, path)});
}

function removeEv(path) {
    window.removeEventListener('keydown', function(e){saveShortcut(e, path)});
}

function saveShortcut(e, path) {
if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)){
    e.preventDefault();
    console.log('ctrl+s');
    saveEdit( path );
    return false;
  }
}

// ----------------- Image manager 

function showImgManager() {
  var overlay = document.createElement('div');
  overlay.id = 'img_overlay'; 
  overlay.onclick = hideImgManager;   
  document.body.appendChild(overlay);
  document.body.style.overflow = 'hidden';
  var img_frame = document.createElement('iframe');
  img_frame.id = 'img_frame';
  img_frame.src = 'img_manager.php';
  overlay.appendChild(img_frame);
}

function delete_img(path) {
  var answer = window.confirm('Are you sure you want to delete this image forever and ever?');
  if(answer) {
  var url = 'img_manager.php?delete=';
  url += encodeURIComponent(path);
  location.href = url;
  }
}

function hideImgManager() {

  var img_o = document.getElementById('img_overlay');
  img_o.parentNode.removeChild(img_o);
  document.body.style.overflow = 'visible';
}

