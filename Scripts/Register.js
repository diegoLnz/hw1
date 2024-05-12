function togglePassword(caller, targetId){
    var target = document.getElementById(targetId);
    target.type = target.type == 'password' ? 'text' : 'password';
    caller.textContent = target.type == 'password' ? 'Mostra' : 'Nascondi';
}