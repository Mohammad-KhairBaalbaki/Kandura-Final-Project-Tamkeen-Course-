import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

import { initFcmAndRegisterToken, listenForegroundMessages } from "./fcm";

if ("serviceWorker" in navigator) {
    initFcmAndRegisterToken();

    listenForegroundMessages((payload) => {
        console.log("FCM foreground:", payload);
        // update UI (toast, badge, etc.)
    });
}
