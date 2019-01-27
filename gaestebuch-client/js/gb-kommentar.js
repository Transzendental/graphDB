import {LitElement, html} from 'https://unpkg.com/@polymer/lit-element/lit-element.js?module';

class GaestebuchKommentar extends LitElement {
    static get properties() {
        return {
            mitglied: {type: String},
            nr: {type: Number},
            kommentar: {type: String}
        };
    }

    constructor() {
        super();
    }

    async loadPHP() {
        await fetch(new Request('http://10.20.110.43/team21/view/getKommentar.php?ID='+this.id), {
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
                this.mitglied = beitraege["mitglied"]["name"];
                this.mitgliedID = beitraege["mitglied"]["ID"];
                this.nodeID = beitraege["kommentar"]["nodeID"];
                this.nr = beitraege["kommentar"]["nr"];
                this.kommentar = beitraege["kommentar"]["kommentar"];
                this.beitragNodeID = beitraege["beitrag"]["nodeID"];
            });
    }

    async deleteKommentar() {
        await fetch(new Request('http://10.20.110.43/team21/view/deleteKommentar.php?ID='+this.nodeID), {
            method: 'GET',
            mode: 'cors',
            cache: 'no-store',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                location.reload(true);
            });
    }

    updateKommentar() {
        gbNeuerKommentar[this.beitragNodeID].chsngeKommentar(this.id);
    }

    render() {
        this.loadPHP();

        return html`<div style="margin-left: 20px;"><b>(${this.nr==null?html`<img src="image/load.gif" border="0" width="20">`:this.nr}) <a href="mitglied.php?id=${this.mitgliedID}">${this.mitglied}</a>:</b> ${this.kommentar} 
<button @click="${this.updateKommentar}">Kommentar bearbeiten</button>
<button @click="${this.deleteKommentar}">Kommentar l&ouml;schen</button></div>`;
    }
}
customElements.define("gb-kommentar", GaestebuchKommentar);