/* public/firebase-messaging-sw.js */
importScripts(
    "https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js",
);
importScripts(
    "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js",
);

// TODO: paste your firebaseConfig here
firebase.initializeApp({
    apiKey: "AIzaSyCg9F6j7wmEZPVuC3Q85Od_velGH9V6ExA",
    authDomain: "kandoura-f72d5.firebaseapp.com",
    projectId: "kandoura-f72d5",
    storageBucket: "kandoura-f72d5.firebasestorage.app",
    messagingSenderId: "353127064443",
    appId: "1:353127064443:web:2b4d49dc5f1e9e3e1813de",
    measurementId: "G-S2TLZ46HL1",
});

const messaging = firebase.messaging();

// Background notifications
messaging.onBackgroundMessage((payload) => {
    const title = payload?.notification?.title ?? "New notification";
    const options = {
        body: payload?.notification?.body ?? "",
        data: {
            url: payload?.data?.url ?? "/",
        },
    };

    self.registration.showNotification(title, options);
});

self.addEventListener("notificationclick", (event) => {
    const url = event.notification?.data?.url ?? "/";
    event.notification.close();

    event.waitUntil(clients.openWindow(url));
});
