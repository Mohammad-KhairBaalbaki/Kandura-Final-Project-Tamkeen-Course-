import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

const firebaseConfig = {
    apiKey: "AIzaSyCg9F6j7wmEZPVuC3Q85Od_velGH9V6ExA",
    authDomain: "kandoura-f72d5.firebaseapp.com",
    projectId: "kandoura-f72d5",
    storageBucket: "kandoura-f72d5.firebasestorage.app",
    messagingSenderId: "353127064443",
    appId: "1:353127064443:web:2b4d49dc5f1e9e3e1813de",
    measurementId: "G-S2TLZ46HL1",
};

const VAPID_KEY = env('FIREBASE_VAPID_KEY');

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

export async function initFcmAndRegisterToken() {
    // 1) Register service worker
    const swReg = await navigator.serviceWorker.register(
        "/firebase-messaging-sw.js",
    );

    // 2) Ask permission
    const permission = await Notification.requestPermission();
    if (permission !== "granted") return null;

    // 3) Get token (requires VAPID key)
    const token = await getToken(messaging, {
        vapidKey: VAPID_KEY,
        serviceWorkerRegistration: swReg,
    });

    if (!token) return null;

    // 4) Send token to Laravel
    await fetch("/api/fcm-token", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ??
                "",
        },
        credentials: "same-origin",
        body: JSON.stringify({ token }),
    });

    return token;
}

export function listenForegroundMessages(callback) {
    onMessage(messaging, (payload) => {
        // Your dashboard is open (foreground)
        callback?.(payload);

        // optional: show browser notification even in foreground
        if (payload?.notification?.title) {
            new Notification(payload.notification.title, {
                body: payload.notification.body,
            });
        }
    });
}
