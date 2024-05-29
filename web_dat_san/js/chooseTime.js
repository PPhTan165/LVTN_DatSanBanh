document.querySelectorAll(".select-button").forEach(button => {
    button.addEventListener("click", function() {
        document.getElementById("selected_time").value = this.dataset.id;
        document.querySelectorAll(".select-button").forEach(btn => btn.classList.remove("bg-green-500"));
        this.classList.add("bg-green-500");
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Lấy giá trị của biến PHP từ thẻ script trong HTML
    const currentDate = new Date();
    console.log(currentDate);
    // Lấy thẻ input date
    const dateInput = document.getElementById('dateInput');

    // Đặt giá trị mặc định và min cho thẻ input date


    // In giá trị của thẻ input date lên console khi thay đổi
    dateInput.addEventListener('change', function() {
        console.log(dateInput.value);
    });
});
