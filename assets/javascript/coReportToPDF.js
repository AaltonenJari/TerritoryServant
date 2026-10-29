function coReportToPDF() 
{
	var sTable = document.getElementById('content').innerHTML;
	
	var style = "<style>";
	
	style += ".statistic-area { margin: 20px 0; padding: 2px 20px; background-color: #ffffff; }";

	style += "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style += "h2 {padding:10px 10px 0px 40px; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 18px; color: #228; }";
	
    style += ".coReportSubHdr { padding:5px 10px 0px 40px;  font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 18px; color: #228; }";
	style += ".coReportRow { height: 15px; padding:5px 5px 5px 65px; font-family: \"Arial\", Times, serif; }";
	style += ".coReportRow2 { height: 15px; padding:5px 5px 5px 90px; font-family: \"Arial\", Times, serif; }";

	style += ".tyhja_rivi { height: 15px; } ";
	
	style += "</style>";
	
	//CREATE A WINDOW OBJECT
	var win = window.open('', '', 'height=700,width=700');
	
	win.document.write('<!DOCTYPE html>');
	win.document.write('<html><head>');
	win.document.write('<title>Raportti kierrosvalvojalle</title>');
	win.document.write(style);
	win.document.write('</head>');
	win.document.write('<body>');
	win.document.write(sTable);
	win.document.write('</body></html>');
	
	win.document.close();
	
	win.print();
}