function menu() {
    document.getElementById("burger").addEventListener('click', function () {

        let element = document.getElementById('menu-list');
        if (element.style.display === "block") {
            element.style.display = "none";
        } else {
            element.style.display = "block";
        }
    });

}