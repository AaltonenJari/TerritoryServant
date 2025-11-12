function bookKeepingToPDF()
{
	var style = "<style>";
	
	style = style + "table {width: 100%; border-collapse: collapse; page-break-inside: avoid; }";
	style = style + ".table3Hdr {height: 50px; vertical-align: text-top; text-align: left; font-family: \"Arial\", Times, serif; font-size: 18px; }";
	style = style + ".table3HdrRow {text-align: center;}";
	style = style + ".table3body {font-family: sans-serif; font-size: 12px; }";
	style = style + ".table3 tbody tr {text-align: center; }";

	style = style + ".hdrtext { font-family: 'Arial', Times, serif; font-size: 8px; font-weight: 600; text-align: center; text-decoration: none; }";
	style = style + ".hdrnbr { font-family: 'Arial', Times, serif; font-size: 18px; font-weight: 700; text-align: left; padding-bottom: 2px; text-decoration: underline; }"; 

	style = style + ".event_nimi {border-color: black; border-style: solid; border-width: 3px 3px 1px 3px; border-collapse: collapse; }";
	style = style + ".event_lainattu {border-color: black; border-style: solid; border-width: 1px 1px 3px 3px; border-collapse: collapse; }";
	style = style + ".event_palautettu {border-color: black; border-style: solid; border-width: 1px 3px 3px 1px; border-collapse: collapse; }";
	style = style + ".tyhja_rivi {height: 18px; }";

	style = style + "</style>";


	tableToPDF("Tapahtumat", "Aluekorttiluettelo", style)
}

function tableToPDF(filename, tableHeader, style)
{
	var tableHTML = document.getElementById('content').innerHTML;

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
	win.document.write(tableHTML);
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
