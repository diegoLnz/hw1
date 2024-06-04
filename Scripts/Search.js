var baseUrl = "Controller/Users/GetUsersListForSearchForm.php";

document.addEventListener("DOMContentLoaded", function() {
    const searchBox = document.getElementById("search-input");
    const username = document.getElementById("hidden-username").value;
    searchBox.addEventListener("input", async function() {
        const searchParam = searchBox.value;

        if(searchParam == "")
        {
            clearUsers();
            return;
        }
    
        const users = await getUsers(searchParam, username);
        displayUsers(users);
    });
});

async function getUsers(searchParam, username) {
    return await fetch(`${baseUrl}?search=${searchParam}&user=${username}`)
        .then(response => response.json());
}

function displayUsers(users) 
{
    const usersListDiv = document.getElementById("users-list-div");
    usersListDiv.innerHTML = "";

    users.forEach(user => {
        const userDiv = document.createElement("a");
        userDiv.classList.add("user-item");

        userDiv.href = `users.php?user=${user.username}`;
        
        userInfo = document.createElement("div");
        userInfo.classList.add("user-item-info");
        var usernamesurname = document.createElement("span");
        usernamesurname.classList.add("usernamesurname");
        usernamesurname.innerHTML = user.name_surname;
        var username = document.createElement("span");
        username.classList.add("username");
        username.innerHTML = user.username;
        userInfo.appendChild(usernamesurname);
        userInfo.appendChild(username);
        userDiv.appendChild(userInfo);
        
        var image = document.createElement("img");
        image.classList.add("profile-image");
        image.src = "Images/Generic_User.png";
        userDiv.appendChild(image);

        usersListDiv.appendChild(userDiv);
    });
}

function clearUsers() 
{
    const usersListDiv = document.getElementById("users-list-div");
    usersListDiv.innerHTML = "";
}