const base = "http://localhost/Projects";
const api_base = "http://13.38.107.39";
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

function split_response(response_data){
    const [resultString, messageString] = response_data.split('}{');
    const fixedResultString = resultString + '}';
    const fixedMessageString = '{' + messageString;
    const result = JSON.parse(fixedResultString).result;
    const message = JSON.parse(fixedMessageString).message;
    return [result,message]
}

// async function axios(url,data){
//     const response = await axios.post(api_base+url,data);
//     [result,message]=split_response(response.data);
//     return [result,message];
// }

function register(){
    sessionStorage.clear();
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
        axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/register.php", {
            full_name: name,
            email: email,
            pass: pass
        })
        .then(response => {
            console.log(response.data);
            const [result,message] = split_response(response.data);
            alert(message);
            if(result.hasOwnProperty("id"))
                sessionStorage.setItem("user_id",result['id']);
            else
                alert(message);
            if(sessionStorage.hasOwnProperty("user_id")){
                window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/dashboard.html');
    }
        })
    }
    reset_fields_by_name(["full_name","pass","email","pass"])
}

// base+"/Wallet-Server/user/v1/login.php"
// "http://localhost/Projects/Digital-Wallet/Wallet-Server/user/v1/login.php"
        
async function login(){
    sessionStorage.clear();
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
        const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/login.php", {
            email: email,
            pass: pass
        });
        const [result,message] = split_response(response.data);
        if(result.hasOwnProperty("id"))
            sessionStorage.setItem("user_id",result['id']);
        else
            alert(message);
        if(sessionStorage.hasOwnProperty("user_id"))
            window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/dashboard.html');
        reset_fields_by_name(["email", "pass"]);
    }
}

function nav_icon_click(){
    let navMenu = document.querySelector("nav ul");
    navMenu.classList.toggle('active');
}

function check_login(){
    if(sessionStorage.getItem("user_id")){
        console.log("id found")
    }else{
        console.log("not found");
        window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/login.html');
    }
}
function transaction(){
    check_login()
}
async function get_user_by_id(id){
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/get_user.php", {
        id: id
    });
    const [result,message]=split_response(response.data);
    const name_o = document.querySelector('[name="full_name"]');
    const phone_nb_o = document.querySelector('[name="phone_number"]');
    const address_o = document.querySelector('[name="address"]');
    const tier_o = document.querySelector('[name="tier"]');
    console.log(result);
    name_o.value=result['name'];
    phone_nb_o.value=result['phone_nb'];
    address_o.value=result['address'];
    tier_o.value=result['validation_level'];
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
        data = {
            full_name: name,
            pass: pass,
            phone_nb: phone_nb,
            address: address,
            id: sessionStorage.getItem("user_id")
        }
        axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/update_user.php", data);
        alert("data updated");
        reset_fields_by_name(["full_name","pass","tier","check_password","address", "phone_number"])
        get_user_by_id(sessionStorage.getItem("user_id"));
    }
}

async function get_wallet_by_id_without_message(id){
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/get_wallet.php", {
        id: id
    });
    const [result,message] = split_response(response.data);
    if(result.hasOwnProperty("id")){
        document.getElementById("balance").innerHTML="Your balance is "+result["balance"];
        return true;
    }else{
        return false;
    }
}

async function get_wallet_by_id(id){
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/get_wallet.php", {
        id: id
    });
    const [result,message] = split_response(response.data);
    if(result.hasOwnProperty("id")){
        document.getElementById("balance").innerHTML="Your balance is "+result["balance"];
        return true;
    }else{
        alert(message);
        window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/dashboard.html');
    }
}

async function submit_withdraw(){
    amount = document.querySelector('[name="amount"]').value;
    let is_checkable = check_missing([amount],['amount']); 
    let id = sessionStorage.getItem("user_id");
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/withdraw.php", {
        id: id,
        amount: amount
    });
    if(!response.data){
        alert_message("Insufficient amount in balance");
    }        
    reset_fields_by_name(["amount"]);
    get_wallet_by_id(sessionStorage.getItem("user_id"));
}

