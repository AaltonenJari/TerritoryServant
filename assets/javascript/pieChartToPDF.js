function pieChartToPDF() 
{
	var sContent = document.getElementById('content').innerHTML;

	var style = "<style>";

	style += ".statistic-area { margin: 20px 0; padding: 2px 20px; background-color: #ffffff; }";
	
    style += "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";

    style += ".pie-chart-title { margin: 5px 0 5px 0; color: #294451; font-size: 26px; font-weight: 600; text-align: center; }";
    style += ".pie-chart-area {  display: flex; align-items: center; justify-content: center; gap: 30px; padding: 20px; }";
    /* Piirakkakuvio */
    style += ".pie-chart { width: 250px; height: 250px; flex-shrink: 0; border-radius: 50%; border: 1px solid #d3e6ef; box-shadow: 0 2px 6px rgba(42, 87, 110, 0.12); }";

    /* Selite */
    style += ".pie-chart-legend { display: flex; flex-direction: column; gap: 9px; min-width: 0; }";
    style += ".pie-chart-legend-item { display: grid; grid-template-columns: 14px minmax(150px, 1fr) 55px 65px; align-items: center; gap: 8px; font-size: 20px; color: #294451; }";
    style += ".pie-chart-color { width: 12px; height: 12px; border-radius: 3px; }";
    style += ".pie-chart-name { min-width: 0; }";
    style += ".pie-chart-count, .pie-chart-percentage { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }";
    style += ".pie-chart-percentage { font-weight: 600; }";

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