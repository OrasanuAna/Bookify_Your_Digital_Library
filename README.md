# Bookify - Your Digital Library 📚

**Bookify** is a digital library platform that allows admins to manage a collection of books (add, update, delete), while users can browse and download available titles. Built with PHP and MySQL, Bookify provides a streamlined experience for accessing and organizing a digital book collection.

## Features

- **Admin Panel**: Admins can add, update, and delete books.
- **User Access**: Non-admin users can view and download books from the collection.
- **Secure Access**: Differentiated functionalities for admins and general users.
- **Easy Management**: Simple CRUD operations for efficient library management.

## Tech Stack

- **PHP**: Backend functionality
- **MySQL**: Database management
- **HTML/CSS**: Frontend structure and styling

## Getting Started

### Prerequisites

- **PHP** (version 7.0 or higher)
- **MySQL** database
- **Web Server** (Apache or Nginx recommended)

### Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/OrasanuAna/Bookify-Your-Digital-Library.git
   ```
2. **Navigate to the Project Directory**:
   ```bash
   cd Bookify-Your-Digital-Library
   ```
3. **Set Up the Database**:
   - Import the SQL file (e.g., `bookify_database.sql`) in your MySQL database.
   - Configure database credentials in `config.php`.

4. **Start the Server**:
   - Use a local server environment (e.g., XAMPP, WAMP) or start the Apache/Nginx server.
   - Access the project in your browser at `http://localhost/Bookify-Your-Digital-Library`.

## Usage

- **Admin Login**: Admins have access to manage the book collection, including adding, editing, and deleting entries.
- **User Access**: Regular users can view and download books but do not have management privileges.

## Folder Structure

- **config.php**: Database configuration
- **admin/**: Admin-specific files for managing books
- **user/**: User interface for browsing and downloading books
