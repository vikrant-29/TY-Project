CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    lastName VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    userName VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    pass VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    email VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    phoneNumber VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL,
    gender VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
    dob DATE NOT NULL,
    maritalStatus VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL,
    resi_address VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    corr_address VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    aadharNo VARCHAR(12) COLLATE utf8mb4_general_ci NOT NULL,
    panNo VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
    photo VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    sign VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    documentType VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL,
    documentFile VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sec_que VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    que_ans VARCHAR(30) COLLATE utf8mb4_general_ci NOT NULL,
    login_attempts INT(11) DEFAULT 0,
    last_failed_attempt DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (userName),
    UNIQUE KEY (aadharNo),
    UNIQUE KEY (panNo)
);

CREATE TABLE accounts (
    acc_no INT(10) DEFAULT NULL,
    id INT(11) NOT NULL,
    account_type ENUM('Savings', 'Current', 'Fixed Deposit') COLLATE utf8mb4_general_ci NOT NULL,
    balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status ENUM('Active', 'Inactive', 'Closed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
    PRIMARY KEY (id),
    UNIQUE KEY (acc_no),
    CONSTRAINT fk_user_id FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE admin (
    id INT(11) NOT NULL,
    _role VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
    _name VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    username VARCHAR(30) COLLATE utf8mb4_general_ci NOT NULL,
    pass VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (username)
);

CREATE TABLE feedback(
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    feedback_text TEXT COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (id),
    KEY user_id (user_id)
);


CREATE TABLE transactions (
    id INT(11) NOT NULL AUTO_INCREMENT,
    sender_acc_no INT(10) NOT NULL,
    receiver_acc_no INT(10) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    transaction_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sender_id INT(11) NOT NULL,
    reciver_id INT(11) DEFAULT NULL,
    PRIMARY KEY (id),
    KEY fk_user (sender_id),
    KEY fk_sender (sender_acc_no),
    KEY fk_receiver (receiver_acc_no),
    CONSTRAINT fk_user FOREIGN KEY (sender_id) REFERENCES accounts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_sender FOREIGN KEY (sender_acc_no) REFERENCES accounts(acc_no) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_receiver FOREIGN KEY (receiver_acc_no) REFERENCES accounts(acc_no) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE investment_applications (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    firstName VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    lastName VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    userName VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    email VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    phoneNumber VARCHAR(15) COLLATE utf8mb4_general_ci NOT NULL,
    investmentType VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    investmentAmount DECIMAL(10,2) NOT NULL,
    investmentDuration INT(11) NOT NULL,
    riskAppetite VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    identityProof VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
    submissionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE loan_applications (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    firstName VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    lastName VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    userName VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    email VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    phoneNumber VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
    loanType VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    loanAmount DECIMAL(15,2) NOT NULL,
    loanPurpose TEXT COLLATE utf8mb4_general_ci NOT NULL,
    employmentStatus ENUM('Employed', 'Self-Employed', 'Unemployed') COLLATE utf8mb4_general_ci NOT NULL,
    annualIncome DECIMAL(15,2) NOT NULL,
    creditScore INT(11) NOT NULL,
    incomeProof VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    identityProof VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    submissionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approval_status ENUM('Pending', 'Approved', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending'
);