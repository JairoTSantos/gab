/*!
* Start Bootstrap - Simple Sidebar v6.0.6 (https://startbootstrap.com/template/simple-sidebar)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-simple-sidebar/blob/master/LICENSE)
*/
// 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
   
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            const isSidebarToggled = document.body.classList.toggle('sb-sidenav-toggled');
            if (isSidebarToggled) {
                sidebarToggle.classList.remove('btn-primary');
                sidebarToggle.classList.add('btn-success');
            } else {
                sidebarToggle.classList.remove('btn-success');
                sidebarToggle.classList.add('btn-primary');
            }
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});
