/**
 *  litter.js
 *  
 * Litter is coded by Scott VonSchilling. He needs a job. If you like
 * what you see, please email scottvonschilling [at] gmail [dot] com.
 *  
 */

// Animated loading message.
var loading = {
	dom:null,
	phase:1,
	go:1,
	message:"Loading",
	timer:null,
	tick:function(){
		if (loading.go){
			switch(loading.phase){
				case 1:
					loading.phase = 2;
					loading.dom.innerHTML = loading.message;
					break;
				case 2:
					loading.phase = 3;
					loading.dom.innerHTML = loading.message + " .";
					break;
				case 3:
					loading.phase = 4;
					loading.dom.innerHTML = loading.message + " ..";
					break;
				case 4:
					loading.phase = 1;
					loading.dom.innerHTML = loading.message + " ...";
					break;
			}
			
		} else 
			clearInterval ( loading.timer );
	},
	init:function(domObj, message){
		loading.dom = document.getElementById(domObj);
		loading.dom.innerHTML = message;
		loading.message = message;
		loading.timer = setInterval (loading.tick, 500);
	}
}


// AJAX functions
function getAjax(){
	if (window.XMLHttpRequest) {
	  // Firefox, Chrome, Safari, Opera, IE7
	  return new XMLHttpRequest();
	}
	else {
	  // IE5 & 6
	  return new ActiveXObject("Microsoft.XMLHTTP");
	}
}

function setupLitter() {
	var status = document.getElementById("stats");
	loading.init("stats", "Setting up your Litter demo");
	
	ajax = getAjax(); 
	
	ajax.onreadystatechange=function(){
	  if (ajax.readyState==4 && ajax.status==200){
		  if (ajax.responseText == "success"){
			  loading.go = 0;
			  status.innerHTML = '<a href="./index.php?cookie=true">Click here to launch Litter!</a>'; 
		  }
	  }
	}
	ajax.open("GET","createNewSession.php",true);
	ajax.send();
}

function getNewLitts(){
	var top = document.getElementById("top_litt").innerHTML;
	ajax = getAjax(); 
	
	ajax.onreadystatechange=function(){
	  if (ajax.readyState==4 && ajax.status==200){
		  var response = eval('(' + ajax.responseText + ')');
		  if (response.status == "ok" && response.text != ""){
			  var onDeck = document.getElementById("on_deck");
			  onDeck.innerHTML = response.text + onDeck.innerHTML;
			  document.getElementById("top_litt").innerHTML = response.top;
			  document.getElementById("you_got_litts").style.visibility="visible";
		  }
	  }
	}
	ajax.open("GET","getLitts.php?before="+top,true);
	ajax.send();
}

function getOldLitts(){
	var bottom = document.getElementById("bottom_litt").innerHTML;
	ajax = getAjax(); 
	
	ajax.onreadystatechange=function(){
	  if (ajax.readyState==4 && ajax.status==200){
		  var response = eval('(' + ajax.responseText + ')');
		  	if (response.status == "ok"){
		  		var littSpace = document.getElementById("litt_space");
		  		littSpace.innerHTML = littSpace.innerHTML + response.text;
		  		document.getElementById("bottom_litt").innerHTML = response.bottom;
		  	}
	  }
	}
	ajax.open("GET","getLitts.php?after="+bottom,true);
	ajax.send();
}

function sendNewLitt(){
	var txt = document.getElementById("txt_box");
	var btn = document.getElementById("new_litt");
	var replyTo = document.getElementById("reply_to");
	
	btn.disabled=true;
	ajax = getAjax();
	
	var params = "text="+txt.value+"&reply="+replyTo.innerHTML;
	ajax.open("POST", "newLitt.php", true);

	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.setRequestHeader("Content-length", params.length);
	ajax.setRequestHeader("Connection", "close");
	
	ajax.onreadystatechange=function(){
		  if (ajax.readyState==4 && ajax.status==200){
			  var response = eval('(' + ajax.responseText + ')');
			  if (response.status == "ok"){
				  txt.value = "";
				  replyTo.innerHTML = "";
				  document.getElementById("top_litt").innerHTML = response.id.substr(1);
				  updateCharLimit();
				  loadOnDeck();
				  var littSpace = document.getElementById("litt_space");
				  littSpace.innerHTML = response.text + littSpace.innerHTML;
			  }
			  btn.disabled=false;
		  }
	}
	ajax.send(params);
}

function changeUserPane(userId){
	ajax = getAjax(); 
	
	ajax.open("GET","getUserPane.php?id="+userId,true);
	ajax.onreadystatechange=function(){
	  if (ajax.readyState==4 && ajax.status==200){
		  var response = eval('(' + ajax.responseText + ')');
		  if (response.status == "ok"){
			  document.getElementById("user_pane").innerHTML = response.text;
		  }
	  }
	}
	ajax.send();
}

function updateCharLimit(){
	var txt = document.getElementById("txt_box");
	var tinyText = document.getElementById("tiny_text");
	len = 140 - txt.value.length;
	if (len > 20)
		tinyText.style.color="#000000";
	else
		tinyText.style.color="#FF0000";
	
	if (len > 1)
		tinyText.innerHTML = len + " charaters left."; 
	else if (len == 1)
		tinyText.innerHTML = len + " charater left."; 
	else{
		tinyText.innerHTML = "No charaters left."; 
		while(txt.value.length > 140){
			txt.value=txt.value.replace(/.$/,'');
		}
	}
}

function replyTo(username, id){
	var txt = document.getElementById("txt_box");
	var replySpace = document.getElementById("reply_to");
	replySpace.innerHTML = id;
	txt.value="@"+ username + " " + txt.value;
	updateCharLimit();
}

function loadOnDeck(){
	var onDeck = document.getElementById("on_deck");
	document.getElementById("you_got_litts").style.visibility="hidden";
	var littSpace = document.getElementById("litt_space");
	littSpace.innerHTML = onDeck.innerHTML + littSpace.innerHTML;
	onDeck.innerHTML = "";
}