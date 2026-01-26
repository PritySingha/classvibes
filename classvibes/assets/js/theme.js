document.addEventListener('DOMContentLoaded', () => {
    let currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme); 

    updateIcon(currentTheme);

    const themeToggleButton = document.getElementById('theme-toggle');

    if (themeToggleButton) {
        themeToggleButton.addEventListener('click', toggleTheme);
    } else {
        console.error("Theme toggle button not found!");
    }
});


function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');

    if (currentTheme === 'light') {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark'); 
        updateIcon('dark'); 
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light'); 
        updateIcon('light'); 
    }
}


function updateIcon(theme) {
    const themeToggleButton = document.getElementById('theme-toggle');
    if (themeToggleButton) {
        if (theme === 'dark') {
            themeToggleButton.innerHTML = '<i class="fas fa-sun"></i>'; 
        } else {
            themeToggleButton.innerHTML = '<i class="fas fa-moon"></i>'; 
        }
    }
}




