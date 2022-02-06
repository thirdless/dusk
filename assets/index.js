let svgs = `
<svg>
    <symbol id="user" viewBox="0 0 28 28">
        <path d="M14 0c7.734 0 14 6.266 14 14 0 7.688-6.234 14-14 14-7.75 0-14-6.297-14-14 0-7.734 6.266-14 14-14zM23.672 21.109c1.453-2 2.328-4.453 2.328-7.109 0-6.609-5.391-12-12-12s-12 5.391-12 12c0 2.656 0.875 5.109 2.328 7.109 0.562-2.797 1.922-5.109 4.781-5.109 1.266 1.234 2.984 2 4.891 2s3.625-0.766 4.891-2c2.859 0 4.219 2.312 4.781 5.109zM20 11c0-3.313-2.688-6-6-6s-6 2.688-6 6 2.688 6 6 6 6-2.688 6-6z"></path>
    </symbol>
    <symbol id="trash" viewBox="0 0 22 28">
        <path d="M8 21.5v-11c0-0.281-0.219-0.5-0.5-0.5h-1c-0.281 0-0.5 0.219-0.5 0.5v11c0 0.281 0.219 0.5 0.5 0.5h1c0.281 0 0.5-0.219 0.5-0.5zM12 21.5v-11c0-0.281-0.219-0.5-0.5-0.5h-1c-0.281 0-0.5 0.219-0.5 0.5v11c0 0.281 0.219 0.5 0.5 0.5h1c0.281 0 0.5-0.219 0.5-0.5zM16 21.5v-11c0-0.281-0.219-0.5-0.5-0.5h-1c-0.281 0-0.5 0.219-0.5 0.5v11c0 0.281 0.219 0.5 0.5 0.5h1c0.281 0 0.5-0.219 0.5-0.5zM7.5 6h7l-0.75-1.828c-0.047-0.063-0.187-0.156-0.266-0.172h-4.953c-0.094 0.016-0.219 0.109-0.266 0.172zM22 6.5v1c0 0.281-0.219 0.5-0.5 0.5h-1.5v14.812c0 1.719-1.125 3.187-2.5 3.187h-13c-1.375 0-2.5-1.406-2.5-3.125v-14.875h-1.5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h4.828l1.094-2.609c0.313-0.766 1.25-1.391 2.078-1.391h5c0.828 0 1.766 0.625 2.078 1.391l1.094 2.609h4.828c0.281 0 0.5 0.219 0.5 0.5z"></path>
    </symbol>
    <symbol id="sort" viewBox="0 0 28 28">
        <path d="M19 24.5v3c0 0.281-0.219 0.5-0.5 0.5h-4c-0.281 0-0.5-0.219-0.5-0.5v-3c0-0.281 0.219-0.5 0.5-0.5h4c0.281 0 0.5 0.219 0.5 0.5zM11.5 22.5c0 0.141-0.063 0.266-0.156 0.375l-4.984 4.984c-0.109 0.094-0.234 0.141-0.359 0.141s-0.25-0.047-0.359-0.141l-5-5c-0.141-0.156-0.187-0.359-0.109-0.547s0.266-0.313 0.469-0.313h3v-21.5c0-0.281 0.219-0.5 0.5-0.5h3c0.281 0 0.5 0.219 0.5 0.5v21.5h3c0.281 0 0.5 0.219 0.5 0.5zM22 16.5v3c0 0.281-0.219 0.5-0.5 0.5h-7c-0.281 0-0.5-0.219-0.5-0.5v-3c0-0.281 0.219-0.5 0.5-0.5h7c0.281 0 0.5 0.219 0.5 0.5zM25 8.5v3c0 0.281-0.219 0.5-0.5 0.5h-10c-0.281 0-0.5-0.219-0.5-0.5v-3c0-0.281 0.219-0.5 0.5-0.5h10c0.281 0 0.5 0.219 0.5 0.5zM28 0.5v3c0 0.281-0.219 0.5-0.5 0.5h-13c-0.281 0-0.5-0.219-0.5-0.5v-3c0-0.281 0.219-0.5 0.5-0.5h13c0.281 0 0.5 0.219 0.5 0.5z"></path>
    </symbol>
    <symbol id="lock" viewBox="0 0 20 20">
        <path d="M4 8v-2c0-3.314 2.686-6 6-6s6 2.686 6 6v0 2h1c1.105 0 2 0.895 2 2v0 8c0 1.105-0.895 2-2 2v0h-14c-1.105 0-2-0.895-2-2v0-8c0-1.1 0.9-2 2-2h1zM9 14.73v2.27h2v-2.27c0.602-0.352 1-0.996 1-1.732 0-1.105-0.895-2-2-2s-2 0.895-2 2c0 0.736 0.398 1.38 0.991 1.727l0.009 0.005zM7 6v2h6v-2c0-1.657-1.343-3-3-3s-3 1.343-3 3v0z"></path>
    </symbol>
    <symbol id="search" viewBox="0 0 32 32">
        <path d="M31.008 27.231l-7.58-6.447c-0.784-0.705-1.622-1.029-2.299-0.998 1.789-2.096 2.87-4.815 2.87-7.787 0-6.627-5.373-12-12-12s-12 5.373-12 12 5.373 12 12 12c2.972 0 5.691-1.081 7.787-2.87-0.031 0.677 0.293 1.515 0.998 2.299l6.447 7.58c1.104 1.226 2.907 1.33 4.007 0.23s0.997-2.903-0.23-4.007zM12 20c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"></path>
    </symbol>
    <symbol id="pin" viewBox="0 0 32 32">
        <path d="M17 0l-3 3 3 3-7 8h-7l5.5 5.5-8.5 11.269v1.231h1.231l11.269-8.5 5.5 5.5v-7l8-7 3 3 3-3-15-15zM14 17l-2-2 7-7 2 2-7 7z"></path>
    </symbol>
    <symbol id="help" viewBox="0 0 24 24">
        <path d="M23 12c0-3.037-1.232-5.789-3.222-7.778s-4.741-3.222-7.778-3.222-5.789 1.232-7.778 3.222-3.222 4.741-3.222 7.778 1.232 5.789 3.222 7.778 4.741 3.222 7.778 3.222 5.789-1.232 7.778-3.222 3.222-4.741 3.222-7.778zM21 12c0 2.486-1.006 4.734-2.636 6.364s-3.878 2.636-6.364 2.636-4.734-1.006-6.364-2.636-2.636-3.878-2.636-6.364 1.006-4.734 2.636-6.364 3.878-2.636 6.364-2.636 4.734 1.006 6.364 2.636 2.636 3.878 2.636 6.364zM10.033 9.332c0.183-0.521 0.559-0.918 1.022-1.14s1.007-0.267 1.528-0.083c0.458 0.161 0.819 0.47 1.050 0.859 0.183 0.307 0.284 0.665 0.286 1.037 0 0.155-0.039 0.309-0.117 0.464-0.080 0.16-0.203 0.325-0.368 0.49-0.709 0.709-1.831 1.092-1.831 1.092-0.524 0.175-0.807 0.741-0.632 1.265s0.741 0.807 1.265 0.632c0 0 1.544-0.506 2.613-1.575 0.279-0.279 0.545-0.614 0.743-1.010 0.2-0.4 0.328-0.858 0.328-1.369-0.004-0.731-0.204-1.437-0.567-2.049-0.463-0.778-1.19-1.402-2.105-1.724-1.042-0.366-2.135-0.275-3.057 0.167s-1.678 1.238-2.044 2.28c-0.184 0.521 0.090 1.092 0.611 1.275s1.092-0.091 1.275-0.611zM12 18c0.552 0 1-0.448 1-1s-0.448-1-1-1-1 0.448-1 1 0.448 1 1 1z"></path>
    </symbol>
    <symbol id="close" viewBox="0 0 32 32">
        <path d="M16 0c-8.837 0-16 7.163-16 16s7.163 16 16 16 16-7.163 16-16-7.163-16-16-16zM16 29c-7.18 0-13-5.82-13-13s5.82-13 13-13 13 5.82 13 13-5.82 13-13 13z"></path>
        <path d="M21 8l-5 5-5-5-3 3 5 5-5 5 3 3 5-5 5 5 3-3-5-5 5-5z"></path>
    </symbol>
    <symbol id="ban" viewBox="0 0 32 32">
        <path d="M16 0c-8.836 0-16 7.164-16 16s7.164 16 16 16 16-7.164 16-16-7.164-16-16-16zM16 4c2.59 0 4.973 0.844 6.934 2.242l-16.696 16.688c-1.398-1.961-2.238-4.344-2.238-6.93 0-6.617 5.383-12 12-12zM16 28c-2.59 0-4.973-0.844-6.934-2.242l16.696-16.688c1.398 1.961 2.238 4.344 2.238 6.93 0 6.617-5.383 12-12 12z"></path>
    </symbol>
    <symbol id="recover" viewBox="0 0 32 32">
        <path d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"></path>
    </symbol>
    <symbol id="promote" viewBox="0 0 32 32">
        <path d="M32 12.408l-11.056-1.607-4.944-10.018-4.944 10.018-11.056 1.607 8 7.798-1.889 11.011 9.889-5.199 9.889 5.199-1.889-11.011 8-7.798z"></path>
    </symbol>
</svg>
`;

