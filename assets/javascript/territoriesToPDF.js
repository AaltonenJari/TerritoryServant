function createPDF() 
{
	//var sTable = document.getElementsByClassName('tableWrap')[0].innerHTML;
	var sTable = document.getElementById('content').innerHTML;

	var style = "<style>";
	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	
	style = style + "thead tr th {position: sticky; top: 0; }";
	style = style + "table { border-collapse: collapse; }";

	style = style + "table, td, th { border: 1px solid black;} ";
	
	style = style + "th {padding: 10px; text-align: left; text-transform: uppercase; " +
			/* " border-left: 2px solid black; border-bottom: 2px solid black;	0px 0px 0 2px #e8e8e8;}"; */
	        /* " background: white; }"; */
            " }";
	
	/* style = style + "table tbody tr td {border: 1px solid black; border-collapse: collapse;	}"; */

	/* style = style + "td, th { border: 2px solid black } "; */
	
	style = style + "tr td:nth-child(1), tr th:nth-child(1), tr td:nth-child(4), tr th:nth-child(4) { text-align: center; } ";
	style = style + "tr td:nth-child(5), tr th:nth-child(5) {text-align: right;	} ";
	
	style = style + "tr td:nth-child(2), tr td:nth-child(3), tr td:nth-child(5), tr td:nth-child(6) {padding: 10px;	} ";
	
	style = style + "a { text-decoration: none !important; color: black; }";
	
	style = style + "</style>";

	var totalCount = document.getElementById('totalcount').innerHTML;
	
	//CREATE A WINDOW OBJECT
	var win = window.open('', '', 'height=700,width=700');
	
	win.document.write('<!DOCTYPE html>');
	win.document.write('<html><head>');
	win.document.write(style);
	win.document.write('</head>');
	win.document.write('<body>');
	win.document.write('<H1>Alueluettelo</H1>');
	win.document.write(sTable);
	win.document.write('</br>');
	win.document.write(totalCount);
	win.document.write('</body></html>');
	
	win.document.close();
	
	win.print();
}