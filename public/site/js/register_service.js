//đăng ký service worker
let swRegistration = navigator.serviceWorker.register('./service.js');
//sau khi đăng ký xong ta sẽ yêu cầu quyền để được đẩy thông báo
if (swRegistration) {
    let permission = window.Notification.requestPermission();
    if (permission !== 'granted') {
        throw new Error('Permission not granted for Notification');
    }
}

function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
function subscribeUser() {
    navigator.serviceWorker.ready
        .then(registration => {
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(window.appServerKey)
            };

            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then(pushSubscription => {
            storePushSubscription(pushSubscription);
        });
}

function storePushSubscription(pushSubscription) {
    fetch('/subscriptions', {
        method: 'POST',
        body: JSON.stringify(pushSubscription),
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(res => {
        return res.json();
    });
}

subscribeUser();