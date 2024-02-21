import { Pages } from "../enums/pagesEnum.js";

export class NavComponent extends HTMLElement {
    homeBtn;
    feedBtn;
    profBtn;
    #initalized = false;

    constructor() { super(); }

    connectedCallback() {
        this.innerHTML = `
        <nav class="fx-row">
            <button id="goto-home-btn"><i class="fa-solid fa-house fa-lg"></i></button>
            <button id="goto-feed-btn"><i class="fa-solid fa-envelopes-bulk fa-lg"></i></button>
            <button id="goto-profile-btn"><i class="fa-solid fa-circle-user fa-lg"></i></button>
        </nav>
        `;
        if (!this.#initalized) {
            this.#initButtons();
            this.#setOnBtnPress();
            this.#initalized = true;
        }
    }

    #initButtons() {
        this.homeBtn = document.getElementById("goto-home-btn");
        this.feedBtn = document.getElementById("goto-feed-btn");
        this.profBtn = document.getElementById("goto-profile-btn");
    }

    #setOnBtnPress() {
        this.homeBtn.addEventListener("click", function() { redirect(Pages.Home) });
        this.feedBtn.addEventListener("click", function() { redirect(Pages.Feed) });
        this.profBtn.addEventListener("click", function() { redirect(Pages.Profile) });
    }

    
}
function redirect(pageId) {
    const params = { page: pageId };
    fetch('http://localhost/pwp/src/app/lib/routing_functions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: JSON.stringify(params)
    }).then(response => {
        if (!response.ok) {
            throw new Error("Redirect failed");
        }
        console.log('Redirect ok');
        return response.json();
    }).then(response => {
        window.location.href = response.url;
    }).catch(error => console.error(error));
}

customElements.define('pw-nav', NavComponent);
