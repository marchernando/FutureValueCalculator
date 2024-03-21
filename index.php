<?php
    //set default value of variables for initial page load
    $investment = isset($_POST['investment']) ? $_POST['investment'] : '';
    $interest_rate = isset($_POST['interest_rate']) ? $_POST['interest_rate'] : '';
    $years = isset($_POST['years']) ? $_POST['years'] : '';
    
    // define variables for displaying results
    $investment_f = '';
    $yearly_rate_f = '';
    $future_value_f = '';
    $years_f = '';

    // set default error message of empty string
    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // get the data from the form
        $investment = filter_input(INPUT_POST, 'investment', FILTER_VALIDATE_FLOAT);
        $interest_rate = filter_input(INPUT_POST, 'interest_rate', FILTER_VALIDATE_FLOAT);
        $years = filter_input(INPUT_POST, 'years', FILTER_VALIDATE_INT);

        // validate investment
        if ($investment === FALSE) {
            $error_message .= 'Investment must be a valid number.<br>'; 
        } else if ($investment <= 0) {
            $error_message .= 'Investment must be greater than zero.<br>'; 
        } 

        // validate interest rate
        if ($interest_rate === FALSE) {
            $error_message .= 'Interest rate must be a valid number.<br>'; 
        } else if ($interest_rate <= 0 || $interest_rate > 15) {
            $error_message .= 'Interest rate must be greater than zero and less than or equal to 15.<br>'; 
        } 

        // validate years
        if ($years === FALSE) {
            $error_message .= 'Years must be a valid whole number.<br>';
        } else if ($years <= 0 || $years > 30) {
            $error_message .= 'Years must be greater than zero and less than or equal to 30.<br>';
        } 

        // calculate the future value if there are no errors
        if ($error_message == '') {
            $future_value = $investment;
            for ($i = 1; $i <= $years; $i++) {
                $future_value += $future_value * $interest_rate * .01;
            }
            // apply currency and percent formatting
            $investment_f = '$'.number_format($investment, 2);
            $yearly_rate_f = $interest_rate.'%';
            $future_value_f = '$'.number_format($future_value, 2);

            // apply years value to display results variable
            $years_f = $years;
            
            // reset input values
            $investment = '';
            $interest_rate = '';
            $years = '';
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Future Value Calculator</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <main>
        <h1>Future Value Calculator</h1>
        <?php if (!empty($error_message)) { ?>
            <p class="error" style="color: red"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div id="data">
                <label>Investment Amount:</label>
                <input type="text" name="investment" value="<?php echo htmlspecialchars($investment); ?>">
                <br>

                <label>Yearly Interest Rate:</label>
                <input type="text" name="interest_rate" value="<?php echo htmlspecialchars($interest_rate); ?>">
                <br>

                <label>Number of Years:</label>
                <input type="text" name="years" value="<?php echo htmlspecialchars($years); ?>">
                <br>
            </div>

            <div id="buttons">
                <label>&nbsp;</label>
                <input type="submit" value="Calculate">
                <br>
            </div>
        </form>

        <?php if ($future_value_f != '') { ?>
            <h2>Future Value:</h2>
            <p>Investment Amount: <?php echo $investment_f; ?></p>
            <p>Yearly Interest Rate: <?php echo $yearly_rate_f; ?></p>
            <p>Number of Years: <?php echo $years_f; ?></p>
            <p>Future Value: <?php echo $future_value_f; ?></p>
            <p>This calculation was done on <?php echo date('m/d/Y'); ?>.</p>
        <?php } ?>
    </main>
</body>
</html>
