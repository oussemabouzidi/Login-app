function setLanguage(lang) {
    // Change the text based on the selected language
    document.querySelectorAll('[data-lang-fr], [data-lang-en]').forEach(function (element) {
        element.textContent = element.getAttribute(`data-lang-${lang}`);
    });

    // Toggle the active class on buttons
    document.querySelectorAll('.language-switcher button').forEach(function (button) {
        button.classList.remove('active');
    });

    // Add active class to the clicked button
    if (lang === 'fr') {
        document.querySelector('button[onclick="setLanguage(\'fr\')"]').classList.add('active');
    } else if (lang === 'en') {
        document.querySelector('button[onclick="setLanguage(\'en\')"]').classList.add('active');
    }
}