function $(_){return document.querySelector(_)}
function $a(_){return document.querySelectorAll(_)}

let notification = null,
    notificationTimeout = null;
function showNotification(text){
    if(typeof notificationTimeout === "number") {
        clearTimeout(notificationTimeout);
        document.body.removeChild(notification);
        notification = null;
        notificationTimeout = null;
    }

    if(text == null) return;

    notification = document.createElement("div");
    notification.className = "notification";
    notification.textContent = text;
    document.body.appendChild(notification);
    setTimeout(function(){
        notification.classList.add("show");
    }, 50);
    notificationTimeout = setTimeout(function(){
        notification.classList.remove("show");
        setTimeout(function(){
            document.body.removeChild(notification);
            notification = null;
            notificationTimeout = null;
        }, 400);
    }, 5000);
}

function ajax(url, method, data, func){
    let http = new XMLHttpRequest();
    http.open(method, url, true);
    http.onreadystatechange = function(){
        if(http.readyState == 4 && http.status == 200){
            func(http.responseText);
        }
        else if(http.readyState == 4 && http.status != 200){
            showNotification("Error: Network problem");
        }
    }
    if(method.toUpperCase() == "POST") http.send(data);
    else http.send();
}

function standardJSONParsing(resp, func){
    let response = JSON.parse(resp);
    if(response.code == 0){
        showNotification("Error: " + response.message);
    }
    else if(response.code == 1){
        func(response);
    }
}

