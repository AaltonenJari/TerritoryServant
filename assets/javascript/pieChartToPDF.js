function pieChartToPDF() 
{
	var sContent = document.getElementById('content').innerHTML;

	var style = "<style>";

	style += ".statistic-area { margin: 20px 0; padding: 2px 20px; background-color: #ffffff; }";
	
    style += "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";

    style += ".pie-chart-title { margin: 5px 0 5px 0; color: #294451; font-size: 26px; font-weight: 600; text-align: center; }";
    style += ".pie-chart-area {  display: flex; align-items: center; justify-content: center; padding: 20px; }";
    /* Piirakkakuvio */
    style += ".pie-chart { display: block; width: 250px; height: 250px; flex-shrink: 0; overflow: visible; filter: drop-shadow(0 2px 4px rgba(42, 87, 110, 0.12)); } ";
    /* Piirakan ja selitteen väli */
    style += ".pie-chart-legend { display: flex; flex-direction: column; min-width: 0; margin-left: 30px; } ";
    style += ".pie-chart-legend-item { display: flex; align-items: center; margin-bottom: 9px; font-size: 20px; color: #294451; } ";
    style += ".pie-chart-color { display: inline-block; width: 12px; height: 12px; flex: 0 0 12px; margin-right: 8px; border-radius: 3px; } ";
    style += ".pie-chart-name { flex: 1 1 auto; min-width: 150px; margin-right: 8px; } ";
    style += ".pie-chart-count { flex: 0 0 65px; text-align: right; margin-right: 8px; } ";
    style += ".pie-chart-percentage { flex: 0 0 75px; text-align: right; font-weight: 600; } ";
    style += ".pie-chart-count, .pie-chart-percentage { white-space: nowrap; font-variant-numeric: tabular-nums; } ";

	style += "</style>";

	contentToPDF("Piirakkakuvio", style)
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