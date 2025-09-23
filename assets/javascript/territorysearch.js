(function(document) {
    'use strict';

    var LightTableFilter = (function(Arr) {

        var _input;
		
        function _onInputEvent(e) {
            _input = e.target;
            document.getElementById("numero").href = document.getElementById("numeroold").value +
         	    "\\" + document.getElementById("selChkBoxOld").value + 
           	    "\\" + document.getElementById("selDateOld").value +
           	    "\\" + document.getElementById("selCodeOld").value;

            document.getElementById("alue_nimi").href = document.getElementById("alue_nimiold").value +
        	    "\\" + document.getElementById("selChkBoxOld").value + 
           	    "\\" + document.getElementById("selDateOld").value +
           	    "\\" + document.getElementById("selCodeOld").value;

           	document.getElementById("lisätieto").href = document.getElementById("lisätietoold").value +
          	    "\\" + document.getElementById("selChkBoxOld").value + 
           	    "\\" + document.getElementById("selDateOld").value +
           	    "\\" + document.getElementById("selCodeOld").value;
            
           	document.getElementById("lainassa").href = document.getElementById("lainassaold").value +
        	    "\\" + document.getElementById("selChkBoxOld").value + 
           	    "\\" + document.getElementById("selDateOld").value +
           	    "\\" + document.getElementById("selCodeOld").value;

           	document.getElementById("käyty").href = document.getElementById("käytyold").value +
        	    "\\" + document.getElementById("selChkBoxOld").value + 
           	    "\\" + document.getElementById("selDateOld").value +
           	    "\\" + document.getElementById("selCodeOld").value;

           	document.getElementById("otettu").href = document.getElementById("otettuold").value +
        	    "\\" + document.getElementById("selChkBoxOld").value + 
           	    "\\" + document.getElementById("selDateOld").value +
           	    "\\" + document.getElementById("selCodeOld").value;

           	document.getElementById("kenellä").href = document.getElementById("kenelläold").value +
    	       "\\" + document.getElementById("selChkBoxOld").value + 
           	    "\\" + document.getElementById("selDateOld").value +
           	    "\\" + document.getElementById("selCodeOld").value;
            
            if (_input.value != "") {
                document.getElementById("numero").href =
                	document.getElementById("numero").href + 
                   	"\\" + _input.value;
                document.getElementById("alue_nimi").href =
                	document.getElementById("alue_nimi").href + 
                	"\\" + _input.value;
                document.getElementById("lisätieto").href =
                	document.getElementById("lisätieto").href + 
                	"\\" + _input.value;
                document.getElementById("lainassa").href =
                    document.getElementById("lainassa").href + 
                    "\\" + _input.value;
                document.getElementById("käyty").href =
                    document.getElementById("käyty").href + 
                    "\\" + _input.value;
                document.getElementById("otettu").href =
                    document.getElementById("otettu").href + 
                    "\\" + _input.value;
                document.getElementById("kenellä").href =
                    document.getElementById("kenellä").href + 
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
            var rows = document.getElementById("table2").getElementsByTagName("tr");
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
            var table = document.getElementById("table2");
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

    document.addEventListener('readystatechange', function () {
    	  if (document.readyState === 'complete') {
    	    LightTableFilter.init();

    	    const paramEl = document.getElementById('filter_param');
    	    const filterEl = document.getElementById('filterString');

    	    if (!paramEl) {
    	      console.warn('#filter_param puuttuu DOMista.');
    	      return;
    	    }
    	    if (!filterEl) {
    	      console.warn('#filterString puuttuu DOMista.');
    	      return;
    	    }

    	    filterEl.value = paramEl.value || '';
    	    // input-tapahtuma käyttäen bubbling-ominaisuutta, jos joku kuuntelee ylhäältä
    	    filterEl.dispatchEvent(new Event('input', { bubbles: true, cancelable: true }));
        }
    });

})(document);

