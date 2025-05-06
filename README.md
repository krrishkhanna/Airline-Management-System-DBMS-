
# ✈️ Airline Management System

A simple yet effective web application for managing airline flight records. Built using **HTML**, **PHP**, **JavaScript**, and **MySQL**, this project allows users to interact with a database of flights through a responsive web interface.

---

## 🚀 Features

- Add, update, and view flight details
- Fetch and display real-time flight records
- Responsive design for mobile and desktop
- Clean, modern UI
- Database connectivity using PHP (MySQLi)
- Flight logs and statistics

---

## 🛠️ Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Tools**: XAMPP / WAMP / LAMP for local server

---

## 🧰 Setup Instructions

### Prerequisites

- A PHP & MySQL stack like **XAMPP**, **WAMP**, or **LAMP**
- A web browser (Chrome, Firefox, etc.)

### Installation

1. Clone or download the project to your local server directory (`htdocs` if using XAMPP):
   ```bash
   git clone <repository-url>
````

2. Or manually extract this folder as `/airline` into `htdocs`.

3. Start **Apache** and **MySQL** from your server control panel.

4. Import the `airline` database:

   * Either visit `http://localhost/phpmyadmin` and import a `.sql` file (if provided)
   * Or create the database manually and use the `setup_database.php` script:

     ```bash
     http://localhost/airline/setup_database.php
     ```

---

## ▶️ Usage

* Visit the main dashboard:

  ```bash
  http://localhost/airline/index.html
  ```
* Use the dashboard to add flights, view logs, and manage airline data.
* PHP scripts handle data fetching, insertion, and log tracking.

---

## 📁 Folder Structure

```
airline/
├── add_flight.php
├── add_flight_form.html
├── dashboard.html
├── fetch_data.php
├── flight_cursor.php
├── get_logs.php
├── index.html
├── README.md
```

---

## 📌 Notes

* Ensure your MySQL database name is `airline` and credentials match in the PHP files.
* If you get connection errors, check your `mysqli_connect()` parameters.

---

## 📄 License

This project is open-source and available for educational use.

```


