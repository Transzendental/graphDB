import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

const JsonType = {
    fromAttribute: (attr) => { return JSON.parse(attr) },
    toAttribute: (prop) => { return JSON.stringify(prop) }
}

class GaestebuchMitgliedVonMitglied extends LitElement {
    static get properties() {
        return {
            hidden: {type: String},
            load: {type: Number},
            statistik: {type: JsonType}
        };
    }

    constructor() {
        super();

        this.load = false;
        this.statistik = [];
    }

    async loadPHP() {
        this.hidden = html`<img src="image/load.gif" border="0" width="50"><br><br>`;
        await fetch(new Request('http://10.20.110.43/team21/view/getKommentareMitgliedVonMitglied.php?ID='+getUrlVars()["id"]), {
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
                this.statistik = beitraege;
                this.load = true;

                this.hidden = html``;
            });
    }

    render() {
        if(!this.load) {
            this.loadPHP();
        }

        return html`
        
        <h2>Mitglied kommentierte anderes Mitglied so oft:</h2>

        ${this.hidden}
        
        ${this.statistik.map((i) => html`${i["mitglied"]} (${i["count"]})<br>`)}
        
        <hr>
        `;
    }
}
customElements.define("gb-mitglied-kommentare-von-mitglied", GaestebuchMitgliedVonMitglied);