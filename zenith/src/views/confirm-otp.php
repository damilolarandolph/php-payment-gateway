<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>Confirm OTP</title>
</head>

<style>
    * {
        padding: 0;
        margin: 0;
        border: none;
        box-sizing: border-box;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    body {
        width: 100vw;
        height: 100vh;
        display: flex;
        background-color: white;
    }

    .otp-container {
        width: 500px;
        height: 600px;
        display: flex;
        padding: 30px;
        flex-direction: column;
        border-radius: 15px;
        justify-content: center;
        margin: auto auto;
        box-shadow: 17px 25px 60px rgba(128, 128, 128, 0.357);
        border: 2px solid rgba(71, 61, 139, 0.076);
    }

    .verification-text {
        font-weight: bold;
        font-size: 30px;
        text-align: center;
    }

    .verification-subtext {
        font-size: larger;
        text-align: center;

        margin-top: 10px;
        color: grey;
    }

    .verification-input {
        margin-top: 140px;
        width: 100%;
        border: 3px solid rgba(128, 128, 128, 0.296);
        outline: none;
        height: 60px;
        font-weight: bold;
        border-radius: 9px;
        letter-spacing: 10px;

        font-size: 70px;
        text-align: center;
    }

    .verification-input:focus {
        outline: none;
    }

    .verification-button {
        display: block;
        margin-top: 15px;
        border-radius: 9px;
        width: 100%;
        background-color: rgb(111, 60, 252);
        color: white;
        font-size: 20px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 5px;
        height: 60px;
        cursor: pointer;
        transition: all 0.5s;
    }

    .verification-button:hover {
        transition: all 0.5s;
        background-color: rgb(73, 31, 187);
    }

    .verification-image {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px auto;
    }
</style>

<body>
    <div class='otp-container'>
        <form method='POST' action='http://zenith.com/oauth/loginCheck' id='otp-form'>
            <div class='verification-image'>
                <img src='/public/images/smartphone.svg' alt='some image' />
            </div>
            <h4 class='verification-text'>Verification</h4>
            <p class='verification-subtext'>
                We sent you a <b>One Time Password</b> on your phone number. Please
                enter it to authorize the transaction.
            </p>
            <input maxlength='8' autocomplete='off' type='password' class='verification-input' name='otpAnswer' />
            <input type='hidden' value='<?php echo $consumerId ?>' name='consumerApiKey' />
            <input type='hidden' value='<?php echo $redirectURL ?>' name='redirectUrl' />
            <input type='hidden' value='<?php echo $account ?>' name='accountNumber' />
            <input type='hidden' value='<?php echo $data ?>' name='data' />
            <button class='verification-button'>Verify</button>
        </form>
    </div>
</body>

</html>