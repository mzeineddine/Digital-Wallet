
const base = "http://localhost/Projects/Digital-Wallet/";
function register(){
    const name = document.querySelector('[name="full_name"]').value;
    const email = document.querySelector('[name="email"]').value;
    const pass = document.querySelector('[name="pass"]').value;
    let is_checkable = true;
    if (name === "") {
        alert("Name is missing");
        is_checkable = false;
    }else if (email === "") {
        alert("email is missing");
        is_checkable = false;
    }else if (pass === "") {
        alert("password is missing");
        is_checkable = false;
    }else{
        const email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.match(email_regex)){
            alert("Invalid email address");
            is_checkable = false;
        }
    }
    if (is_checkable){
        // base+"/Wallet-Server/user/v1/register.php"
        // "http://localhost/Projects/Digital-Wallet/Wallet-Server/user/v1/register.php"
        
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
        alert("email is missing");
        is_checkable = false;
    }else if (pass === "") {
        alert("password is missing");
        is_checkable = false;
    }else{
        const email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.match(email_regex)){
            alert("Invalid email address");
            is_checkable = false;
        }
    }
    if (is_checkable){
        const response = await axios.post(base+"/Wallet-Server/user/v1/login.php", {
            email: email,
            pass: pass
        });
        console.log(response.data);
    }
}