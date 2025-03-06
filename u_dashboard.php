<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_nm'])) {
    header("Location: login.php");
    exit();
}
include('includes/connect.php');
include('includes/header.html');

$user_id = $_SESSION['id'];
$profile_img = $_SESSION['photo'];

// Fetch user account information (account number, balance, email, mobile number, etc.)
$sql = "SELECT a.acc_no, a.balance, u.firstName, u.lastName ,u.email, u.phoneNumber
        FROM users u
        JOIN accounts a ON u.id = a.id
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($account_number, $balance, $name, $lnm, $email, $mobile_number);
if ($stmt->fetch()) {
    $_SESSION['balance'] = $balance;
    $_SESSION['acc_no'] = $account_number;
    $_SESSION['email'] = $email;
    $_SESSION['mob'] = $mobile_number;
    $_SESSION['nm'] = $name;
    $_SESSION['lastName'] = $lnm;
}

$stmt->close();

// Check if user has account number
if ($account_number == NULL) {
    $account_pending = true;
    $_SESSION['ac_status'] = 1;
} else {
    $account_pending = false;
    $_SESSION['ac_status'] = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Bank Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/u_dashboard.css">
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="bank_img/logo_fade.jpeg" alt="Bank Logo">
            <span>HORIZON BANK</span>
        </div>
        <div class="menu">
            <a href="u_dashboard.php"><i class="fas fa-home"></i> Home</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="account_statement.php"><i class="fas fa-file-alt"></i> Account Statement</a>
            <a href="transaction.php"><i class="fas fa-exchange-alt"></i> Fund Transfer</a>
            <a href="feedback.php"><i class="fas fa-comments"></i> Feedback/Complaints</a>
            <a href="loan.php"><i class="fas fa-hand-holding-usd"></i> Loan</a>
            <a href="invest.php"><i class="fas fa-chart-line"></i> Investment</a>
        </div>
        <div class="right">
            <span class="balance"><i class="fas fa-wallet"></i> Balance: ₹<?php echo number_format($balance, 2); ?></span>
            <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- User Dashboard Content -->
    <div id="home">
        <div class="content">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_nm']); ?> <i class="fas fa-smile"></i></h2>

            <?php if ($account_pending): ?>
                <p><i class="fas fa-hourglass-half"></i> Your account is pending approval. Please wait for admin approval before you can perform any transactions.</p>
            <?php else: ?>
                <p><i class="fas fa-credit-card"></i> Your account number is: <?php echo htmlspecialchars($account_number); ?></p>
                <p><a href="account_statement.php"><i class="fas fa-file-alt"></i> Check Transactions History</a></p>
                <p><a href="invest.php"><i class="fas fa-chart-line"></i> Go to Investment</a></p>
                <p><a href="loan.php"><i class="fas fa-hand-holding-usd"></i> Apply for Loan</a></p>
            <?php endif; ?>
        </div>

        <div class="container mt-5">
            <div class="row">
                <div class="text-content dashboard-content">
                    <h2><i class="fas fa-coins"></i> Your Money, Your Rules 💸</h2>
                    <p>Since 1969, we've been making banking simple, smart, and stress-free.</p>
                    <p>We get it—banking isn’t the most exciting thing. But saving for your dreams? Now that’s a vibe. Whether it’s your first home, that dream car, or just a better way to manage your money, we’ve got you covered. 🚀</p>
                    <p><i class="fas fa-phone"></i> Call us anytime: <strong>+977 9819102869</strong></p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-info-circle"></i> Learn More</a>
                </div>
                <div class="image-placeholder">
                    <img src="bank_img/rename.png" alt="Bank Services">
                </div>
            </div>

            <div class="row">
                <div class="image-placeholder">
                    <img src="bank_img/piggy.png" alt="Bank Services">
                </div>
                <div class="text-content bank-services">
                    <h3><i class="fas fa-piggy-bank"></i> Banking, But Make It Easy 🎯</h3>
                    <p>From big life goals to daily expenses, we’ve got financial solutions that just make sense.</p>
                    <ul>
                        <li><i class="fas fa-home"></i> <b>Dream Home Loan</b> – Your dream home? Let's make it happen.</li>
                        <li><i class="fas fa-car"></i> <b>Auto Loan</b> – Hit the road with easy financing.</li>
                        <li><i class="fas fa-graduation-cap"></i> <b>Education Loan</b> – Invest in your future, worry-free.</li>
                        <li><i class="fas fa-wallet"></i> <b>Personal Loan</b> – For the unexpected (or that trip you’ve been planning 😉).</li>
                        <li><i class="fas fa-building"></i> <b>SME Loan</b> – Helping businesses grow, one loan at a time.</li>
                    </ul>
                    <a href="#" class="btn btn-primary"><i class="fas fa-search"></i> Explore Services</a>
                </div>
            </div>

            <div class="row">
                <div class="text-content financial-tools">
                    <h3><i class="fas fa-tools"></i> Money Tools for Smart People 🔥</h3>
                    <p>Managing money shouldn’t be complicated. Here’s how we help:</p>
                    <ul>
                        <li><i class="fas fa-percent"></i> <b>High-Interest Savings</b> – Make your money work for you.</li>
                        <li><i class="fas fa-mobile-alt"></i> <b>24/7 Digital Banking</b> – Send, receive, and manage your money anytime.</li>
                        <li><i class="fas fa-lock"></i> <b>Safe Deposit Lockers</b> – Because some things are better offline.</li>
                        <li><i class="fas fa-credit-card"></i> <b>Credit & Debit Cards</b> – Rewards, cashback, and zero stress.</li>
                    </ul>
                    <a href="#" class="btn btn-primary"><i class="fas fa-user-plus"></i> Join Now</a>
                </div>
                <div class="image-placeholder">
                    <img src="bank_img/cards.png" alt="Bank Services">
                </div>
            </div>

            <div class="row">
                <div class="image-placeholder">
                    <img src="bank_img/calc_sales.png" alt="Bank Services">
                </div>
                <div class="text-content financial-results">
                    <h3><i class="fas fa-chart-bar"></i> Our 2024 Highlights 📊</h3>
                    <p>Because numbers matter—especially when they work in your favor.</p>
                    <ul>
                        <li><i class="fas fa-money-bill-wave"></i> <b>57.6 bn</b> – Total Loans Given</li>
                        <li><i class="fas fa-chart-line"></i> <b>0.95%</b> – Classified Loan Portfolio (because we play it safe!)</li>
                        <li><i class="fas fa-chart-pie"></i> <b>388.5%</b> – Loan Coverage Ratio</li>
                        <li><i class="fas fa-coins"></i> <b>50.4 bn</b> – Deposits Secured</li>
                        <li><i class="fas fa-landmark"></i> <b>6.1 bn</b> – Shareholders’ Equity</li>
                        <li><i class="fas fa-shield-alt"></i> <b>18.51%</b> – Capital Adequacy Ratio (super strong 💪)</li>
                        <li><i class="fas fa-city"></i> <b>8.5 bn</b> – Market Capitalization</li>
                        <li><i class="fas fa-trophy"></i> <b>AAA Credit Rating</b> – Because trust is everything.</li>
                    </ul>
                    <a href="#" class="btn btn-primary"><i class="fas fa-file-alt"></i> View Report</a>
                </div>
            </div>
        </div>

        <!-- Carousel Section -->
        <div id="carouselExampleCaptions" class="carousel slide mt-4" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img style="width: 100%; height: 1000px; object-fit: cover;" src="bank_img/modern_bank.jpg" alt="Modern Banking Experience">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><i class="fas fa-building"></i> Modern Banking Experience</h5>
                        <p>Experience banking like never before with our cutting-edge technology and friendly service.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img style="width: 100%; height: 1000px; object-fit: cover;" src="bank_img/financial_growth2.jpg" alt="Financial Growth and Success">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><i class="fas fa-chart-line"></i> Financial Growth and Success</h5>
                        <p>Trust us to help you grow your wealth and achieve your financial goals.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img style="width: 100%; height: 1000px; object-fit: cover;" src="bank_img/family_home.jpg" alt="Family and Home Loan">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><i class="fas fa-home"></i> Family and Home Loan</h5>
                        <p>Your dream home is just a loan away. Let us help you make it a reality.</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <!-- Bootstrap JS and jQuery (for carousel functionality) -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    </div>

</body>
<?php include('includes/footer.html'); ?>

</html>

<?php
$conn->close();
?>