let modal = null,
    modaloverlay = null;

function closeModal(){
    if(modal === null) return;

    document.body.removeChild(modal);
    document.body.removeChild(modaloverlay);

    modal = null;
    modaloverlay = null;
}

function openModal(element, width = null){
    if(modal !== null || !(element instanceof Node)) return;

    modaloverlay = document.createElement("div");
    modaloverlay.className = "modal-overlay";

    let modalclose = document.createElement("div");
    modalclose.className = "close";
    modalclose.innerHTML = '<svg><use xlink:href="#close"></use></svg>';
    modalclose.addEventListener("click", closeModal);
    let modalinner = document.createElement("div");
    modalinner.className = "parent";
    modalinner.appendChild(element);

    modal = document.createElement("div");
    modal.className = "modal";
    if(width != null) modal.style.width = width + "px";
    modal.appendChild(modalinner);
    modal.appendChild(modalclose);

    document.body.appendChild(modaloverlay);
    document.body.appendChild(modal);
}

function checkPage(page){
    return !!document.querySelectorAll("body>.main." + page).length;
}

function createSVG(){
    let parent = document.createElement("div");
    parent.setAttribute("style", "display:none;");
    parent.innerHTML = svgs;
    document.body.appendChild(parent);
}

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

function checkPostForm(e){
    let alphanumRegex = new RegExp("[a-zA-Z0-9]");
    
    let title = $(".create .title"),
        textarea = $(".create textarea"),
        lock = $(".lockpost .switch"),
        pin = $(".pinpost .switch");
    
    if(title.value == "" && textarea.value == "") return;
    else if(title.value == "" || !alphanumRegex.test(title.value)){
        showNotification("Choose a title.");
    }
    else if(textarea.value == "" || !alphanumRegex.test(textarea.value)){
        showNotification("Type some text to post.");
    }
    else{
        showNotification();

        let data = new FormData();
        data.append("title", title.value);
        data.append("textarea", textarea.value);
        if(lock && lock.classList.contains("on")) data.append("lock", 1);
        if(pin && pin.classList.contains("on")) data.append("pin", 1);
        
        ajax("/action.php?action=createPost", "POST", data, (resp) => {
            standardJSONParsing(resp, (response) => {
                title.value = "";
                textarea.value = "";
                location.href = response.url;
            });
        });
    }
}

function checkCommForm(){
    let alphanumRegex = new RegExp("[a-zA-Z0-9]");
    
    let post = $(".cominput textarea").getAttribute("data-post"),
        textarea = $(".cominput textarea");

    if(textarea.value == "" || !alphanumRegex.test(textarea.value)){
        showNotification("Type some text.");
    }
    else if(location.href.indexOf(post) == -1){
        showNotification("Faulty post parameter.");
    }
    else{
        showNotification();

        let data = new FormData();
        data.append("textarea", textarea.value);
        data.append("postid", post);
        
        ajax("/action.php?action=createComment", "POST", data, (resp) => {
            standardJSONParsing(resp, (response) => {
                textarea.value = "";
                location.reload();
            });
        });
    }
}

