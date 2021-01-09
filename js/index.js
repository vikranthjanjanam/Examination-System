function hideDiv(atr)
{
        var hideArray=document.getElementsByClassName("pages");
	for(var i=0; i<hideArray.length; i++) {
		hideArray[i].style.display="none";
                var j=hideArray[i].id;
                if(j===atr)
                    hideArray[i].style.display="inline-block";
	}
        var menu=document.getElementsByClassName("actual");
	for(var i=0; i<menu.length; i++) {
		menu[i].style.background="#E37C20";
		menu[i].style.color="#fff";
	}
}
function changeMenuHelp(obj)
{
	var atr=obj.getAttribute("pid");
        hideDiv(atr);
	
}
function changeMenu(obj)
{
	changeMenuHelp(obj);
	obj.style.background="#FFDCAC";
	obj.style.color="black";
}

function validPassword(st)
{
	var ob=document.forms[st];
	var p=ob.pass.value;
	if(p.length===0)
		alert("Password should not be empty!!!\n\nStrong Password is Required.");
	else if(!p.match(/[A-Z]/))
		alert("Password should have atlast one Capital alphabet.\n\nStrong Password is Required.");
	else if(!p.match(/[a-z]/))
		alert("Password should have atlast one Small alphabet.\n\nStrong Password is Required.");
	else if(!p.match(/[0-9]/))
		alert("Password should have atlast one digit.\n\nStrong Password is Required.");
	else if(!(p.match(/[\W]/)))
		alert("Password should have atlast one Special character.\n\nStrong Password is Required.");
	else if(p.length<8)
		alert("Password should contain atleast 8 characters.\n\nStrong Password is Required.");
}

function conPassword(st)
{
	var ob=document.forms[st];
	var p=ob.pass.value;
	var conp=ob.conPass.value;
	if(p !== conp)
		alert("Passwords doesn't match!!!");
}

function validGender(frm)
{
	var ob=document.forms[frm];
	var op=ob.gender.selectedIndex;
	if(op === "")
		alert("Please Select Gender");
}

function validGrp(frm)
{
	var ob=document.forms[frm];
	var op=ob.tm.selectedIndex;
	if(op === "")
		alert("Please Select Number for Team");
}

function hideError()
{
	var obj=document.getElementById('error');
	obj.style.display="none";
        var hdr=document.getElementById('errmsg');
        hdr.innerHTML="";
}

function unhideError(msg)
{
        var obj=document.getElementById('error');
	obj.style.display="block";
        var hdr=document.getElementById('errmsg');
        hdr.innerHTML=msg;
}

function verifyPhone(frm)
{
        var ob=document.forms[frm];
        var phn=ob.phn.value;
        if(phn.length!==10)
            alert("Invalid Phone!!!");
}

function validDate(frm)
{
        var ob=document.forms[frm];
        var dt=ob.dte.value;
        if(dt === "")
            alert("Date must not be empty!!!");
        var cr=new Date();
        var cy=cr.getFullYear().toString();
        var cm=(cr.getMonth()+1).toString();
        var cd=cr.getDate().toString();
        
}

function target_popup(form) {
    window.open('admin.php', 'formpopup', 'width=400,height=400,resizeable,scrollbars');
    form.target = 'formpopup';
}

function chVal(ob)
{
    var name=ob.getAttribute("name");
    ob.value=name;
    ob.name="GetResult";
}