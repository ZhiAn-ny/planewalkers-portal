export class NotificationComponent extends HTMLElement {
    #title;
    #text;
    #iconClass;
    #initalized = false;

    constructor() {
        super();
    }

    get title() {
        return this.#title;
    }
    set title(value) {
        this.#title = value;
        this.setAttribute('title', value);
    }
    
    get text() {
        return this.#text;
    }
    set text(value) {
        this.#text = value;
        this.setAttribute('text', value);
    }

    get iconClass() {
        return this.#iconClass;
    }
    set iconClass(value) {
        this.#iconClass = value;
        this.setAttribute('iconClass', value);
    }

    static get observedAttributes() {
        return ['title', 'text', 'iconClass'];
    }

    connectedCallback() {
        this.render();
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (oldValue !== newValue) {
            this[name] = newValue;
            this.render();
        }
    }

    render() {
        this.innerHTML = `
            <div class="notification-container">
                <i class="notification-icon fa-xl"></i>
                <div class="notification-content">
                    <h2 class="notification-title"></h2>
                    <p class="notification-text"></p>
                </div>
            </div>`;

        this.#initValues();
        setTimeout(() => {
            this.style.display = 'none';
        }, 5000);
    }
  
    #initValues() {
        try {
            const classes = this.#iconClass.split(' ');
            const icon = this.querySelector(".notification-icon");
            if (icon.classList.length < 3) {
                icon.classList.add(...classes);
            }
            this.querySelector(".notification-title").innerHTML = this.title;
            this.querySelector(".notification-text").innerHTML = this.text;
        } catch (error) {
            console.error(error);                
        }
    }
}

customElements.define('pw-notif', NotificationComponent);