function deleteAction(e){
    let param = e.currentTarget.getAttribute("data-action"),
        data = param.split(":"),
        numericRegex = new RegExp("[0-9]");
    
    if(data.length != 2 || (data[0] != "deletePost" && data[0] != "deleteCom") || !numericRegex.test(data[1])){
        showNotification("Bad parameter");
        return;
    }

    showNotification();

    ajax("/action.php?action=" + data[0] + "&id=" + data[1], "GET", 0, (resp) => {
        standardJSONParsing(resp, (response) => {
            if(response.url) location.href = response.url;
            else location.reload();
        });
    });
}

function helpBBCodeModal(){
    let element = document.createElement("div");
    element.className = "help-bbcodes";

    element.innerHTML = `
        <div class="sep">
            <svg><use xlink:href="#lock"></use></svg> - activate this to lock the comments on the post
        </div>
        <div class="sep">
            <svg><use xlink:href="#pin"></use></svg> - activate this to pin the post to the top of the list (only for admins and moderators)
        </div>
        <div class="sep">
            <p>You can also format your text using these codes:</p>
            <center><table>
                <tr>
                    <td>Code</td>
                    <td>Meaning</td>
                </tr>
                <tr>
                    <td>[big=Text]</td>
                    <td>Use this code to type a subtitle or something important.</td>
                </tr>
                <tr>
                    <td>[img=URL]</td>
                    <td>Insert an image by entering the image URL into the code.</td>
                </tr>
                <tr>
                    <td>[youtube=videoID]</td>
                    <td>Insert a youtube video by entering the video ID into the code. Note: use only the videoID and nothing else; example: [youtube=P5eCtL6KlTU]</td>
                </tr>
                <tr>
                    <td>[caption=Text]</td>
                    <td>Insert a caption for the image or video you previously inserted. This will generate a small centered text.</td>
                </tr>
            </table></center>
        </div>
    `;

    openModal(element);
}

function changePassModal(){
    let element = document.createElement("div");
    element.className = "change-pass";

    element.innerHTML = `
        <div class="subtitle">Change password</div>
        <input name="oldpass" type="password" placeholder="Old Password"/>
        <div class="sep"></div>
        <input name="newpass" type="password" placeholder="New Password"/>
        <div class="sep"></div>
        <input name="newpass1" type="password" placeholder="Confirm New Password"/>
        <div class="sep"></div>
        <div class="gen_button submit">Change</div>
    `;

    element.querySelector(".submit").addEventListener("click", () => {
        let oldpass = element.querySelector("[name=oldpass]"),
            newpass = element.querySelector("[name=newpass]"),
            newpass1 = element.querySelector("[name=newpass1]");

        if(!oldpass.value.length || !newpass.value.length || !newpass1.value.length){
            showNotification("Fill every row.");
            return;
        }
        if(newpass.value != newpass1.value){
            showNotification("New passwords are not the same.");
            return;
        }
        
        let data = new FormData();
        data.append("oldpass", oldpass.value);
        data.append("newpass", newpass.value);
        data.append("newpass1", newpass1.value);

        ajax("/action.php?action=changePassword", "POST", data, (resp) => {
            standardJSONParsing(resp, (response) => {
                oldpass.value = "";
                newpass.value = "";
                newpass1.value = "";
                showNotification("Password changed. Refreshing in 2 seconds.");
                setTimeout(function(){
                    location.reload();
                }, 2000);
            });
        });
    });

    openModal(element, 500);
}

function toggleInvitation(e){
    let button = e.currentTarget,
        value = e.currentTarget.getAttribute("data-action"),
        url = "/action.php?action=changeSettings&setting=invitation&option=";

    if(value == "create"){
        ajax(url + "1", "GET", 0, (resp) => {
            standardJSONParsing(resp, (response) => {
                location.reload();
            });
        });
    }
    else if(value == "delete"){
        ajax(url + "0", "GET", 0, (resp) => {
            standardJSONParsing(resp, (response) => {
                location.reload();
            });
        });
    }
}

function toggleSetting(element, type){
    let url = "/action.php?action=changeSettings&setting=" + type + "&option=",
        value = element.classList.contains("on") ? "0" : "1";

    ajax(url + value, "GET", 0, (resp) => {
        let response = JSON.parse(resp);
        if(response.code == 0){
            showNotification("Error: " + response.message);
            if(value == "0") element.classList.add("on");
            else element.classList.remove("on");
        }
        else if(response.code == 1 && type == "darkMode"){
            if(value == "0") document.body.classList.remove("dark");
            else if(value == "1") document.body.classList.add("dark");
        }
    });
}

