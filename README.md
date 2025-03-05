# Digital-Wallet
A digital wallet, also known as an e-wallet, is a software-based system that securely stores users' payment information and passwords for numerous payment methods and websites. By using a digital wallet, users can complete purchases easily and quickly with their smartphones, tablets, or computers.

<h2>ER Diagram</h2>
<img src=https://github.com/mzeineddine/Digital-Wallet/blob/main/assets/db_er.png>

<h2>External Transaction API</h2>
<h4>This api is used in post method which require the email of the receiver and the id of the sender so that we can trasnfer between wallets. It can be used in any online store or pos systems.</h4>

<h4>The api is found in http://ec2-13-38-107-39.eu-west-3.compute.amazonaws.com/Digital-Wallet/Wallet-Server/user/v1/external_api.php where the developer have to send with the post request receiver_email
and id (sender id) as well as the amount to be transfered</h4>

<h3>Note: Both the sender and the receiver must there accounts and wallets to ensure the  transaction</h3>


