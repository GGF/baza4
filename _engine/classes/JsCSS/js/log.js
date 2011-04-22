/*
 * Логирование в консоль
 */
function log(text) {
    if (window.console) {
        console.log(text);
    } else if (window.opera) {
        opera.postError(text);
    } else {
        window.alert(text);
    }

}
