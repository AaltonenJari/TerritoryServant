function createPDF() 
{
	//var sTable = document.getElementsByClassName('tableWrap')[0].innerHTML;
	var sTable = document.getElementById('content').innerHTML;
	
	var style = "<style>";
	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style = style + "h2 {padding:10px 10px 0px 40px; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 18px; color: #228; }";
	
 	style = style + ".coReportSubHdr { padding:10px 10px 0px 40px;  font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 18px; color: #228; }";
	style = style + ".coReportRow { height: 15px; padding:5px 5px 5px 100px; font-family: \"Arial\", Times, serif; }";
	style = style + ".coReportRow2 { height: 15px; padding:5px 5px 5px 130px; font-family: \"Arial\", Times, serif; }";

	style = style + ".tyhja_rivi { height: 15px; } ";
	
	style = style + "</style>";
	
	//CREATE A WINDOW OBJECT
	var win = window.open('', '', 'height=700,width=700');
	
	win.document.write('<!DOCTYPE html>');
	win.document.write('<html><head>');
	win.document.write(style);
	win.document.write('</head>');
	win.document.write('<body>');
	win.document.write(sTable);
	win.document.write('</body></html>');
	
	win.document.close();
	
	win.print();
}