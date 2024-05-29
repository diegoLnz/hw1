var baseUrl = "Controller/Users/GetUsersListForSearchForm.php?search=";

document.addEventListener("DOMContentLoaded", function() {
    const searchBox = document.getElementById("search-input");
    searchBox.addEventListener("input", async function() {
        const searchParam = searchBox.value;

        if(searchParam == "")
        {
            clearUsers();
            return;
        }
    
        const users = await getUsers(searchParam);
        displayUsers(users);
    });
});

async function getUsers(searchParam) {
    return await fetch(`${baseUrl}${searchParam}`)
        .then(response => response.json());
}

function displayUsers(users) 
{
    const usersListDiv = document.getElementById("users-list-div");
    usersListDiv.innerHTML = "";

    users.forEach(user => {
        const userDiv = document.createElement("div");
        userDiv.classList.add("user-item");
        
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