import { Pages } from "../enums/pagesEnum.js";

export class NavComponent extends HTMLElement {
    homeBtn;
    feedBtn;
    profBtn;
    ntfPopup;
    #initalized = false;

    constructor() { super(); }

    connectedCallback() {
        this.innerHTML = `
        <nav class="fx-row">
            <button id="goto-home-btn"><i class="fa-solid fa-house fa-lg"></i></button>
            <button id="goto-feed-btn"><i class="fa-solid fa-envelopes-bulk fa-lg"></i></button>
            <button id="goto-profile-btn"><i class="fa-solid fa-circle-user fa-lg"></i></button>
        </nav>
        <div id="notifications-popup" class="glassmorph"></div>
        `;
        if (!this.#initalized) {
            this.#initButtons();
            this.#setOnBtnPress();
            this.#initPopUp();
            this.#initalized = true;
        }
    }

    #initButtons() {
        this.homeBtn = document.getElementById("goto-home-btn");
        this.feedBtn = document.getElementById("goto-feed-btn");
        this.profBtn = document.getElementById("goto-profile-btn");
        this.ntfPopup = document.getElementById("notifications-popup");
    }

    #initPopUp() {
        this.ntfPopup = document.getElementById("notifications-popup");
        let style = "height: 200px; width: 70%;" 
            + "background: var(--accent-t); position: absolute;"
            + "top: 40px; left: 15%;"
            + "border: 1px solid rgba(255, 255, 255, 0.18);"
            + "border-radius: 10px;"
            + "backdrop-filter: blur(5px);"
            + "box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);";
        this.ntfPopup.setAttribute("style", style);
        this.ntfPopup.hidden = true;
        this.feedBtn.popup = this.ntfPopup;
    }

    #setOnBtnPress() {
        this.homeBtn.addEventListener("click", function() { redirect(Pages.Home) });
        this.profBtn.addEventListener("click", function() { redirect(Pages.Profile) });
        this.feedBtn.addEventListener("click", this.togglePopUp );
    }

    /** Toggles the notifications' popup.
     * In this function `this` references the feed button.
     */
    togglePopUp() {
        this.popup.hidden = !this.popup.hidden;
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
        return response.json();
    }).then(response => {
        window.location.href = response.url;
    }).catch(error => console.error(error));
}

customElements.define('pw-nav', NavComponent);
