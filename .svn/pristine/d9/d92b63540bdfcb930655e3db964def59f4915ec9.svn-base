(function(document) {
    'use strict';

    var LightTableFilter = (function(Arr) {

        var _input;
		
        function _onInputEvent(e) {
            _input = e.target;
            document.getElementById("käyttäjä").href = document.getElementById("käyttäjäold").value +
        	    "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;

            document.getElementById("lokitunnus").href = document.getElementById("lokitunnusold").value +
         	    "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;

            document.getElementById("tapahtumatunnus").href = document.getElementById("tapahtumatunnusold").value +
         	    "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;

            document.getElementById("aikaleima").href = document.getElementById("aikaleimaold").value +
          	    "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;
            
           	document.getElementById("alue").href = document.getElementById("alueold").value +
        	    "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;

           	document.getElementById("kuka").href = document.getElementById("kukaold").value +
        	    "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;

           	document.getElementById("tapahtuma").href = document.getElementById("tapahtumaold").value +
        	    "\\" + document.getElementById("selUserLimitOld").value + 
          	    "\\" + document.getElementById("selDateLimitOld").value;

           	document.getElementById("koska").href = document.getElementById("koskaold").value +
    	        "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;
            
           	document.getElementById("toiminto").href = document.getElementById("toimintoold").value +
    	        "\\" + document.getElementById("selUserLimitOld").value + 
           	    "\\" + document.getElementById("selDateLimitOld").value;
            
            if (_input.value != "") {
                document.getElementById("käyttäjä").href =
                	document.getElementById("käyttäjä").href + 
                	"\\" + _input.value;
                document.getElementById("lokitunnus").href =
                	document.getElementById("lokitunnus").href + 
                   	"\\" + _input.value;
                document.getElementById("tapahtumatunnus").href =
                	document.getElementById("tapahtumatunnus").href + 
                   	"\\" + _input.value;
                document.getElementById("aikaleima").href =
                	document.getElementById("aikaleima").href + 
                	"\\" + _input.value;
                document.getElementById("alue").href =
                    document.getElementById("alue").href + 
                    "\\" + _input.value;
                document.getElementById("kuka").href =
                    document.getElementById("kuka").href + 
                    "\\" + _input.value;
                document.getElementById("tapahtuma").href =
                    document.getElementById("tapahtuma").href + 
                    "\\" + _input.value;
                document.getElementById("koska").href =
                    document.getElementById("koska").href + 
                    "\\" + _input.value;
                document.getElementById("toiminto").href =
                    document.getElementById("toiminto").href + 
                    "\\" + _input.value;

                document.getElementById("filter_param").value = _input.value;
		    }

            var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
            Arr.forEach.call(tables, function(table) {
                Arr.forEach.call(table.tBodies, function(tbody) {
                    Arr.forEach.call(tbody.rows, _filter);
                    rows++;
                });
            });
            
            //Get the row count of the fitered table
            var rowCount = 0;
            var rows = document.getElementById("logtable").getElementsByTagName("tr");
            for (var i = 0; i < rows.length; i++) {
                if (rows[i].style.display == 'none') {
                	continue;
                }
                if (rows[i].getElementsByTagName("td").length > 0) {
                    rowCount++;
                }
            }         
            document.getElementById("tableRowCount").innerHTML = rowCount;
            
            //Zebra stripe the table
            var k = 0;
            var table = document.getElementById("logtable");
            for (var i = 0, row; row = table.rows[i]; i++) {
            	row = table.rows[i];
                if (!(row.style.display === 'none')) {
                	if (k % 2) {
                   		row.style.backgroundColor = "#eee";
                     } else  {
                   		row.style.backgroundColor = "white";
                    }
                    k++;
                }
            }         
  
        }

        function _filter(row) {
            var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
            row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
        }

        return {
            init: function() {
                var inputs = document.getElementsByClassName('light-table-filter');
                Arr.forEach.call(inputs, function(input) {
                    input.oninput = _onInputEvent;
                });
            }
        };
    })(Array.prototype);

    document.addEventListener('readystatechange', function() {
        if (document.readyState === 'complete') {
            LightTableFilter.init();
            document.getElementById("filterString").value = document.getElementById("filter_param").value;
            //alertTextInput();
            filterString.dispatchEvent(new Event("input"));
        }
    });

})(document);

