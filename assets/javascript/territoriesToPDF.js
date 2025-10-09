function territoryListToPDF()
{
	var style = "<style>";
      	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style = style + "table { width: 100%; border-collapse: collapse; }";
	
	// Otsikko ja taulukko pysyvät samalla sivulla PDF:ssä ilman turhaa sivunvaihtoa
	style = style + `
	@media print {
	  h1, table {
	    page-break-before: avoid;
	    page-break-after: avoid;
	    page-break-inside: auto;
	    break-before: avoid;
	    break-after: avoid;
	    break-inside: auto;
	    margin: 0;
	    padding-bottom: 10px;
	  }
	}
	`;

	
	style = style + "th {padding: 10px; font-weight: bold; text-align: left; text-transform: uppercase; } ";
	style = style + "tr th:nth-child(1) { padding-left: 0px; width: 5%; } ";
	style = style + "tr th:nth-child(2), tr th:nth-child(3) { width: 20%; } ";
	style = style + "tr th:nth-child(4) { text-align: center; width: 5%; } ";
	style = style + "tr th:nth-child(5), tr th:nth-child(6) { text-align: center; width: 10%; } ";
	style = style + "tr th:nth-child(7) { padding-left: 0px; width: 25%; } ";
	
	style = style + "tr td:nth-child(1), tr td:nth-child(4) {padding-left: 0px; text-align: center; }";
	style = style + "tr td:nth-child(2), tr td:nth-child(3) {padding-left: 10px; }";
	style = style + "tr td:nth-child(5), tr td:nth-child(6) {text-align: right; padding-right: 10px; } ";
	style = style + "tr td:nth-child(7) {padding-left: 0px; }";
	
	style = style + "</style>";

	tableToPDF("Aluelista", "Alueluettelo", style)
}

function territoryMaintenanceToPDF()
{
	var style = "<style>";
      	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style = style + "table { width: 100%; border-collapse: collapse; }";
	
	// Otsikko ja taulukko pysyvät samalla sivulla PDF:ssä ilman turhaa sivunvaihtoa
	style = style + `
	@media print {
	  h1, table {
	    page-break-before: avoid;
	    page-break-after: avoid;
	    page-break-inside: auto;
	    break-before: avoid;
	    break-after: avoid;
	    break-inside: auto;
	    margin: 0;
   	    padding-bottom: 10px;
	  }
	}
	`;

	
	style = style + "th {padding: 10px; font-weight: bold; text-align: left; text-transform: uppercase; } ";
	style = style + "tr th:nth-child(1) { padding-left: 0px; } ";
	style = style + "tr th:nth-child(4), tr th:nth-child(5) {padding-left: 0px; text-align: center; } ";

	style = style + "tr td:nth-child(1) {padding-left: 0px; text-align: center; }";
	style = style + "tr td:nth-child(2), tr td:nth-child(3) {padding-left: 10px; }";
	style = style + "tr td:nth-child(4), tr td:nth-child(5) {padding-left: 0px; text-align: center; } ";
	
	style = style + ` 
	.alue_detail, .alue_location {
		  padding-left: 6px;
		  border-width: 0px;
		  background-color: inherit;
		}
	`;
	
	style = style + ` 
	.alue_taloudet  {
		  padding-left: 0px;
		  border-width: 0px;
		  text-align: center;
		  background-color: inherit;
		}
	`;
	
	style = style + "</style>";
	
	tableToPDF("Alueet", "Alueluettelo", style)
}

function personMaintenanceToPDF()
{
	var style = "<style>";
      	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style = style + "table { width: 100%; border-collapse: collapse; }";
	
	// Otsikko ja taulukko pysyvät samalla sivulla PDF:ssä ilman turhaa sivunvaihtoa
	style = style + `
	@media print {
	  h1, table {
	    page-break-before: avoid;
	    page-break-after: avoid;
	    page-break-inside: auto;
	    break-before: avoid;
	    break-after: avoid;
	    break-inside: auto;
	    margin: 0;
   	    padding-bottom: 10px;
	  }
	}
	`;
	
	style = style + "th {padding: 10px; font-weight: bold; text-align: left; text-transform: uppercase; } ";

	style = style + "tr th:nth-child(1) { padding-left: 0px; } ";
	style = style + "tr th:nth-child(4), tr th:nth-child(5) {padding-left: 6px; } ";
	style = style + "tr th:nth-child(6), tr th:nth-child(7) {padding-left: 0px; text-align: center; } ";

	style = style + "tr td:nth-child(1) {padding-left: 0px; text-align: center; }";
	style = style + "tr td:nth-child(2), tr td:nth-child(3) {padding-left: 10px; }";
	style = style + "tr td:nth-child(6), tr td:nth-child(7) {padding-left: 0px; text-align: center; } ";
	
	style = style + ` 
	.person_name, .person_lastname {
		  padding-left: 6px;
		  border-width: 0px;
		  background-color: inherit;
		}
	`;
	
	style = style + ` 
	.person_show  {
		  padding-left: 0px;
		  border-width: 0px;
		  text-align: center;
		  background-color: inherit;
		}
	`;

	style += `
		.group_select_input {
		  padding-left: 6px;
		  border: none;
		  background: transparent;
		  -webkit-appearance: none; /* Chrome, Safari */
		  -moz-appearance: none;    /* Firefox */
		  appearance: none;
		  pointer-events: none; /* Estää valinnan tulostuksessa */
		}
		@media print {
		  .group_select_input {
		    background: transparent !important;
		    -webkit-appearance: none !important;
		    -moz-appearance: none !important;
		    appearance: none !important;
		    color: inherit;
		  }
		}
		`;
	
	style += `
		.group_overseer_select_input {
		  padding-left: 6px;
		  border: none;
		  background: transparent;
		  -webkit-appearance: none; /* Chrome, Safari */
		  -moz-appearance: none;    /* Firefox */
		  appearance: none;
		  pointer-events: none; /* Estää valinnan tulostuksessa */
		}
		@media print {
		  .group_overseer_select_input {
		    background: transparent !important;
		    -webkit-appearance: none !important;
		    -moz-appearance: none !important;
		    appearance: none !important;
		    color: inherit;
		  }
		}
		`;

	style = style + ` 
	.group_select_input, .group_overseer_select_input {
		  padding-left: 6px;
		  border-width: 0px;
		}
	`;

	style = style + "</style>";

	tableToPDF("Henkilöt", "Henkilöluettelo", style)
}

