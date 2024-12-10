window.RufflePlayer = window.RufflePlayer || {};
window.RufflePlayer.config = {
    "width": "200",
    "height": "200",
    "publicPath": undefined,
    "polyfills": true,
    "autoplay": "on",
    "unmuteOverlay": "hidden",
    "backgroundColor": "#000000",
    "wmode": "window",
    "letterbox": "fullscreen",
    "warnOnUnsupportedContent": true,
    "contextMenu": false,
    "upgradeToHttps": window.location.protocol === "https:",
    "maxExecutionDuration": {"secs": 15, "nanos": 0},
    "logLevel": "error",
    "base": null,
    "allowScriptAccess": true,
    "menu": false,
    "salign": "TL",
    "scale": "noscale",
    "quality": "high",
    "preloader": false,
}
window.addEventListener("load", (event) => {
    const ruffle = window.RufflePlayer.newest();

    const player = ruffle.createPlayer();
    const container = document.getElementById("contestflash");
    container.appendChild(player);

    player.load({
        url: "/swf/contest.swf?g=" + window.g_id,
        width: "200",
        height: "200",
        salign: "TL",

    });
});
swfobject.embedSWF("/swf/contest.swf", "contestflash", "150", "30", "8", "/swfobject/expressInstall.swf", { g: window.g_id}, { bgcolor: "#000000", menu: "false", quality: "high", scale: "noscale", salign: "tl", wmode: "opaque" });
