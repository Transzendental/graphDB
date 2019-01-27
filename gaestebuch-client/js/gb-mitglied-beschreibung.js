import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

class GaestebuchMitgliedBeschreibung extends LitElement {
    static get properties() {
        return {
            hidden: {type: String}
        }
    }

    constructor() {
        super();

        this.load = false;
    }

    async loadMitglied() {
        this.hidden = html`<img src="image/load.gif" border="0" width="50"><br><br>`;
        await fetch(new Request('http://10.20.110.43/team21/view/getMitglied.php?ID='+getUrlVars()["id"]), {
            method: 'GET',
            mode: 'cors',
            cache: 'no-store',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                return response.json();
            })
            .then((mitglied) => {
                this.name = mitglied["name"];
                this.rolle = mitglied["rolle"];
                this.geburtsdatum = mitglied["geburtsdatum"];
                this.geschlecht = mitglied["geschlecht"];

                this.hidden = html``;
            });
    }

    render() {
        if(!this.load) {
            this.loadMitglied();
            this.load = true;
        }

        return html`
        
        <h2>Beschreibung</h2>
        
        ${this.hidden}
        
        <b>Name:</b> ${this.name}<br>
        <b>Rolle:</b> ${this.rolle}<br>
        <b>Geburtsdatum:</b> ${this.geburtsdatum}<br>
        <b>Geschlecht:</b> ${this.geschlecht}<br>
        
        <hr>
        `;
    }
}
customElements.define("gb-mitglied-beschreibung", GaestebuchMitgliedBeschreibung);