function userHandling(e){
    let target = e.target;

    while(true){
        if(target == e.currentTarget) return;
        else if(target.getAttribute("data-action")) break;

        target = target.parentNode;
    }

    let value = target.getAttribute("data-action"),
        data = new FormData();

    data.append("user", target.parentNode.getAttribute("data-user"));
    data.append("type", value);

    ajax("/action.php?action=changeUser", "POST", data, (resp) => {
        standardJSONParsing(resp, (response) => {
            location.reload();
        });
    });
}

function loginError(text)
{
    let warning = document.querySelectorAll("body>.main>.content>.warning"),
        parent = document.querySelector("body>.main>.content"),
        separator = document.querySelector("body>.main>.content>.separator"),
        element = document.createElement("div");

    if(warning.length != 0) warning[0].outerHTML = "";

    element.className = "warning";
    element.innerText = text;

    parent.insertBefore(element, separator);
}

function main(){
    let loginForm = "body>.main>.content>.separator>form",
        loginFormInput = loginForm + ">input";

    createSVG();

    if(checkPage("home")){
        //home page
        let searchBar = $(".search input.bar");
        searchBar.addEventListener("keydown", (e) => {
            if(e.keyCode == 13){
                if(searchBar.value.trim() == "") location.href = "/";
                else location.href = "/?search=" + searchBar.value;
            }
        });

        let submitButton = $(".create .submit");
        if(submitButton) submitButton.addEventListener("click", checkPostForm);
    }
    else if(checkPage("post")){
        //post page
        let submitButton = $(".cominput .submit");
        if(submitButton) submitButton.addEventListener("click", checkCommForm);

        for(let i = 0; i < $a(".action.delete").length; i++){
            $a(".action.delete")[i].addEventListener("click", deleteAction);
        }
    }
    else if(checkPage("user")){
        //user page
        let invitationButton = $(".setting.invitation .submit"),
            darkSwitch = $(".setting.dark .switch"),
            privateSwitch = $(".setting.private .switch"),
            list = $(".content .list");

        if(invitationButton) invitationButton.addEventListener("click", toggleInvitation);
        if(darkSwitch) darkSwitch.addEventListener("click", () => {
            toggleSetting(darkSwitch, "darkMode");
        });
        if(privateSwitch) privateSwitch.addEventListener("click", () => {
            toggleSetting(privateSwitch, "privateProfile");
        });

        if(list) list.addEventListener("click", userHandling);
    }
    else if(checkPage("register"))
    {
        let user = document.querySelector(loginFormInput + "[name=username]"),
            pass = document.querySelector(loginFormInput + "[name=password]"),
            pass1 = document.querySelector(loginFormInput + "[name=password1]"),
            inv = document.querySelector(loginFormInput + "[name=invitation]"),
            text = "";

        form.addEventListener("submit", (e) => {
            e.preventDefault();
            if(user.value == "" || pass.value == "" || pass1.value == "" || inv.value == "")
            {
                if(user.value == 0) text = "Username";
                else if(pass.value == 0) text = "Password";
                else if(pass1.value == 0) text = "Confirm Password";
                else if(inv.value == 0) text = "Invitation";
                
                loginError(text + " field is empty");
            }
            else if(pass1.value != pass.value)
            {
                text = "Passwords are not the same";
                loginError(text);
            }
            else
            {
                let act = form.action,
                    met = form.method,
                    data = new FormData();

                data.append("username", user.value);
                data.append("password", pass.value);
                data.append("password1", pass1.value);
                data.append("invitation", inv.value);
                
                ajax(act, met, data, (response) => {
                    let element = document.createElement("div");

                    element.innerHTML = response;

                    let isNotHome = element.querySelectorAll(".main.home").length == 0,
                        warningElement = element.querySelector(".main>.content>.warning");
                    
                    if(isNotHome && warningElement) loginError(warningElement.innerText);
                    else location.reload();
                });
            }
        })
    }

    for(let i = 0; i < $a(".switch").length; i++){
        $a(".switch")[i].addEventListener("click", (e) => {
            e.currentTarget.classList.toggle("on");
        });
    }

    for(let i = 0; i < $a(".open-modal").length; i++){
        $a(".open-modal")[i].addEventListener("click", (e) => {
            let value = e.currentTarget.getAttribute("data-open");

            if(value == "help-bbcodes") helpBBCodeModal();
            else if(value == "change-pass") changePassModal();
        });
    }
}

//document.addEventListener("DOMContentLoaded", dom);
window.addEventListener("load", main);