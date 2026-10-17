function statisticToPDF() 
{
	var sContent = document.getElementById('content').innerHTML;

	var style = "<style>";

	style += ".statistic-area { margin: 20px 0; padding: 2px 20px; background-color: #f1fbff; }";
	
    style += "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";

	style += ".statistic-row { margin-top: 10px; margin-bottom: 10px; }";
	style += ".statistic-title { font-weight: bold; margin-bottom: 5px; color: #000000; }";

	style += ".statistic-bar { box-sizing: border-box; min-height: 28px; margin-bottom: 4px; padding: 5px 8px; color: #ffffff !important; font-size: 14px; line-height: 18px; white-space: nowrap; }";

	style += ".uncovered-bar { background-color: #d93025 !important; color: #ffffff !important; }";
	style += ".total-bar { background-color: #258739 !important; color: #ffffff !important;  }";

	style += ".statistic-bar-row { margin-bottom: 4px; }";
	style += ".statistic-bar-row .statistic-bar { display: inline-block; vertical-align: middle; }";
	style += ".bar-text-inside { color: #ffffff !important; }";
	style += ".bar-text-outside { color: #000000 !important; white-space: nowrap; }";

	style += "</style>";

	
	//CREATE A WINDOW OBJECT
	var win = window.open('', '', 'height=700,width=700');
	
	win.document.write('<!DOCTYPE html>');
	win.document.write('<html><head>');
	win.document.write('<title>Tilastotietoa</title>');
	win.document.write(style);
	win.document.write('</head>');
	win.document.write('<body>');
	win.document.write(sContent);
	win.document.write('</body></html>');
	
	win.document.close();
	
	win.print();
}