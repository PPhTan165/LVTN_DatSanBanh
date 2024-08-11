function deleteQRCodeAfterTimeout(qrId, timeout) {
    setTimeout(function() {
        var form = document.createElement('form');
        form.method = 'post';
        form.action = '';

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete';
        input.value = qrId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }, timeout);
}

function startCountdown(duration) {
    var timer = duration, seconds, minutes;
    var countdownElement = document.getElementById('countdown');

    var interval = setInterval(function () {
        seconds = parseInt(timer % 60, 10);
        minutes = parseInt(timer / 60, 10);
        seconds = seconds < 10 ? "0" + seconds : seconds;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        countdownElement.textContent = minutes + " : " + seconds ;

        if (--timer === 0) {
            clearInterval(interval);
            countdownElement.textContent = "Mã QR đã bị xóa";
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        }

    }, 1000);
}

function initiateCountdownAndDelete(qrId, countdownTime) {
     // thời gian đếm ngược (giây)
    deleteQRCodeAfterTimeout(qrId, countdownTime * 1000);
    startCountdown(countdownTime);
}