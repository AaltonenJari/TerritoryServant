function exhortListToPDF(filename)
{
	var style = "<style>";
	
	style += "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style += ".statistic-area { margin: 20px 0; padding: 2px 20px; background-color: #ffffff; }";
	
	style += "thead tr th {position: sticky; top: 0; }";
	style += "table { width: 100%; border-collapse: collapse; page-break-inside: avoid; }";

	
	style += ".tyhja_rivi { height: 15px; } ";
	style += ".kehotus_selite { height: 25px; padding:8px 5px 5px 30px;   font-family: \"Arial\", Times, serif;  font-size: 12px; }";
	style += ".julistaja { padding-left: 20px; padding-top: 20px; font-family: \"Arial\", Times, serif; font-size: 18px;  font-weight: bold; } ";
	style += ".ohjeteksti { padding-left: 30px; } ";
	style += ".otsikko_numero { padding-left: 45px; font-weight: bold; text-align: left; width: 20%; } ";
	style += ".otsikko_nimi { font-weight: bold; width: 40%; } ";
	style += ".otsikko_kayty, .otsikko_otettu { text-align: right; font-weight: bold; padding-right: 15px; width: 20%; } ";
	style += ".alue_number { padding-left: 45px;	text-align: left; width: 20%; } ";
	style += ".alue_name { text-align: left; width: 40%; } ";
	style += ".alue_lastdate, .event_last_date { text-align: right; padding-right: 15px;	width: 20%; } ";
	style += "</style>";

	contentToPDF(filename, style)
}

function contentToPDF(filename, style)
{
    var sContent = document.getElementById('content').innerHTML;
  
    //CREATE A WINDOW OBJECT
    var win = window.open('', '', 'height=700,width=700');

    win.document.write('<!DOCTYPE html>');
    win.document.write('<html><head>');
	win.document.write('<title>');
	win.document.write(filename);
	win.document.write('</title>');
    win.document.write(style);
    win.document.write('</head>');
    win.document.write('<body>');
    win.document.write(sContent);
    win.document.write('</body></html>');

    win.document.close();

    win.print();
}