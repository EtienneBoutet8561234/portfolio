*create the database*

Run the following command:

    psql -U postgres

Once connected to your account create the database with the following commands:

    CREATE DATABASE hospital_db;

Then exit with \q

Then connect to the newly created database with the  following command:

    psql -U postgres -d hospital_db

Then add the 'patient' table into the database with the following command:

    CREATE TABLE patient (id SERIAL PRIMARY KEY,name VARCHAR(255) NOT NULL, code CHAR(3) UNIQUE NOT NULL,priority INTEGER CHECK (priority >= 1 AND priority <= 5),arrival_time TIMESTAMP NOT NULL);

OPTIONAL:Add 5 test users with following command:

INSERT INTO patient (name, code, priority, arrival_time) VALUES('John Doe', 'JDO', 3, '2024-03-26 08:00:00'),('Jane Smith', 'JSM', 5, '2024-03-26 08:30:00'),('Alex Johnson', 'ALJ', 1, '2024-03-26 09:00:00'),('Emily Davis', 'EMD', 2, '2024-03-26 09:30:00'),('Chris Brown', 'CHB', 4, '2024-03-26 10:00:00');

*How to start the php server*

Install PHP: Make sure PHP is installed on your computer. You can check by opening a terminal or command prompt and typing php -v.

Also, make sure the following two extension are uncommented in your php.ini file:

extension=pdo_pgsql
extension=pgsql

Start the Built-in Server: Navigate to the directory containing your index.php file in the terminal or command prompt. Run the following command: php -S localhost:8000 -c "C:\Program Files\PHP\php.ini"

To View Client interface: Open a browser and visit http://localhost:8000/index.php. 

To View Admin interface: Open a browser and visit http://localhost:8000/admin.php. 
