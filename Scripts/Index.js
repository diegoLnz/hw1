document.addEventListener('DOMContentLoaded', () => {
    const optionsButton = document.getElementById("options");
    const dropdownMenu = document.getElementById("dropdown-menu");
    const logoutButton = document.getElementById("logout");
    SetModalScript();

    optionsButton.addEventListener("click", () => {
        ToggleBlockDisplay(dropdownMenu);
    });

    document.addEventListener("click", (event) => {
        if (!optionsButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            SetDisplayNone(dropdownMenu);
        }
    });

    logoutButton.addEventListener("click", () => {
        window.location.href = "Configs/DoLogOut.php";
    });
});

function ToggleBlockDisplay(element)
{
    element.classList.contains('d-none') 
        ? SetDisplayBlock(element)
        : SetDisplayNone(element);
}

function SetDisplayBlock(element)
{
    element.classList.remove('d-none');
    element.classList.remove('d-flex');
    element.classList.add('d-block');
}

function SetDisplayNone(element)
{
    element.classList.remove('d-block');
    element.classList.remove('d-flex');
    element.classList.add('d-none');
}

function SetModalScript()
{
    var modal = document.getElementById("new-thread-modal");
    var btn = document.getElementById("new-thread");
    var textArea = document.getElementsByClassName("start-thread-label")[0];

    btn.onclick = () => {
        SetDisplayBlock(modal);
    }

    textArea.onclick = () => {
        SetDisplayBlock(modal);
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            SetDisplayNone(modal);
        }
    }
}