CREATE TABLE account (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(9) NOT NULL,
    fullname VARCHAR(50) NOT NULL,
    dob VARCHAR(9) NOT NULL,
    gender INTEGER CHECK (gender IN (0, 1, 2)) NOT NULL,
    email VARCHAR(50) NOT NULL,
    mobile VARCHAR(10) NOT NULL,
    address VARCHAR(200) NOT NULL,
    state CHAR(2) NOT NULL,
    city VARCHAR(20) NOT NULL,
    permission INTEGER CHECK (permission IN (0, 1)) NOT NULL
);

INSERT INTO account (username, password, fullname, dob, gender, email, mobile, address, state, city, permission) VALUES
('adminUser', 'admin123', 'Admin Name', '1970-01-01', 1, 'admin@example.com', '1234567890', '123 Admin Address', 'TX', 'AdminCity', 1),
('johnDoe', 'jdoe12345', 'John Doe', '1990-02-15', 1, 'johndoe@example.com', '2345678901', '456 John Street', 'CA', 'DoeCity', 0),
('janeDoe', 'jdoe12345', 'Jane Doe', '1992-03-22', 2, 'janedoe@example.com', '3456789012', '789 Jane Lane', 'NY', 'DoeCity', 0),
('alexSmith', 'asmith123', 'Alex Smith', '1988-07-30', 0, 'alexsmith@example.com', '4567890123', '101 Alex Boulevard', 'FL', 'SmithCity', 0);