const base = "http://localhost/Projects/Digital-Wallet/";
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
        sessionStorage.setItem("user_name",response.data['name']);
        sessionStorage.setItem("user_email",response.data['email']);
        sessionStorage.setItem("user_validation_level",response.data['validation_level']);

        console.log(sessionStorage.getItem("user_name"));
        console.log(sessionStorage.getItem("user_email"));
        console.log(sessionStorage.getItem("user_validation_level"));
        console.log(sessionStorage)
        if(sessionStorage.getItem("user_id")){
            window.location.replace(base+'/Wallet-Client/HTML/dashboard.html')
        }
    }
    reset_fields_by_name(["email", "pass"]);
}

function nav_icon_click(){
    var navMenu = document.querySelector("nav ul");
    navMenu.classList.toggle('active');
}


function transaction(){
    console.log("in transactions");
    if(sessionStorage.getItem("user_id")){
        console.log("id found")
    }else{
        console.log("not found");
        window.location.replace(base+'/Wallet-Client/HTML/login.html');
    }
}