async function submit_deposit(){
    amount = document.querySelector('[name="amount"]').value;
    check_missing([amount],['amount']); 
    let id = sessionStorage.getItem("user_id");
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/deposit.php", {
        id: id,
        amount: amount
    });
    // [result,message]=split_response(response.data);
    reset_fields_by_name(["amount"]);
    get_wallet_by_id(sessionStorage.getItem("user_id"));
}

function randomString(length, chars) {
    let result = '';
    for (let i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
    return result;
}

async function receive(){
    let code = randomString(10, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    let id = sessionStorage.getItem("user_id");
    const trans_code = document.getElementById('code');
    trans_code.innerHTML = code;
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/receive.php", {
        id: id,
        transaction_code: code
    });
    // [result,message]=split_response(response.data);
    get_wallet_by_id(sessionStorage.getItem("user_id"));
}

async function submit_send_trans(){
    const code_o = document.querySelector("[name='trans_code']");   
    const amount_o = document.querySelector("[name='amount']");
    
    const code = code_o.value;   
    const amount = amount_o.value;

    let id = sessionStorage.getItem("user_id");
    let is_checkable = check_missing([amount,code],['amount','code']);
    if(is_checkable){
        trans_code = code;
        const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/send.php", {
            id: id,
            transaction_code: code,
            amount: amount
        });
        const [result,message] = split_response(response.data);
        alert(message);
    }   
    reset_fields_by_name(["trans_code","amount"])
    get_wallet_by_id(sessionStorage.getItem("user_id"));
}

function view_trans_hist(){
    window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/trans_hist.html');
}

async function get_trans_hist(){
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/get_transactions_by_id.php", {
        id: sessionStorage.getItem("user_id"),
    });
    const [result,message] = split_response(response.data);
    console.log(result);
    const table = document.getElementById("trans_hist_table");
    for(let i = 0; i<result.length;i++){
        row = "<tr>";
            
            row+="<td>";row+=result[i]["sender_name"];row+="</td>";
            row+="<td>";row+=result[i]["receiver_name"];row+="</td>";
            row+="<td>";row+=result[i]["transaction_date"];row+="</td>";
            row+="<td>";row+=result[i]["amount"];row+="</td>";
        row +="</tr>";
        table.innerHTML+=row;
    }
}

function logout(){
    sessionStorage.clear();
    if(!sessionStorage.getItem("user_id")){
        window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/login.html');
    }
}

async function delete_account(){
    if(confirm("Do you want to delete the account and the wallet as well?")){
        const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/delete_user_and_wallet.php", {
            id: sessionStorage.getItem("user_id"),
        });
        const [result,message] = split_response(response.data);
        if(result){
            logout();
        }else{
            alert(message);
        }
    }
}

async function register_wallet(){
    const response = await axios.post(api_base+"/Digital-Wallet/Wallet-Server/user/v1/add_wallet.php", {
        id: sessionStorage.getItem("user_id"),
        transaction_code: randomString(10, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    });
    console.log(response.data);
    const [result,message] = split_response(response.data);
    window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/dashboard.html');
}
function go_to_login(){
    window.location.replace(base+'/Digital-Wallet/Wallet-Client/HTML/login.html');
}
async function dashboard_load(){
    console.log(sessionStorage.getItem("user_id"));
    if(sessionStorage.getItem("user_id")){
        bool = await(get_wallet_by_id_without_message(sessionStorage.getItem("user_id")));
        console.log(bool);
        if(bool){
            document.getElementById("btn_login").classList.add("none");
            document.getElementById("btn").classList.add("none");
            document.getElementById("balance").classList;
        }else{
            document.getElementById("btn").classList;
            document.getElementById("balance").classList.add("none");
            document.getElementById("btn_login").classList.add("none");
        }
    } else{
        document.getElementById("btn").classList.add("none");
        document.getElementById("balance").classList.add("none");
    }
}