# The Variety Hotel Booking Applications
  This repository contains the code for the Variety Hotel booking application.

## Table of Contents
* Installation
* Database Setup
* Running the application
* Testing the Application


## Installation
Clone this repository in your local machine:
* booking_process.php
* payment_process.php
* index.html
* HotelDB_Script.sql
* cityview.php
* deck.jpg
* doubleroom.jpg
* gallery.php
* hallway.jpg
* lobby.jpg
* lobby2.jpg
* room.jpg

## Database Setup
1.  Start the Apache and MySQL services in your XAMPP Control Panel.
2.  Open your web browser and navigate to http://localhost/phpmyadmin/
3.  Create a new database named Hotel_DB
4.  Import the HotelDB_Script.sql file located in the project root into the database you just created. This will create the necessary tables and schema.

## Running the application
1.  Open your Web browser
2.  Navigate to the application's homepage. The URL will typically be:
    * http://localhost/index.html (if you placed the folder directly in htdocs)
    * if placed in a folder type: http://localhost/your-folder-name/index.html

      Adjust the URL based on your project's location within the web server's document root.

## Testing the Application      

1.  **Browse the Website:** Navigate through the different pages (Home, Gallery, Bookings) to ensure the basic layout is working.
2.  **Make a Booking:**
    * Fill out the customer and booking information form.
    * Click "Book Now" (The small "Book Now").
    * Verify that the booking process completes without errors and (if implemented) that you are presented with a payment option.
    * Check your database (using phpMyAdmin) to confirm that the booking information has been added to the appropriate tables (Guest, Booking).
3.  **Attempt Payment (if applicable):** If you have a payment processing simulation, go through the payment steps. Verify that the payment information is (or would be) recorded in the database (Payment table).




