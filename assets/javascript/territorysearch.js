(function(document) {
    'use strict';

    var LightTableFilter = (function(Arr) {

        var _input;
		
        function _onInputEvent(e) {
            const input = e.target;
            const val = input.value.toLowerCase();

            const tables = document.getElementsByClassName(input.getAttribute('data-table'));

            let rowCount = 0;

            Array.prototype.forEach.call(tables, function(table) {
                const rows = table.tBodies[0].rows;

                Array.prototype.forEach.call(rows, function(row) {
                    const text = row.textContent.toLowerCase();
                    const match = text.indexOf(val) !== -1;

                    row.style.display = match ? '' : 'none';

                    if (match) rowCount++;
                });
            });

            document.getElementById("tableRowCount").innerHTML = rowCount;

            zebraStripe();
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

function zebraStripe() {
    let k = 0;

    const rows = document.querySelectorAll('#table2 tbody tr');

    rows.forEach(row => {
        if (row.style.display === 'none') return;

        row.style.backgroundColor = (k % 2) ? "#eee" : "white";
        k++;
    });
}

