/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var n=0;

function previous(qno)
{
    if(qno === "0")
        alert("This Question is First.");
    else
    {
        $('#q'+qno--).css({"display":"none"});
        $('#q'+qno).css({"display":"block"});
    }
}

function next(qno, last)
{
    if(qno === last)
        alert("This Question is Last.");
    else
    {
        $('#q'+qno++).hide();//css({"display":"none"});
        $('#q'+qno).show();//css({"display":"block"});
    }
}

function confrm()
{
    var ch = confirm("Do You Want to Submit???");
    if(ch === true)
        document.getElementById('examForm').submit();
}