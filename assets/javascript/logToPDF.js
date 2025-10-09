function logToPDF()
{
	var style = "<style>";
      	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style = style + "table { width: 100%; border-collapse: collapse; }";
	
	// Otsikko ja taulukko pysyvät samalla sivulla PDF:ssä ilman turhaa sivunvaihtoa
	style = style + `
	@media print {
	  h1, table {
	    page-break-before: avoid;
	    page-break-after: avoid;
	    page-break-inside: auto;
	    break-before: avoid;
	    break-after: avoid;
	    break-inside: auto;
	    margin: 0;
	    padding-bottom: 10px;
	  }
	}
	`;

	
	style = style + "th {padding: 10px; font-weight: bold; text-align: left; text-transform: uppercase; } ";
	
	style = style + "tr th:nth-child(1) { padding-left: 0px; } ";
	style = style + "tr th:nth-child(4), tr th:nth-child(8) { padding-right: 0px; text-align: right; } ";
	style = style + "tr th:nth-child(6), tr th:nth-child(7) {padding-left: 0px; }";
	style = style + "tr th:nth-child(9) {text-align: center; } ";

	style = style + "tr td:nth-child(1) {padding-left: 0px; text-align: center; }";
	style = style + "tr td:nth-child(2), tr td:nth-child(3), tr td:nth-child(5) { text-align: center; } ";
	style = style + "tr td:nth-child(4), tr td:nth-child(8) { text-align: right; } ";
	style = style + "tr td:nth-child(6), tr td:nth-child(7) {padding-left: 6px; }";
	style = style + "tr td:nth-child(9) {padding-left: 0px; text-align: center; } ";
	
	style = style + "</style>";

	tableToPDF("Loki", "Lokitapahtumat", style)
}

function tableToPDF(filename, tableHeader, style)
{
	var tableHTML = document.querySelector('#content table.table').outerHTML;
	var totalCount = document.getElementById('totalcount') ? document.getElementById('totalcount').outerHTML : '';

	//Luo uusi ikkuna tulostusta varten
	var win = window.open('', '', 'height=700,width=700');
	
	win.document.write('<!DOCTYPE html>');
	win.document.write('<html><head>');
	win.document.write('<title>');
	win.document.write(filename);
	win.document.write('</title>');
	win.document.write(style);
	win.document.write('</head>');
	win.document.write('<body>');
	win.document.write('<H1>');
	win.document.write(tableHeader);
	win.document.write('</H1>');
	win.document.write(tableHTML);
	win.document.write('</br>');
	win.document.write(totalCount);
	win.document.write('</body></html>');
	
	win.document.close();
	
	// poista href kokonaan tulostettavasta dokumentista
	var links = win.document.getElementsByTagName('a');
	for (var i = 0; i < links.length; i++) {
	  links[i].removeAttribute('href');
	  links[i].style.cursor = 'default';
	}

	win.print();
}
