
const base = "http://localhost/Projects/Digital-Wallet/";
function alert_message($message){
    alert("message");
    return false;
}
function register(){
    const name = document.querySelector('[name="full_name"]').value;
    const email = document.querySelector('[name="email"]').value;
    const pass = document.querySelector('[name="pass"]').value;
    let is_checkable = true;
    if (name === "") {
        is_checkable = alert_message("name is missing");
    }else if (email === "") {
        is_checkable = alert_message("email is missing");
    }else if (pass === "") {
        is_checkable = alert_message("password is missing");
    }else{
        const email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.match(email_regex)){
            is_checkable = alert_message("Invalid email address");
        }
    }
    if (is_checkable){
        // base+"/Wallet-Server/user/v1/register.php"
        // "http://localhost/Projects/Digital-Wallet/Wallet-Server/user/v1/register.php"
        document.querySelector('[name="full_name"]').value="";
        document.querySelector('[name="pass"]').value="";
        document.querySelector('[name="email"]').value="";
        document.querySelector('[name="pass"]').value="";
        axios.post(base+"/Wallet-Server/user/v1/register.php", {
            full_name: name,
            email: email,
            pass: pass
        })
        .then(response => {
            console.log(response.data);
        })
        .catch(error => {
            console.error('There was an error!', error);
            errorMessage.textContent = "An error occurred. Please try again.";
        });
        
    }
}

// base+"/Wallet-Server/user/v1/login.php"
    // "http://localhost/Projects/Digital-Wallet/Wallet-Server/user/v1/login.php"
        
async function login(){
    const email = document.querySelector('[name="email"]').value;
    const pass = document.querySelector('[name="pass"]').value;
    let is_checkable = true;
    if (email === "") {
        is_checkable = alert_message("email is missing");
    }else if (pass === "") {
        is_checkable = alert_message("password is missing");
    }else{
        const email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.match(email_regex)){
            is_checkable = alert_message("Invalid email address");
        }
    }
    if (is_checkable){
        document.querySelector('[name="email"]').value="";
        document.querySelector('[name="pass"]').value="";
        
        const response = await axios.post(base+"/Wallet-Server/user/v1/login.php", {
            email: email,
            pass: pass
        });
        console.log(response.data);
    }
}

function nav_icon_click(){
    var navMenu = document.querySelector("nav ul");
    navMenu.classList.toggle('active');
}