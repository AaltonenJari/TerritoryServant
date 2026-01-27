# TerritoryServant
TerritoryServant is a card-based system for managing territories or task areas represented by cards. It can be used for sales territories, distribution areas, or property maintenance tracking.

# Version 1.x

Version 1.x provides core territory card management. The main view displays a sortable and filterable list of cards, showing key information such as card number, description, last assignment date, last completion date, and current holder. Cards can be borrowed and returned via a simple dialog, and visible data can be exported to PDF reports.

From the main view, users can also generate follow-up reports. These reports identify cards that have not been handled for over a year or cards that have been borrowed for more than four months.

Cards and persons can be created, updated, and deleted through dedicated views that also support sorting, filtering, and PDF export. Event data is automatically updated whenever cards are borrowed or returned. The database consists of tables for cards, persons, and events.

The original version was completed in 2020. The production system runs in a LAMP environment, with development done on Windows using WAMP. The application is built using PHP, MySQL, JavaScript, HTML/CSS, and the CodeIgniter framework.

# Version 2.x

Version 2.x focuses on refactoring and usability improvements. The user interface was refreshed, PDF reports were visually improved, and several bugs were fixed. Logical deletion (soft delete) was added for cards, along with tools to remove outdated event and personal data.
