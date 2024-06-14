function handleLogin(){
    window.location.href="login.php";
}

function handleLoginAdmin(){
    window.location.href="./user/login.php";
}

document.addEventListener("DOMContentLoaded", function() {
    // Lấy URL hiện tại
    const currentLocation = window.location.href;
    // Lấy tất cả các mục menu
    const menuItems = document.querySelectorAll("#navbar-sticky a");
    
    menuItems.forEach(item => {
        // Kiểm tra nếu href của mục menu trùng với URL hiện tại
        if (item.href === currentLocation) {
            item.setAttribute("aria-current", "page");
        } else {
            item.removeAttribute("aria-current");
        }
    });
});