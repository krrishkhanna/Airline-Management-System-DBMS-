# Airline Management System

A simple web application for airline flight management using HTML, JavaScript, PHP, and MySQL.

## Features

- Connect to a MySQL database named `airline`
- Fetch and display flight records from the database
- Responsive design for both desktop and mobile
- Real-time statistics on flight statuses
- Clean and modern UI

## Setup Instructions

### Prerequisites

- XAMPP, WAMP, LAMP, or any PHP/MySQL server stack
- Web browser (Chrome, Firefox, Edge, etc.)

### Installation

1. Clone or download this repository to your web server's document root (e.g., `htdocs` folder for XAMPP)
   ```
   git clone <repository-url>
   ```
   Or create a new folder named `airline` in your `htdocs` directory and copy all the files there.

2. Start your Apache and MySQL services from your server control panel (e.g., XAMPP Control Panel)

3. Set up the database by opening the following URL in your browser:
   ```
   http://localhost/airline/setup_database.php
   ```
   This will create the necessary database and tables with sample data.

4. After successful setup, you can access the application:
   ```
   http://localhost/airline/
   ```

## File Structure

- `index.html` - Landing page
- `dashboard.html` - Main dashboard to display flight data
- `fetch_data.php` - PHP script to connect to the database and return JSON data
- `setup_database.php` - Script to initialize the database and sample data
- `README.md` - Documentation (this file)

## Database Structure

The application uses a MySQL database named `airline` with a table `flights` that has the following columns:
- `id` - Auto-incremented primary key
- `Name` - Airline name
- `Flight` - Flight number
- `Date` - Flight date
- `Status` - Flight status (On Time, Delayed, Cancelled)

## Technologies Used

- HTML5
- CSS3
- JavaScript (ES6+)
- PHP
- MySQL

## License

This project is open-source and available for personal and commercial use.

## Support

For any issues or questions, please contact the repository owner. 