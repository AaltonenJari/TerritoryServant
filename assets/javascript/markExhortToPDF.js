function createPDF() 
{
	//var sTable = document.getElementsByClassName('tableWrap')[0].innerHTML;
	var sTable = document.getElementById('content').innerHTML;

	var style = "<style>";
	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	
	style = style + "thead tr th {position: sticky; top: 0; }";
	style = style + "table { width: 100%; border-collapse: collapse; page-break-inside: avoid; }";

	
	style = style + ".tyhja_rivi { height: 15px; } ";
	style = style + ".kehotus_selite { height: 25px; padding:8px 15px 15px 20px; font-family: \"Arial\", Times, serif; font-size: 10px; }";
	style = style + ".julistaja { font-family: \"Arial\", Times, serif; font-size: 18px; font-weight: bold; } ";
	style = style + ".ohjeteksti { padding-left: 30px; } ";
	style = style + ".otsikko_numero { font-weight: bold;  text-align: center; width: 10%; } ";
	style = style + ".otsikko_nimi { padding-left: 15px; font-weight: bold; width: 50%; } ";
	style = style + ".otsikko_kayty, .otsikko_otettu { text-align: right; font-weight: bold; padding-right: 15px; width: 20%; } ";
	style = style + ".alue_number { text-align: center; } ";
	style = style + ".alue_name { padding-left: 15px; } ";
	style = style + ".alue_lastdate, .event_date { text-align: right;  padding-right: 15px;	} ";
	
	style = style + "</style>";
	
	//CREATE A WINDOW OBJECT
	var win = window.open('', '', 'height=700,width=700');
	
	win.document.write('<!DOCTYPE html>');
	win.document.write('<html><head>');
	win.document.write(style);
	win.document.write('</head>');
	win.document.write('<body>');
	win.document.write('<H1>Merkitsemiskehotuslista</H1>');
	win.document.write(sTable);
	win.document.write('</body></html>');
	
	win.document.close();
	
	win.print();
}