function groupMaintenanceToPDF()
{
	var style = "<style>";
  	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style = style + "table { width: 100%; border-collapse: collapse; }";
	
	// Otsikko ja taulukko pysyvät samalla sivulla PDF:ssä ilman turhaa sivunvaihtoa
	style = style + `
	@media print {
	  h1, table {
	    page-break-before: avoid;
	    page-break-after: avoid;
	    page-break-inside: auto;
	    break-before: avoid;
	    break-after: avoid;
	    break-inside: auto;
	    margin: 0;
   	    padding-bottom: 10px;
	  }
	}
	`;

	
	style = style + "th {padding: 10px; font-weight: bold; text-align: left; text-transform: uppercase; } ";
	style = style + "tr th:nth-child(1) { padding-left: 0px; } ";
	style = style + "tr th:nth-child(4) {padding-left: 0px; text-align: center; } ";

	style = style + "tr td:nth-child(1) {padding-left: 0px; text-align: center; }";
	style = style + "tr td:nth-child(2), tr td:nth-child(3) {padding-left: 10px; }";
	style = style + "tr td:nth-child(4) {padding-left: 0px; text-align: center; } ";
	
	style = style + ` 
	.group_name, .group_events {
		  padding-left: 6px;
		  border-width: 0px;
		  background-color: inherit;
		}
	`;
	
	style = style + "</style>";
	
	tableToPDF("Ryhmälista", "Ryhmälista", style)
}

function userMaintenanceToPDF()
{
	var style = "<style>";
      	
	style = style + "h1 {text-align: center; font-family: \"Arial\", Times, serif; font-size: 26px; }";
	style = style + "table { width: 100%; border-collapse: collapse; }";
	
	// Otsikko ja taulukko pysyvät samalla sivulla PDF:ssä ilman turhaa sivunvaihtoa
	style = style + `
	@media print {
	  h1, table {
	    page-break-before: avoid;
	    page-break-after: avoid;
	    page-break-inside: auto;
	    break-before: avoid;
	    break-after: avoid;
	    break-inside: auto;
	    margin: 0;
   	    padding-bottom: 10px;
	  }
	}
	`;
	
	style = style + "th {padding: 10px; font-weight: bold; text-align: left; text-transform: uppercase; } ";

	style = style + "tr th:nth-child(1), tr th:nth-child(6) { padding-left: 0px; } ";
	style = style + "tr th:nth-child(7) {padding-left: 0px; text-align: center; } ";

	style = style + "tr td:nth-child(1) {padding-left: 0px; text-align: center; }";
	style = style + "tr td:nth-child(2), tr td:nth-child(3), tr td:nth-child(4), tr td:nth-child(5) {padding-left: 10px; }";
	style = style + "tr td:nth-child(7) {padding-left: 0px; text-align: center; } ";
	
	style = style + ` 
	.user_username, .user_firstname, .user_lastname, .user_email {
		  padding-left: 6px;
		  border-width: 0px;
		  background-color: inherit;
		}
	`;

	style += `
		.dropdown_field {
		  padding-left: 6px;
		  border: none;
		  background: transparent;
		  -webkit-appearance: none; /* Chrome, Safari */
		  -moz-appearance: none;    /* Firefox */
		  appearance: none;
		  pointer-events: none; /* Estää valinnan tulostuksessa */
		}
		@media print {
		  .group_select_input {
		    background: transparent !important;
		    -webkit-appearance: none !important;
		    -moz-appearance: none !important;
		    appearance: none !important;
		    color: inherit;
		  }
		}
		`;
	
	style = style + ` 
	.group_select_input, .group_overseer_select_input {
		  padding-left: 6px;
		  border-width: 0px;
		}
	`;

	style = style + "</style>";

	tableToPDF("Käyttäjät", "Käyttäjäluettelo", style)
}

function tableToPDF(filename, tableHeader, style)
{
	var tableHTML = document.querySelector('#content table.table').outerHTML;
	var totalCount = document.getElementById('totalcount') ? document.getElementById('totalcount').outerHTML : '';

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
	win.document.write('<H1>');
	win.document.write(tableHeader);
	win.document.write('</H1>');
	win.document.write(tableHTML);
	win.document.write('</br>');
	win.document.write(totalCount);
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
