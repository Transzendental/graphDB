import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

const JsonType = {
    fromAttribute: (attr) => { return JSON.parse(attr) },
    toAttribute: (prop) => { return JSON.stringify(prop) }
}

class GaestebuchMitgliedBeitraege extends LitElement {
    static get properties() {
        return {
            hidden: {type: String},
            load: {type: Number},
            beitraege: {type: JsonType}
        };
    }

    constructor() {
        super();

        this.load = false;
        this.beitraege = [];
    }

    async loadPHP() {
        this.hidden = html`<img src="image/load.gif" border="0" width="50"><br><br>`;
        await fetch(new Request('http://10.20.110.43/team21/view/getBeitraegeByMitglied.php?ID='+getUrlVars()["id"]), {
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
            .then((beitraege) => {
                this.beitraege = beitraege;
                this.load = true;

                this.hidden = html``;
            });
    }

    render() {
        if(!this.load) {
            this.loadPHP();
        }

        return html`
        
        <h2>Beitr&auml;ge von Mitglied (${this.beitraege.length} Beitr&auml;ge)</h2>

        ${this.hidden}
        
        ${this.beitraege.map((i) => html`<gb-beitrag id="${i}"></gb-beitrag>`)}
        
        <hr>
        
        `;
    }
}
customElements.define("gb-mitglied-beitraege", GaestebuchMitgliedBeitraege);