document.addEventListener('DOMContentLoaded', () => {
    const optionsButton = document.getElementById("options");
    const dropdownMenu = document.getElementById("dropdown-menu");
    const logoutButton = document.getElementById("logout");
    SetModalScript();
    SetTextAreaScript();
    SetUploadScript();

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
            ResetNewThreadForm();
        }
    }
}

function SetTextAreaScript()
{
    const textarea = document.getElementById('new-thread-input-text');
    const publishBtn = document.getElementById('submit-thread');
    
    textarea.addEventListener('input', () => {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';

        if (textarea.value.length > 0) {
            publishBtn.classList.remove('btn-disabled');
            publishBtn.disabled = false;
        } else if(textarea.value.length == 0 && FileUploaderIsEmpty()) {
            publishBtn.classList.add('btn-disabled');
            publishBtn.disabled = true;
        }
    });
}

function SetUploadScript()
{
    const uploadButton = document.getElementById('upload-button');
    const fileInput = document.getElementById('file-input');
    const imagePreview = document.getElementById('image-preview');
    const removeImageButton = document.getElementById('remove-image-button');
    const publishBtn = document.getElementById('submit-thread');

    uploadButton.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];

        if (file && (file.type === 'image/jpeg' || file.type === 'image/png'))
        {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                SetDisplayBlock(imagePreview);
                SetDisplayNone(uploadButton);
                SetDisplayBlock(removeImageButton);

                publishBtn.classList.remove('btn-disabled');
                publishBtn.disabled = false;
            };
            reader.readAsDataURL(file);
        } 
        else 
        {
            alert('Per favore carica un file JPG o PNG.');
            fileInput.value = "";
            SetDisplayNone(imagePreview);
            //uploadButton.textContent = "Carica foto";
        }
    });

    removeImageButton.addEventListener('click', () => {
        fileInput.value = "";
        imagePreview.src = "";
        SetDisplayBlock(uploadButton);
        SetDisplayNone(imagePreview);
        SetDisplayNone(removeImageButton);
        //uploadButton.textContent = "Carica foto";

        if(TextAreaIsEmpty()) {
            publishBtn.classList.add('btn-disabled');
            publishBtn.disabled = true;
        }
    });
}

function ResetNewThreadForm() {
    const textarea = document.getElementById('new-thread-input-text');
    textarea.value = "";

    const fileInput = document.getElementById('file-input');
    fileInput.value = "";

    const imagePreview = document.getElementById('image-preview');
    imagePreview.src = "";
    SetDisplayNone(imagePreview);

    const removeImageButton = document.getElementById('remove-image-button');
    SetDisplayNone(removeImageButton);

    const uploadButton = document.getElementById('upload-button');
    //uploadButton.textContent = "Carica foto";
}

function TextAreaIsEmpty()
{
    const textarea = document.getElementById('new-thread-input-text');
    return textarea.value.length == 0;
}

function FileUploaderIsEmpty()
{
    const fileInput = document.getElementById('file-input');
    return fileInput.value.length == 0;
}