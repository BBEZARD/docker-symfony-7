const GA_MEASUREMENT_ID = 'G-BZ69Z88ZJE';
const COOKIE_BANNER = document.getElementById('cookie-banner');
const COOKIE_INFORM_AND_ASK = document.getElementById('cookie-inform-and-ask');
const COOKIE_MORE_BUTTON = document.getElementById('cookie-more-button');
const GA_CONFIRM_BUTTON = document.getElementById('ga-confirm-button');
const COOLIE_EXPIRE = null

// 1. On récupère l'éventuel cookie indiquant le choix passé de l'utilisateur
const CONSENT_GA_COOKIE = Cookies.getJSON('hasGaConsent');
const consentLgCookie = Cookies.getJSON('hasLgConsent');

function startGoogleAnalytics() {
    window.dataLayer = window.dataLayer || [];
    function gtag(){
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', GA_MEASUREMENT_ID, {
        'send_page_view': true,
    });
}

function processCookieConsent() {
    // 2. On récupère la valeur "doNotTrack" du navigateur
    const doNotTrack = navigator.doNotTrack || navigator.msDoNotTrack;

    // 3. Si le cookie existe et qu'il vaut explicitement "false" ou que le "doNotTrack" est défini à "OUI"
    //    l'utilisateur s'oppose à l'utilisation des cookies. On exécute une fonction spécifique pour ce cas.
    if (doNotTrack === 'yes' || doNotTrack === '1' || CONSENT_GA_COOKIE === false || consentLgCookie === false) {
        reject();
        removeHtmlCookie();
        return;
    }

    // 4. Si le cookie existe et qu'il vaut explicitement "true", on démarre juste Google Analytics
    if (CONSENT_GA_COOKIE === true) {
        startGoogleAnalytics();
        removeHtmlCookie();
        return;
    }

    // 5. Si le cookie n'existe pas et que le "doNotTrack" est défini à "NON", on crée le cookie "hasGaConsent" avec pour
    //    valeur "true" pour une durée de 13 mois (la durée maximum autorisée) puis on démarre Google Analytics
    if (doNotTrack === 'no' || doNotTrack === '0') {
        Cookies.set('hasGaConsent', true, { expires: COOLIE_EXPIRE });
        Cookies.set('hasLgConsent', true, { expires: COOLIE_EXPIRE });
        startGoogleAnalytics();
        removeHtmlCookie();
        return;
    }

    // 6. Si le cookie n'existe pas et que le "doNotTrack" n'est pas défini, alors on affiche le bandeau et on crée les listeners
    COOKIE_BANNER.classList.remove('is-closed')
    COOKIE_MORE_BUTTON.addEventListener('click', onMoreButtonClick, false);
    document.addEventListener('click', onDocumentClick, false);
};

// const GA_PROPERTY = 'UA-XXXXX-Y';
const GA_COOKIE_NAMES = ['__utma', '__utmb', '__utmc', '__utmz', '_ga', '_gat'];

function reject(cookieGoogle=true, cookieLang=true) {
    if (CONSENT_GA_COOKIE === false || cookieGoogle === false) {
        // création du cookie spécifique empêchant Google Analytics de démarrer
        // Cookies.set(`ga-disable-${GA_PROPERTY}`, true, { expires: COOLIE_EXPIRE });
        // insertion de cette valeur dans l'objet window
        // window[`ga-disable-${GA_PROPERTY}`] = true;

        // création du cookie précisant le choix utilisateur
        Cookies.set('hasGaConsent', false, { expires: COOLIE_EXPIRE });

        function gtag(){
            dataLayer.push(arguments);
        }
        gtag('config', GA_MEASUREMENT_ID, {
            'send_page_view': false,
        });

        // suppression de tous les cookies précédemment créés par Google Analytics
        GA_COOKIE_NAMES.forEach(cookieName => Cookies.remove(cookieName));
    }

    if (consentLgCookie  === false || cookieLang  === false) {
        // création du cookie précisant le choix utilisateur
        Cookies.set('hasLgConsent', false, { expires: COOLIE_EXPIRE });

        // suppression de tous les cookies précédemment créés par Google Analytics
        Cookies.remove('cklang');
    }

}

function onDocumentClick(event) {
    const target = event.target;
// Si l'utilisateur a cliqué sur le bandeau ou le bouton dans ce dernier alors on ne fait rien.
    if ((target.id === 'cookie-banner'
        || target.parentNode.id === 'cookie-banner'
        || target.parentNode.parentNode.id === 'cookie-banner')
        && target.className !== ('banner-close')) {
        return;
    }

    event.preventDefault();

// On crée le cookie signifiant le consentement de l'utilisateur et on démarre Google Analytics
    Cookies.set('hasGaConsent', true, { expires: COOLIE_EXPIRE });
    Cookies.set('hasLgConsent', true, { expires: COOLIE_EXPIRE });
    Cookies.set('cklang', true, { expires: COOLIE_EXPIRE });
    startGoogleAnalytics();

// On cache le bandeau
    COOKIE_BANNER.classList.add('is-closed');
    removeHtmlCookie();

// On supprime le listener sur la page et celui sur le bouton du bandeau
    document.removeEventListener('click', onDocumentClick, false);
    COOKIE_MORE_BUTTON.removeEventListener('click', onMoreButtonClick, false);
}

function onMoreButtonClick(event) {
    event.preventDefault();

// On affiche la boîte de dialogue permettant à l'utilisateur de faire son choix
    COOKIE_BANNER.classList.remove('is-closed');

// On cache le bandeau
    COOKIE_BANNER.classList.add('is-closed');
    COOKIE_BANNER.remove();
    COOKIE_INFORM_AND_ASK.classList.remove('is-closed');

// On crée les listeners sur les boutons de la boîte de dialogue
    GA_CONFIRM_BUTTON.addEventListener('click', onGaConfirmButtonClick, false);

// On supprime le listener sur la page et celui sur le bouton du bandeau
    document.removeEventListener('click', onDocumentClick, false);
    COOKIE_MORE_BUTTON.removeEventListener('click', onMoreButtonClick, false);
}

function onGaConfirmButtonClick(event) {
    let cookieGoogle = document.getElementById('cookie-switch-google').checked;
    let cookieLang = document.getElementById('cookie-switch-lang').checked;
    event.preventDefault();

    if (cookieGoogle  === true) {
        // On crée le cookie signifiant le consentement de l'utilisateur et on démarre Google Analytics
        Cookies.set('hasGaConsent', true, { expires: COOLIE_EXPIRE });
        startGoogleAnalytics();
    }

    if (cookieLang === true) {
        // On crée le cookie signifiant le consentement de l'utilisateur et on démarre Google Analytics
        Cookies.set('hasLgConsent', true, { expires: COOLIE_EXPIRE });
        Cookies.set('cklang', true, { expires: COOLIE_EXPIRE });
    }

    if (cookieGoogle === false || cookieLang  === false)
    {
        reject(cookieGoogle, cookieLang);
    }

// On cache la boîte de dialogue
    COOKIE_INFORM_AND_ASK.classList.add('is-closed')
    COOKIE_INFORM_AND_ASK.parentNode.remove();

// On supprime les listeners sur les boutons de la boîte de dialogue
    GA_CONFIRM_BUTTON.removeEventListener('click', onGaConfirmButtonClick, false);
}

processCookieConsent();

function removeHtmlCookie() {
    if (COOKIE_BANNER) {
        if (COOKIE_BANNER.parentNode) {
            COOKIE_BANNER.parentNode.remove();
        }
    }

}
