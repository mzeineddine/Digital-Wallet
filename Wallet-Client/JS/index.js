const base = "http://localhost/Projects/Digital-Wallet";
function alert_message(message){
    alert(message);
    return false;
}

function check_missing(data, args){
    let is_checkable = true;
    for(let i=0; i<args.length;i++){
        if(data[i]==''){
            return alert_message(args[i]+" is missing");
        }
    }
    return is_checkable;
}

function reset_fields_by_name(args){
    for(let i = 0; i<args.length;i++){
        document.querySelector('[name="'+args[i]+'"]').value = "";
    }
}

function register(){
    const name = document.querySelector('[name="full_name"]').value;
    const email = document.querySelector('[name="email"]').value;
    const pass = document.querySelector('[name="pass"]').value;
    let is_checkable = check_missing([name,email,pass],['name','email','password']);
    if(is_checkable){
        const email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.match(email_regex)){
            is_checkable = alert_message("Invalid email address");
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
            sessionStorage.setItem("user_id",response.data['id']);
            sessionStorage.setItem("user_name",response.data['name']);
            sessionStorage.setItem("user_email",response.data['email']);
            sessionStorage.setItem("user_validation_level",response.data['validation_level']);
            console.log(sessionStorage.getItem("user_name"));
            console.log(sessionStorage.getItem("user_email"));
            console.log(sessionStorage.getItem("user_validation_level"));
            console.log(response)
        })
    }
    reset_fields_by_name(["full_name","pass","email","pass"])
}

// base+"/Wallet-Server/user/v1/login.php"
// "http://localhost/Projects/Digital-Wallet/Wallet-Server/user/v1/login.php"
        
async function login(){
    const email = document.querySelector('[name="email"]').value;
    const pass = document.querySelector('[name="pass"]').value;
    let is_checkable = check_missing([email,pass],['email','password']);
    if(is_checkable){
        const email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!email.match(email_regex)){
            is_checkable = alert_message("Invalid email address");
        }
    }
    if (is_checkable){
        // document.querySelector('[name="email"]').value="";
        // document.querySelector('[name="pass"]').value="";
        const response = await axios.post(base+"/Wallet-Server/user/v1/login.php", {
            email: email,
            pass: pass
        });
        sessionStorage.setItem("user_id",response.data['id']);

        if(!isNaN(sessionStorage.getItem("user_id"))){
            window.location.replace(base+'/Wallet-Client/HTML/dashboard.html');
        }else{
            alert("Email and password mismatch");
        }
    }
    reset_fields_by_name(["email", "pass"]);
}

function nav_icon_click(){
    var navMenu = document.querySelector("nav ul");
    navMenu.classList.toggle('active');
}

function check_login(){
    if(sessionStorage.getItem("user_id")){
        console.log("id found")
    }else{
        console.log("not found");
        window.location.replace(base+'/Wallet-Client/HTML/login.html');
    }
}
function transaction(){
    check_login()
}
async function get_user_by_id(id){
    const response = await axios.post(base+"/Wallet-Server/user/v1/get_user.php", {
        id: id
    });
    console.log(response.data)
    const name_o = document.querySelector('[name="full_name"]');
    const phone_nb_o = document.querySelector('[name="phone_number"]');
    const address_o = document.querySelector('[name="address"]');
    const tier_o = document.querySelector('[name="tier"]');

    name_o.value=response.data['name'];
    phone_nb_o.value=response.data['phone_nb'];
    address_o.value=response.data['address'];
    tier_o.value=response.data['validation_level'];
}

function settings(){
    check_login();
    get_user_by_id(sessionStorage.getItem("user_id"));
}

async function change_user_data(){
    const name_o = document.querySelector('[name="full_name"]');
    const pass_o = document.querySelector('[name="pass"]');
    const pass2_o = document.querySelector('[name="check_password"]');
    const phone_nb_o = document.querySelector('[name="phone_number"]');
    const address_o = document.querySelector('[name="address"]');
    const tier_o = document.querySelector('[name="tier"]');

    const name = name_o.value;
    const pass = pass_o.value;
    const pass2 = pass2_o.value;
    const phone_nb = phone_nb_o.value;
    const address = address_o.value;
    
    let is_checkable = check_missing([name],['name']); 

    if(pass!=pass2){
        is_checkable = false;
        alert_message("password does not match");
    }
    if (is_checkable){
        // base+"/Wallet-Server/user/v1/register.php"
        // "http://localhost/Projects/Digital-Wallet/Wallet-Server/user/v1/register.php"
        axios.post(base+"/Wallet-Server/user/v1/update_user.php", {
            full_name: name,
            pass: pass,
            phone_nb: phone_nb,
            address: address,
            id: sessionStorage.getItem("user_id")
        })
        .then(response => {
            alert("data updated");
            reset_fields_by_name(["full_name","pass","tier","check_password","address", "phone_number"])
            get_user_by_id(sessionStorage.getItem("user_id"));
        })
    }
}

async function get_wallet_by_id(id){
    const response = await axios.post(base+"/Wallet-Server/user/v1/get_wallet.php", {
        id: id
    });
    if(response.data["id"]){
        document.getElementById("balance").innerHTML="Your balance is "+response.data["balance"];
    } else{
        alert("No wallet found. Create one")
        window.location.replace(base+'/Wallet-Client/HTML/dashboard.html');
    }
    reset_fields_by_name(["amount"]);
}

function withdraw_deposit(){ 
    get_wallet_by_id(sessionStorage.getItem("user_id"));
}
async function submit_withdraw(){
    amount = document.querySelector('[name="amount"]').value;
    let is_checkable = check_missing([amount],['amount']); 
    id = sessionStorage.getItem("user_id");
    const response = await axios.post(base+"/Wallet-Server/user/v1/withdraw.php", {
        id: id,
        amount: amount
    });
    withdraw_deposit();
}

async function submit_deposit(){
    amount = document.querySelector('[name="amount"]').value;
    let is_checkable = check_missing([amount],['amount']); 
    id = sessionStorage.getItem("user_id");
    const response = await axios.post(base+"/Wallet-Server/user/v1/deposit.php", {
        id: id,
        amount: amount
    });
    withdraw_deposit();
}