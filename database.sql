<<<<<<< HEAD
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    userName VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(15) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    dob DATE NOT NULL,
    maritalStatus VARCHAR(15) NOT NULL,
    resi_address VARCHAR(255) NOT NULL,
    corr_address VARCHAR(255) NOT NULL,
    aadharNo VARCHAR(12) NOT NULL UNIQUE,
    panNo VARCHAR(10) NOT NULL UNIQUE,
    photo VARCHAR(255) NOT NULL,
    sign VARCHAR(255) NOT NULL,
    documentType VARCHAR(15) NOT NULL,
    documentFile VARCHAR(255) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
=======
aditya can you see thise changes 
>>>>>>> 5c089da6eda172063d75fee69d96e7d240bb1a7d
