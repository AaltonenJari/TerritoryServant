function createPDF() 
{
	//var sTable = document.getElementsByClassName('tableWrap')[0].innerHTML;
	var sTable = document.getElementById('content').innerHTML;

	var style = "<style>";
	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	
	style = style + "thead tr th {position: sticky; top: 0; }";
	style = style + "table {border-collapse: collapse; }";
	style = style + "th {text-align: center; }";

	style = style + "table tbody tr td {border: 1px solid black; border-collapse: collapse;	}";
	style = style + "table tbody tr { text-align: center; }";

	style = style + ".hdrtext { font-family: 'Arial', Times, serif; font-size: 8px; text-align: center; text-decoration: none; }";
	style = style + ".hdrnbr { font-family: 'Arial', Times, serif; font-size: 16px; text-align: left; text-decoration: underline; }"; 
	style = style + "</style>";
	
	//CREATE A WINDOW OBJECT
	var win = window.open('', '', 'height=700,width=700');
	
	win.document.write('<html><head>');
	win.document.write(style);
	win.document.write('</head>');
	win.document.write('<body>');
	win.document.write('<H1>Aluekorttiluettelo</H1>');
	win.document.write(sTable);
	win.document.write('</body></html>');
	
	win.document.close();
	
	win.print();
}