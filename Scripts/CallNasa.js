document.addEventListener("DOMContentLoaded", async function() {
    const nasaPostContainer = document.getElementById('nasa-post-container');
    const picData = await getNasaAPOD();
    DisplayNasaPic(picData, nasaPostContainer);
});

async function getNasaAPOD()
{
    return await fetch("Controller/Nasa/GetPicOfTheDay.php")
        .then(response => response.json());
}

function DisplayNasaPic(picData, container){
    const postHTML = document.createElement("div");
    postHTML.classList.add("single-post");

    postHTML.appendChild(generateNasaPostHeaderHTML("NASA", picData.date));
    postHTML.appendChild(generateNasaPostContentHTML(picData.explanation, picData.url, picData.post_id));
    postHTML.appendChild(generateNasaPostFooterHTML());

    container.appendChild(postHTML);
}

function generateNasaPostHeaderHTML(username, time) {
    const postHeader = document.createElement('div');
    postHeader.classList.add('post-header');

    const userInfo = document.createElement('div');
    userInfo.classList.add('user-info');

    const userImage = document.createElement('div');
    userImage.classList.add('user-image');
    userInfo.appendChild(userImage);

    const mainUsername = document.createElement('div');
    mainUsername.classList.add('main-username');
    const userLink = document.createElement('a');
    userLink.classList.add('userlink');
    userLink.href = '#';
    userLink.textContent = username;
    mainUsername.appendChild(userLink);
    userInfo.appendChild(mainUsername);

    postHeader.appendChild(userInfo);

    const actionsAndDate = document.createElement('div');
    actionsAndDate.classList.add('actions-and-date');

    const uploadDate = document.createElement('p');
    uploadDate.classList.add('upload-date');
    uploadDate.textContent = time;
    actionsAndDate.appendChild(uploadDate);

    const actionBtn = document.createElement('div');
    actionBtn.classList.add('action-btn');
    const svgIcon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svgIcon.setAttribute('width', '16');
    svgIcon.setAttribute('height', '16');
    svgIcon.setAttribute('fill', 'currentColor');
    svgIcon.setAttribute('viewBox', '0 0 16 16');
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', 'M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3');
    svgIcon.appendChild(path);
    actionBtn.appendChild(svgIcon);
    actionsAndDate.appendChild(actionBtn);

    postHeader.appendChild(actionsAndDate);

    return postHeader;
}

function generateNasaPostContentHTML(postBody, postImage, postId) {
    const userId = document.getElementById("user-id").value;

    const postContent = document.createElement('div');
    postContent.classList.add('post-content');

    const postText = document.createElement('p');
    postText.classList.add('post-text');
    postText.textContent = postBody;
    postContent.appendChild(postText);

    if(postImage != "")
    {
        const postImageContainer = document.createElement('div');
        postImageContainer.classList.add('post-image');
        const image = document.createElement('img');
        image.src = postImage;
        postImageContainer.appendChild(image);
        postContent.appendChild(postImageContainer);
    }

    return postContent;
}

function generateNasaPostFooterHTML() {
    const postFooter = document.createElement('div');
    postFooter.classList.add('post-footer', 'pointed');

    const usersFooterImages = document.createElement('div');
    usersFooterImages.classList.add('users-footer-images');
    const userFooterImage = document.createElement('div');
    userFooterImage.classList.add('user-footer-image');

    usersFooterImages.appendChild(userFooterImage);
    postFooter.appendChild(usersFooterImages);

    const separator = document.createElement('div');
    separator.textContent = ' · ';
    postFooter.appendChild(separator);

    return postFooter;
}